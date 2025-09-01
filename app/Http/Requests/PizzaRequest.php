<?php

namespace App\Http\Requests;

use App\Rules\Money;
use Illuminate\Foundation\Http\FormRequest;

class PizzaRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => ['required', new Money()],
            'image' => 'nullable|image|max:2048',
            'ingredients' => 'nullable|array',
            'ingredients.*.0' => 'integer|exists:ingredients,id', // id
            'ingredients.*.1' => 'integer|min:1', // quantity
        ];
    }
}
