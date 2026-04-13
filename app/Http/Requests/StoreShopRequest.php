<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isSeller();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shops,slug',
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
            'cropped_logo' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'delivery_fee_usd' => $this->filled('delivery_fee_usd')
                ? $this->input('delivery_fee_usd')
                : 0,
            'same_day_delivery_enabled' => $this->boolean('same_day_delivery_enabled'),
            'shamcash_is_active' => $this->boolean('shamcash_is_active'),
        ]);
    }
}
