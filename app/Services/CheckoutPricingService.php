<?php

namespace App\Services;

class CheckoutPricingService
{
    public function __construct(
        protected ExchangeRateService $exchangeRateService,
    ) {
    }

    public function buildPricingSnapshot(
        array $resolvedItems,
        float $usdToSypRate,
        float $promoDiscountPercent = 0,
        float $deliveryFeeUsd = 0,
    ): array {
        $subtotalUsd = 0;
        $subtotalSyp = 0;
        $lineItems = [];

        foreach ($resolvedItems as $resolvedItem) {
            $product = $resolvedItem['product'];
            $quantity = (int) $resolvedItem['quantity'];
            $unitPriceUsd = round($product->effectivePrice(), 2);
            $unitPriceSyp = $this->exchangeRateService->convertUsdToSyp($unitPriceUsd, $usdToSypRate);

            $subtotalUsd += $unitPriceUsd * $quantity;
            $subtotalSyp += $unitPriceSyp * $quantity;

            $lineItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'product_option_id' => $resolvedItem['product_option']?->id,
                'product_option_label' => $resolvedItem['product_option']?->label,
                'unit_price_usd' => $unitPriceUsd,
                'unit_price_syp' => $unitPriceSyp,
            ];
        }

        $subtotalUsd = round($subtotalUsd, 2);
        $subtotalSyp = round($subtotalSyp, 2);
        $discountAmountUsd = $promoDiscountPercent > 0
            ? round($subtotalUsd * ($promoDiscountPercent / 100), 2)
            : 0.0;
        $discountAmountSyp = $promoDiscountPercent > 0
            ? round($subtotalSyp * ($promoDiscountPercent / 100), 2)
            : 0.0;
        $discountedProductsSubtotalUsd = round($subtotalUsd - $discountAmountUsd, 2);
        $discountedProductsSubtotalSyp = round($subtotalSyp - $discountAmountSyp, 2);
        $deliveryFeeUsd = round(max(0, $deliveryFeeUsd), 2);
        $deliveryFeeSyp = $this->exchangeRateService->convertUsdToSyp($deliveryFeeUsd, $usdToSypRate);
        $finalTotalUsd = round($discountedProductsSubtotalUsd + $deliveryFeeUsd, 2);
        $finalTotalSyp = round($discountedProductsSubtotalSyp + $deliveryFeeSyp, 2);

        return [
            'usd_to_syp_rate' => $usdToSypRate,
            'subtotal_usd' => $subtotalUsd,
            'subtotal_syp' => $subtotalSyp,
            'discount_amount_usd' => $discountAmountUsd,
            'discount_amount_syp' => $discountAmountSyp,
            'discounted_products_subtotal_usd' => $discountedProductsSubtotalUsd,
            'discounted_products_subtotal_syp' => $discountedProductsSubtotalSyp,
            'delivery_fee_usd' => $deliveryFeeUsd,
            'delivery_fee_syp' => $deliveryFeeSyp,
            'final_total_usd' => $finalTotalUsd,
            'final_total_syp' => $finalTotalSyp,
            'line_items' => $lineItems,
        ];
    }
}
