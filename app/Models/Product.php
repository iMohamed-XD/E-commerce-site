<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id',
        'category_id',
        'name',
        'description',
        'price',
        'image_path',
        'is_active',
        'discount_percent',
        'discount_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_active' => 'boolean',
        'discount_percent' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function allImages(): array
    {
        $paths = [];
        if ($this->image_path) {
            $paths[] = $this->image_path;
        }
        foreach ($this->productImages as $img) {
            $paths[] = $img->path;
        }
        return array_slice($paths, 0, 4);
    }
}
