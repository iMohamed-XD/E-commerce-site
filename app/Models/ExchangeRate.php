<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'base_currency',
        'target_currency',
        'rate',
        'provider',
        'effective_date',
        'retrieved_at',
        'meta',
    ];

    protected $casts = [
        'rate' => 'decimal:6',
        'effective_date' => 'date',
        'retrieved_at' => 'datetime',
        'meta' => 'array',
    ];

    public function scopeForPair(Builder $query, string $baseCurrency, string $targetCurrency): Builder
    {
        return $query
            ->where('base_currency', strtoupper($baseCurrency))
            ->where('target_currency', strtoupper($targetCurrency));
    }
}
