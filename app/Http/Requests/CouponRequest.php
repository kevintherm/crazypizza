<?php

namespace App\Http\Requests;

use App\Rules\Money;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|exists:coupons,id',
            'code' => 'required|string|max:50|unique:coupons,code,' . $this->input('id'),
            'discount' => ['required', new Money()],
            'quota' => 'required|integer|min:0|max:999',
            'is_active' => 'required|boolean',
        ];
    }
}
