<?php

namespace App\Http\Requests;

use App\Rules\Money;
use Illuminate\Foundation\Http\FormRequest;

class IngredientRequest extends FormRequest
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
            'id' => 'nullable|exists:ingredients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'calories_per_unit' => 'nullable|integer|min:0',
            'unit' => 'nullable|in:' . implode(',', array_keys(\App\Models\Ingredient::UNITS)),
            'is_vegan' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'price_per_unit' => ['required', new Money()],
            'available_as_topping' => 'required|boolean'
        ];
    }
}
