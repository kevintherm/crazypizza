<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'image' => 'https://placehold.co/400',
            'description' => $this->faker->sentence(),
            'calories_per_unit' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->randomElement(array_keys(Ingredient::UNITS)),
            'is_vegan' => $this->faker->boolean(),
            'is_gluten_free' => $this->faker->boolean(),
            'available_as_topping' => $this->faker->boolean(),
            'price_per_unit' => new Money((string) $this->faker->randomDigit()),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
