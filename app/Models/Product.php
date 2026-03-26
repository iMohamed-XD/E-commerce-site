<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id', 'name', 'category', 'description', 'price', 'image_path', 'is_active',
        'discount_percent', 'discount_active', 'discount_starts_at', 'discount_ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_active' => 'boolean',
        'discount_percent' => 'decimal:2',
        'discount_starts_at' => 'datetime',
        'discount_ends_at' => 'datetime',
    ];

    /**
     * Get the effective price for display/orders (applies discount_percent if active).
     */
    public function effectivePrice(): float
    {
        if ($this->hasActiveDiscount()) {
            return round($this->price * (1 - $this->discount_percent / 100), 2);
        }
        return (float) $this->price;
    }

    public function hasActiveDiscount(): bool
    {
        return (bool) ($this->discount_active && $this->discount_percent > 0);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
