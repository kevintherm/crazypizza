<?php

namespace Database\Factories;

use App\Models\Ingredient;
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
            'description' => $this->faker->sentence(),
            'calories_per_unit' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->randomElement(array_keys(Ingredient::UNITS)),
            'is_vegan' => $this->faker->boolean(),
            'is_gluten_free' => $this->faker->boolean(),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
