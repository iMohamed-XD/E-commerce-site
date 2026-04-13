<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopDeliveryFeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_a_shop_with_a_delivery_fee(): void
    {
        $seller = $this->createSeller();

        $response = $this
            ->actingAs($seller)
            ->post(route('shop.store'), $this->shopPayload([
                'delivery_fee_usd' => '2.50',
            ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $shop = Shop::firstOrFail();

        $this->assertSame(2.5, (float) $shop->delivery_fee_usd);
    }

    public function test_delivery_fee_must_not_be_negative_when_creating_a_shop(): void
    {
        $seller = $this->createSeller();

        $response = $this
            ->actingAs($seller)
            ->from(route('dashboard'))
            ->post(route('shop.store'), $this->shopPayload([
                'delivery_fee_usd' => '-1',
            ]));

        $response
            ->assertSessionHasErrors('delivery_fee_usd')
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseCount('shops', 0);
    }

    public function test_omitted_delivery_fee_defaults_to_zero_when_creating_a_shop(): void
    {
        $seller = $this->createSeller();
        $payload = $this->shopPayload();
        unset($payload['delivery_fee_usd']);

        $response = $this
            ->actingAs($seller)
            ->post(route('shop.store'), $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $shop = Shop::firstOrFail();

        $this->assertSame(0.0, (float) $shop->delivery_fee_usd);
    }

    public function test_seller_can_update_the_shop_delivery_fee(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'delivery_fee_usd' => 1.25,
        ]);

        $response = $this
            ->actingAs($seller)
            ->from(route('dashboard'))
            ->patch(route('shop.update'), $this->shopPayload([
                'slug' => $shop->slug,
                'delivery_fee_usd' => '4.75',
            ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertSame(4.75, (float) $shop->refresh()->delivery_fee_usd);
    }

    public function test_dashboard_displays_delivery_fee_input_for_create_and_edit_states(): void
    {
        $seller = $this->createSeller();

        $this->actingAs($seller)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('رسوم التوصيل (دولار)');

        $this->createShop($seller);

        $this->actingAs($seller)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('رسوم التوصيل (دولار)');
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
            'name' => 'متجر تجريبي',
            'slug' => 'sample-shop',
            'description' => 'وصف المتجر',
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

    private function shopPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'متجر تجريبي',
            'slug' => 'sample-shop',
            'description' => 'وصف المتجر',
            'delivery_fee_usd' => '0',
            'color' => 'navy',
            'location_text' => 'دمشق - المالكي',
            'city' => 'دمشق',
            'same_day_delivery_enabled' => '1',
        ], $overrides);
    }
}
