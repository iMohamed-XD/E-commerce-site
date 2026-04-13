<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopShowStockPayloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_page_includes_stock_payload_for_products_outside_current_pagination_page(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $shop = Shop::create([
            'user_id' => $seller->id,
            'name' => 'متجر الخيارات',
            'slug' => 'option-shop',
            'description' => 'متجر للاختبار',
            'delivery_fee_usd' => 0,
            'location_text' => 'دمشق - المالكي',
            'city' => 'دمشق',
            'same_day_delivery_enabled' => true,
            'color' => 'navy',
            'shamcash_is_active' => false,
        ]);

        $category = Category::create([
            'shop_id' => $shop->id,
            'name' => 'ملابس',
        ]);

        foreach (range(1, 20) as $index) {
            Product::create([
                'shop_id' => $shop->id,
                'category_id' => $category->id,
                'name' => 'منتج عادي ' . $index,
                'description' => 'منتج للاختبار',
                'price' => 10,
                'quantity_available' => 3,
                'has_options' => false,
                'is_active' => true,
                'discount_percent' => 0,
                'discount_active' => false,
            ]);
        }

        $optionProduct = Product::create([
            'shop_id' => $shop->id,
            'category_id' => $category->id,
            'name' => 'قميص المقاسات',
            'description' => 'منتج خيارات',
            'price' => 25,
            'quantity_available' => 0,
            'has_options' => true,
            'is_active' => true,
            'discount_percent' => 0,
            'discount_active' => false,
        ]);

        $lateOption = ProductOption::create([
            'product_id' => $optionProduct->id,
            'label' => 'L',
            'quantity' => 10,
        ]);

        $response = $this->get(route('shop.show', $shop->slug));

        $response
            ->assertOk()
            ->assertSee('"' . $optionProduct->id . '":10', false)
            ->assertSee('"' . $lateOption->id . '":10', false);
    }
}
