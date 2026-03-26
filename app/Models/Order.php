<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['shop_id', 'buyer_name', 'buyer_email', 'buyer_phone', 'buyer_address', 'promo_code_used', 'total_amount', 'status'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
