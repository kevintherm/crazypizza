<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Pizza;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('123')
        ]);

        Pizza::factory(2)->create();

        // Create ingredients for each pizza
        foreach (Pizza::all() as $pizza) {
            $pizza->ingredients()->attach(
                Ingredient::factory(rand(1, 3))->create()->pluck('id')->toArray()
            );
        }

        Order::factory(20)->create();
    }
}
