<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateShopRequest extends StoreShopRequest
{
    public function rules(): array
    {
        $shopId = $this->user()?->shop?->id;

        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('shops', 'slug')->ignore($shopId)],
            'description' => 'nullable|string',
            'delivery_fee_usd' => 'nullable|numeric|min:0',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
            'color' => ['required', 'string', Rule::in(array_keys(config('shop_colors', ['navy' => []])))],
            'location_text' => 'required|string|max:255',
            'city' => ['required', 'string', Rule::in(config('syria_cities.cities', []))],
            'same_day_delivery_enabled' => 'nullable|boolean',
            'shamcash_account_number' => 'nullable|string|max:255',
            'shamcash_qr' => 'nullable|image|max:4096',
            'shamcash_is_active' => 'nullable|boolean',
            'shamcash_remove_qr' => 'nullable|boolean',
            'cropped_logo' => 'nullable|string',
        ];
    }
}
