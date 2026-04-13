<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCitySelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_a_shop_with_a_city_from_the_shared_list(): void
    {
        $seller = $this->createSeller();

        $response = $this
            ->actingAs($seller)
            ->post(route('shop.store'), $this->shopPayload([
                'city' => 'دمشق',
                'location_text' => 'دمشق - المالكي',
            ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('shops', [
            'user_id' => $seller->id,
            'city' => 'دمشق',
            'location_text' => 'دمشق - المالكي',
            'same_day_delivery_enabled' => 1,
        ]);
    }

    public function test_seller_can_update_the_city_and_disable_same_day_delivery(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller, [
            'city' => 'دمشق',
            'same_day_delivery_enabled' => true,
        ]);

        $payload = $this->shopPayload([
            'slug' => $shop->slug,
            'city' => 'حلب',
            'location_text' => 'حلب - الشهباء الجديدة',
        ]);

        unset($payload['same_day_delivery_enabled']);

        $response = $this
            ->actingAs($seller)
            ->from(route('dashboard'))
            ->patch(route('shop.update'), $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('shops', [
            'id' => $shop->id,
            'city' => 'حلب',
            'location_text' => 'حلب - الشهباء الجديدة',
            'same_day_delivery_enabled' => 0,
        ]);
    }

    public function test_seller_city_must_be_one_of_the_allowed_syrian_cities(): void
    {
        $seller = $this->createSeller();

        $response = $this
            ->actingAs($seller)
            ->from(route('dashboard'))
            ->post(route('shop.store'), $this->shopPayload([
                'city' => 'بيروت',
            ]));

        $response
            ->assertSessionHasErrors('city')
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseCount('shops', 0);
    }

    public function test_dashboard_create_form_uses_shared_city_dropdown_without_google_maps_dependency(): void
    {
        $seller = $this->createSeller();

        $this->actingAs($seller)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('id="create_city"', false)
            ->assertSee('name="city"', false)
            ->assertSee('اختر المدينة', false)
            ->assertDontSee('maps.googleapis.com', false)
            ->assertDontSee('mahlyInitDashboardMaps', false);
    }

    public function test_dashboard_edit_form_preselects_legacy_city_values_safely_in_shared_dropdown(): void
    {
        $seller = $this->createSeller();
        $this->createShop($seller, [
            'city' => 'Damascus',
        ]);

        $this->actingAs($seller)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('id="edit_city"', false)
            ->assertSee('name="city"', false)
            ->assertSee('value="دمشق"', false)
            ->assertDontSee('maps.googleapis.com', false)
            ->assertDontSee('mahlyInitDashboardMaps', false);
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
            'name' => 'متجر المدن',
            'slug' => 'city-shop',
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

    private function shopPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'متجر المدن',
            'slug' => 'city-shop',
            'description' => 'متجر للاختبار',
            'delivery_fee_usd' => '0',
            'color' => 'navy',
            'location_text' => 'دمشق - المالكي',
            'city' => 'دمشق',
            'same_day_delivery_enabled' => '1',
        ], $overrides);
    }
}
