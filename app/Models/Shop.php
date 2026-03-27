<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['user_id', 'name', 'slug', 'description', 'logo_path', 'hero_image_path'];

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
}
