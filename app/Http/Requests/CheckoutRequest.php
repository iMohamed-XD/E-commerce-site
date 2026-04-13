<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'nullable|email|max:255',
            'buyer_phone' => 'required|string|min:7|max:32',
            'buyer_location_text' => 'required|string|max:255',
            'buyer_city' => ['required', 'string', Rule::in(config('syria_cities.cities', []))],
            'cart' => 'required|json',
            'promo_code' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cod,shamcash',
            'shamcash_transaction_number' => 'nullable|string|max:255',
        ];
    }
}
