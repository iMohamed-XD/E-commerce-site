<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Shop;
use App\Models\User;
use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CheckoutDeliveryFeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_snapshots_delivery_fee_and_final_totals(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'delivery_fee_usd' => 2.00,
        ]);
        $product = $this->createProduct($shop, [
            'price' => 10.00,
        ]);

        PromoCode::create([
            'shop_id' => $shop->id,
            'code' => 'SAVE10',
            'discount_percentage' => 10,
            'is_active' => true,
        ]);

        $response = $this->post(route('shop.checkout', $shop->slug), [
            'buyer_name' => 'Buyer Test',
            'buyer_email' => 'buyer@example.com',
            'buyer_phone' => '0999999999',
            'buyer_location_text' => 'دمشق - أبو رمانة',
            'buyer_city' => 'دمشق',
            'cart' => json_encode([
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'cart_key' => $product->id . ':simple',
                ],
            ], JSON_THROW_ON_ERROR),
            'promo_code' => 'SAVE10',
            'payment_method' => 'cod',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('shop.show', $shop->slug));

        $order = Order::query()->with('items')->firstOrFail();

        $this->assertSame(20.0, (float) $order->subtotal_usd);
        $this->assertSame(240000.0, (float) $order->subtotal_syp);
        $this->assertSame(2.0, (float) $order->discount_amount_usd);
        $this->assertSame(24000.0, (float) $order->discount_amount_syp);
        $this->assertSame(2.0, (float) $order->delivery_fee_usd);
        $this->assertSame(24000.0, (float) $order->delivery_fee_syp);
        $this->assertSame(20.0, (float) $order->final_total_usd);
        $this->assertSame(240000.0, (float) $order->final_total_syp);
        $this->assertSame(240000.0, (float) $order->total_amount);
        $this->assertCount(1, $order->items);
    }

    public function test_storefront_and_seller_orders_page_show_delivery_fee_breakdown(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'delivery_fee_usd' => 2.00,
            'slug' => 'delivery-demo-shop',
        ]);
        $product = $this->createProduct($shop, [
            'price' => 10.00,
            'name' => 'منتج الاختبار',
        ]);

        $this->get(route('shop.show', $shop->slug))
            ->assertOk()
            ->assertSeeText('رسوم التوصيل')
            ->assertSeeText('الإجمالي النهائي');

        $this->post(route('shop.checkout', $shop->slug), [
            'buyer_name' => 'Buyer Test',
            'buyer_email' => 'buyer@example.com',
            'buyer_phone' => '0999999999',
            'buyer_location_text' => 'حمص - الوعر',
            'buyer_city' => 'حمص',
            'cart' => json_encode([
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'cart_key' => $product->id . ':simple',
                ],
            ], JSON_THROW_ON_ERROR),
            'payment_method' => 'cod',
        ])->assertSessionHasNoErrors();

        $this->mockExchangeRate(15000);

        $this->actingAs($seller)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSeeText('رسوم التوصيل')
            ->assertSeeText('الإجمالي النهائي')
            ->assertSeeText('$2.00')
            ->assertSeeText('24,000.00 ل.س')
            ->assertSeeText('144,000.00 ل.س')
            ->assertDontSeeText('180,000.00 ل.س');
    }

    private function createSeller(): User
    {
        return User::factory()->create([
            'role' => 'seller',
        ]);
    }

    private function createShop(User $seller, array $overrides = []): Shop
    {
        return Shop::create(array_merge([
            'user_id' => $seller->id,
            'name' => 'متجر الشحن',
            'slug' => 'shipping-shop',
            'description' => 'متجر للاختبار',
            'delivery_fee_usd' => 0,
            'location_text' => 'دمشق - المالكي',
            'city' => 'دمشق',
            'latitude' => null,
            'longitude' => null,
            'same_day_delivery_enabled' => true,
            'color' => 'navy',
            'shamcash_is_active' => false,
        ], $overrides));
    }

    private function createProduct(Shop $shop, array $overrides = []): Product
    {
        return Product::create(array_merge([
            'shop_id' => $shop->id,
            'category_id' => null,
            'name' => 'منتج',
            'description' => 'وصف المنتج',
            'price' => 10.00,
            'quantity_available' => 10,
            'has_options' => false,
            'is_active' => true,
            'discount_percent' => null,
            'discount_active' => false,
        ], $overrides));
    }

    private function mockExchangeRate(float $rate): void
    {
        $this->mock(ExchangeRateService::class, function (MockInterface $mock) use ($rate): void {
            $mock->shouldReceive('getCurrentUsdToSypRate')->andReturn($rate);
            $mock->shouldReceive('convertUsdToSyp')
                ->andReturnUsing(fn (float $amountUsd, ?float $customRate = null): float => round($amountUsd * ($customRate ?? $rate), 2));
        });
    }
}
