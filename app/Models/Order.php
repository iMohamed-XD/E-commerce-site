<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'shop_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_address',
        'buyer_location_text',
        'buyer_city',
        'seller_city_snapshot',
        'delivery_estimate',
        'promo_code_used',
        'payment_method',
        'shamcash_transaction_number',
        'usd_to_syp_rate',
        'subtotal_usd',
        'subtotal_syp',
        'discount_amount_usd',
        'discount_amount_syp',
        'delivery_fee_usd',
        'delivery_fee_syp',
        'final_total_usd',
        'final_total_syp',
        'total_amount',
        'status',
        'archived_from_status',
    ];

    protected $casts = [
        'usd_to_syp_rate' => 'decimal:6',
        'subtotal_usd' => 'decimal:2',
        'subtotal_syp' => 'decimal:2',
        'discount_amount_usd' => 'decimal:2',
        'discount_amount_syp' => 'decimal:2',
        'delivery_fee_usd' => 'decimal:2',
        'delivery_fee_syp' => 'decimal:2',
        'final_total_usd' => 'decimal:2',
        'final_total_syp' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getDeliveryEstimateLabelAttribute(): string
    {
        return match ($this->delivery_estimate) {
            'same_day' => 'خلال اليوم',
            default => 'خلال عدة أيام',
        };
    }

    public function finalTotalSypValue(): float
    {
        return (float) ($this->final_total_syp ?? $this->total_amount ?? 0);
    }

    public function productSubtotalUsdValue(): ?float
    {
        if ($this->subtotal_usd !== null) {
            return (float) $this->subtotal_usd;
        }

        return $this->finalTotalUsdValue();
    }

    public function productSubtotalSypValue(): float
    {
        if ($this->subtotal_syp !== null) {
            return (float) $this->subtotal_syp;
        }

        return $this->finalTotalSypValue();
    }

    public function discountAmountUsdValue(): float
    {
        return (float) ($this->discount_amount_usd ?? 0);
    }

    public function discountAmountSypValue(): float
    {
        return (float) ($this->discount_amount_syp ?? 0);
    }

    public function discountedProductsSubtotalUsdValue(): ?float
    {
        $subtotalUsd = $this->productSubtotalUsdValue();

        if ($subtotalUsd === null) {
            return null;
        }

        return max(0, round($subtotalUsd - $this->discountAmountUsdValue(), 2));
    }

    public function discountedProductsSubtotalSypValue(): float
    {
        return max(0, round($this->productSubtotalSypValue() - $this->discountAmountSypValue(), 2));
    }

    public function deliveryFeeUsdValue(): float
    {
        return (float) ($this->delivery_fee_usd ?? 0);
    }

    public function deliveryFeeSypValue(): float
    {
        return (float) ($this->delivery_fee_syp ?? 0);
    }

    public function finalTotalUsdValue(): ?float
    {
        return $this->final_total_usd !== null ? (float) $this->final_total_usd : null;
    }
}
