<?php

use App\Models\Pizza;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cart_id')->index();
            $table->uuid('pizza_id')->index();
            $table->json('ingredients')->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('size', array_values(Pizza::SIZE))->default(Pizza::SIZE['small']);
            $table->timestamps();

            $table->unique(['cart_id', 'pizza_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
