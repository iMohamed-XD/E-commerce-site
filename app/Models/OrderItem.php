<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_option_id',
        'product_option_label',
        'quantity',
        'price_at_time_of_order',
        'unit_price_usd',
        'unit_price_syp',
    ];

    protected $casts = [
        'unit_price_usd' => 'decimal:2',
        'unit_price_syp' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productOption()
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function resolvedUnitPriceSyp(): float
    {
        return (float) ($this->unit_price_syp ?? $this->price_at_time_of_order ?? 0);
    }

    public function resolvedUnitPriceUsd(): ?float
    {
        return $this->unit_price_usd !== null ? (float) $this->unit_price_usd : null;
    }
}
