<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isSeller();
    }

    public function rules(): array
    {
        $usesOptions = $this->boolean('has_options');

        return [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'has_options' => 'nullable|boolean',
            'quantity_available' => $usesOptions ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'options' => $usesOptions ? 'required|array|min:1' : 'nullable|array',
            'options.*.label' => $usesOptions ? 'required|string|max:255' : 'nullable|string|max:255',
            'options.*.quantity' => $usesOptions ? 'required|integer|min:0' : 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'secondary_images' => 'nullable|array|max:3',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_options' => $this->boolean('has_options'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
