<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'logo_path', 'qr_path', 'account_id', 'link',
        'details', 'is_active', 'sort_order'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? Storage::url($this->logo_path)
            : null;
    }
    
    public function getQrUrlAttribute(): ?string
    {
        return $this->qr_path
            ? Storage::url($this->qr_path)
            : null;
    }
}
