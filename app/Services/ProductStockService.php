<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Shop;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ProductStockService
{
    public function syncProductOptions(Product $product, array $options): void
    {
        $product->productOptions()->delete();

        foreach ($this->normalizeOptions($options) as $option) {
            $product->productOptions()->create($option);
        }
    }

    public function resolveCheckoutItems(Shop $shop, array $cartLines): array
    {
        $groupedLines = collect($cartLines)
            ->map(fn (mixed $line) => is_array($line) ? $line : [])
            ->map(function (array $line): array {
                $productId = (int) ($line['product_id'] ?? $line['id'] ?? 0);
                $optionId = isset($line['option_id']) && $line['option_id'] !== '' ? (int) $line['option_id'] : null;
                $quantity = max(0, (int) ($line['quantity'] ?? 0));
                $cartKey = $line['cart_key'] ?? ($productId . ':' . ($optionId ?: 'simple'));

                return [
                    'product_id' => $productId,
                    'option_id' => $optionId,
                    'quantity' => $quantity,
                    'cart_key' => (string) $cartKey,
                ];
            })
            ->filter(fn (array $line) => $line['product_id'] > 0 && $line['quantity'] > 0)
            ->groupBy('cart_key')
            ->map(function (Collection $lines): array {
                $first = $lines->first();

                return [
                    'product_id' => $first['product_id'],
                    'option_id' => $first['option_id'],
                    'quantity' => $lines->sum('quantity'),
                    'cart_key' => $first['cart_key'],
                ];
            })
            ->values();

        if ($groupedLines->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'عربة التسوق فارغة أو غير صالحة.',
            ]);
        }

        $products = $shop->products()
            ->with('productOptions')
            ->whereIn('id', $groupedLines->pluck('product_id'))
            ->where('is_active', true)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        return $groupedLines->map(function (array $line) use ($products): array {
            /** @var Product|null $product */
            $product = $products->get($line['product_id']);

            if (!$product) {
                throw ValidationException::withMessages([
                    'cart' => 'بعض المنتجات في السلة لم تعد متاحة.',
                ]);
            }

            $productOption = null;

            if ($product->has_options) {
                if (!$line['option_id']) {
                    throw ValidationException::withMessages([
                        'cart' => "يرجى اختيار خيار صالح للمنتج {$product->name}.",
                    ]);
                }

                /** @var ProductOption|null $productOption */
                $productOption = $product->productOptions->firstWhere('id', $line['option_id']);

                if (!$productOption) {
                    throw ValidationException::withMessages([
                        'cart' => "الخيار المحدد للمنتج {$product->name} لم يعد متاحاً.",
                    ]);
                }

                if ((int) $productOption->quantity < (int) $line['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => "الكمية المتاحة من {$product->name} - {$productOption->label} غير كافية.",
                    ]);
                }
            } else {
                if ((int) $product->quantity_available < (int) $line['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => "الكمية المتاحة من {$product->name} غير كافية. المتوفر حالياً: {$product->quantity_available}.",
                    ]);
                }
            }

            return [
                'cart_key' => $line['cart_key'],
                'product' => $product,
                'product_option' => $productOption,
                'quantity' => (int) $line['quantity'],
            ];
        })->all();
    }

    public function decrementResolvedItems(array $resolvedItems): void
    {
        foreach ($resolvedItems as $resolvedItem) {
            /** @var Product $product */
            $product = $resolvedItem['product'];
            /** @var ProductOption|null $productOption */
            $productOption = $resolvedItem['product_option'];
            $quantity = (int) $resolvedItem['quantity'];

            if ($productOption) {
                $updated = $productOption->newQuery()
                    ->whereKey($productOption->id)
                    ->where('quantity', '>=', $quantity)
                    ->decrement('quantity', $quantity);

                if ($updated === 0) {
                    throw ValidationException::withMessages([
                        'cart' => "الكمية المتاحة من {$product->name} - {$productOption->label} لم تعد كافية، يرجى تحديث السلة.",
                    ]);
                }

                continue;
            }

            $updated = $product->newQuery()
                ->whereKey($product->id)
                ->where('quantity_available', '>=', $quantity)
                ->decrement('quantity_available', $quantity);

            if ($updated === 0) {
                throw ValidationException::withMessages([
                    'cart' => "الكمية المتاحة من {$product->name} لم تعد كافية، يرجى تحديث السلة.",
                ]);
            }
        }
    }

    public function restoreOrderItem(OrderItem $orderItem): void
    {
        $orderItem->loadMissing(['product', 'productOption']);

        if ($orderItem->productOption) {
            $orderItem->productOption->increment('quantity', (int) $orderItem->quantity);

            return;
        }

        if ($orderItem->product && $orderItem->product->has_options && $orderItem->product_option_label) {
            $matchingOption = $orderItem->product->productOptions()
                ->where('label', $orderItem->product_option_label)
                ->first();

            if ($matchingOption) {
                $matchingOption->increment('quantity', (int) $orderItem->quantity);

                return;
            }
        }

        if ($orderItem->product) {
            $orderItem->product->increment('quantity_available', (int) $orderItem->quantity);
        }
    }

    protected function normalizeOptions(array $options): array
    {
        return collect($options)
            ->map(fn (mixed $option) => is_array($option) ? $option : [])
            ->map(function (array $option): array {
                return [
                    'label' => trim((string) ($option['label'] ?? '')),
                    'quantity' => max(0, (int) ($option['quantity'] ?? 0)),
                ];
            })
            ->filter(fn (array $option) => $option['label'] !== '')
            ->values()
            ->all();
    }
}
