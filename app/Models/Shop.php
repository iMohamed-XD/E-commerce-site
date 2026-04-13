<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'delivery_fee_usd',
        'location_text',
        'city',
        'latitude',
        'longitude',
        'same_day_delivery_enabled',
        'logo_path',
        'hero_image_path',
        'color',
        'shamcash_account_number',
        'shamcash_qr_path',
        'shamcash_is_active',
    ];

    protected $casts = [
        'delivery_fee_usd' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'same_day_delivery_enabled' => 'boolean',
        'shamcash_is_active' => 'boolean',
    ];

    public function getColorHexAttribute(): string
    {
        return config('shop_colors')[$this->color]['hex'] ?? '#0d1b4b';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function hasLocation(): bool
    {
        return !empty($this->city) || !empty($this->location_text);
    }
}
