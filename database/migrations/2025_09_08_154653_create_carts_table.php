<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        /**
         *
         * 1 User = 1 Cart -> Many: Cart Items -> Cart.id, CartItem.pizza_id, quantity, ingredients (json)
         *
         */

        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('coupon_code')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
