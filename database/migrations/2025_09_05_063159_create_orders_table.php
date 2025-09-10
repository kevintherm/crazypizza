<?php

use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('stripe_session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('delivery_address')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', array_values(Order::STATUS))->default(Order::STATUS['pending']);
            $table->text('notes')->nullable();
            $table->json('json')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
