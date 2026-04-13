<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CheckoutPricingService;
use Tests\TestCase;

class CheckoutPricingServiceTest extends TestCase
{
    public function test_builds_pricing_snapshot_with_delivery_fee_excluded_from_discount(): void
    {
        $product = new Product();
        $product->id = 1;
        $product->name = 'منتج تجريبي';
        $product->price = 10.00;
        $product->discount_percent = null;
        $product->discount_active = false;

        $service = app(CheckoutPricingService::class);

        $snapshot = $service->buildPricingSnapshot(
            [
                [
                    'product' => $product,
                    'product_option' => null,
                    'quantity' => 2,
                ],
            ],
            12000,
            10,
            3,
        );

        $this->assertSame(20.0, $snapshot['subtotal_usd']);
        $this->assertSame(240000.0, $snapshot['subtotal_syp']);
        $this->assertSame(2.0, $snapshot['discount_amount_usd']);
        $this->assertSame(24000.0, $snapshot['discount_amount_syp']);
        $this->assertSame(18.0, $snapshot['discounted_products_subtotal_usd']);
        $this->assertSame(216000.0, $snapshot['discounted_products_subtotal_syp']);
        $this->assertSame(3.0, $snapshot['delivery_fee_usd']);
        $this->assertSame(36000.0, $snapshot['delivery_fee_syp']);
        $this->assertSame(21.0, $snapshot['final_total_usd']);
        $this->assertSame(252000.0, $snapshot['final_total_syp']);
    }
}
