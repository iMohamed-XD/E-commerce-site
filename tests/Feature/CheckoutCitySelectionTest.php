<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CheckoutCitySelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_must_submit_city_and_full_delivery_address(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $product = $this->createProduct($shop);

        $response = $this->from(route('shop.show', $shop->slug))
            ->post(route('shop.checkout', $shop->slug), [
                'buyer_name' => 'Buyer Test',
                'buyer_phone' => '0999999999',
                'buyer_location_text' => '',
                'buyer_city' => '',
                'cart' => json_encode([
                    [
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'cart_key' => $product->id . ':simple',
                    ],
                ], JSON_THROW_ON_ERROR),
                'payment_method' => 'cod',
            ]);

        $response
            ->assertSessionHasErrors(['buyer_location_text', 'buyer_city'])
            ->assertRedirect(route('shop.show', $shop->slug));
    }

    public function test_checkout_snapshots_same_day_estimate_with_canonicalized_seller_city(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'city' => 'Damascus',
            'same_day_delivery_enabled' => true,
        ]);
        $product = $this->createProduct($shop);

        $response = $this->post(route('shop.checkout', $shop->slug), [
            'buyer_name' => 'Buyer Test',
            'buyer_email' => 'buyer@example.com',
            'buyer_phone' => '0999999999',
            'buyer_location_text' => 'دمشق - أبو رمانة',
            'buyer_city' => 'دمشق',
            'cart' => json_encode([
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'cart_key' => $product->id . ':simple',
                ],
            ], JSON_THROW_ON_ERROR),
            'payment_method' => 'cod',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('shop.show', $shop->slug));

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'buyer_city' => 'دمشق',
            'buyer_location_text' => 'دمشق - أبو رمانة',
            'seller_city_snapshot' => 'دمشق',
            'delivery_estimate' => 'same_day',
        ]);
    }

    public function test_checkout_uses_multiple_days_when_buyer_city_differs(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'city' => 'دمشق',
            'same_day_delivery_enabled' => true,
        ]);
        $product = $this->createProduct($shop);

        $this->post(route('shop.checkout', $shop->slug), [
            'buyer_name' => 'Buyer Test',
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

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'delivery_estimate' => 'multiple_days',
        ]);
    }

    public function test_checkout_uses_multiple_days_when_seller_disables_same_day_delivery(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'city' => 'دمشق',
            'same_day_delivery_enabled' => false,
        ]);
        $product = $this->createProduct($shop);

        $this->post(route('shop.checkout', $shop->slug), [
            'buyer_name' => 'Buyer Test',
            'buyer_phone' => '0999999999',
            'buyer_location_text' => 'دمشق - المزة',
            'buyer_city' => 'دمشق',
            'cart' => json_encode([
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'cart_key' => $product->id . ':simple',
                ],
            ], JSON_THROW_ON_ERROR),
            'payment_method' => 'cod',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'delivery_estimate' => 'multiple_days',
        ]);
    }

    public function test_storefront_checkout_form_uses_city_dropdown_without_google_maps_dependency(): void
    {
        $this->mockExchangeRate(12000);

        $seller = $this->createSeller();
        $shop = $this->createShop($seller);

        $this->get(route('shop.show', $shop->slug))
            ->assertOk()
            ->assertSee('name="buyer_city"', false)
            ->assertSee('اختر المدينة')
            ->assertDontSee('maps.googleapis.com', false)
            ->assertDontSee('mahlyInitShopCheckoutMaps', false)
            ->assertDontSee('buyer_latitude', false)
            ->assertDontSee('buyer_longitude', false);
    }

    public function test_seller_orders_page_renders_old_orders_without_city_snapshots(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);

        Order::create([
            'shop_id' => $shop->id,
            'buyer_name' => 'عميل قديم',
            'buyer_email' => null,
            'buyer_phone' => '0999999999',
            'buyer_address' => 'عنوان قديم محفوظ',
            'buyer_location_text' => null,
            'buyer_city' => null,
            'seller_city_snapshot' => null,
            'delivery_estimate' => null,
            'usd_to_syp_rate' => 12000,
            'subtotal_usd' => 10,
            'subtotal_syp' => 120000,
            'discount_amount_usd' => 0,
            'discount_amount_syp' => 0,
            'delivery_fee_usd' => 0,
            'delivery_fee_syp' => 0,
            'final_total_usd' => 10,
            'final_total_syp' => 120000,
            'total_amount' => 120000,
            'status' => 'pending',
        ]);

        $this->actingAs($seller)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSeeText('عنوان قديم محفوظ');
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
            'name' => 'متجر الطلبات',
            'slug' => 'checkout-shop',
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
