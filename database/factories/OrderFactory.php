<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => \App\Models\Order::generateInvoiceNumber(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'delivery_address' => $this->faker->address(),
            'total_amount' => (string) $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->randomElement(array_values(\App\Models\Order::STATUS)),
            'json' => [],
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
