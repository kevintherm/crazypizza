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
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('activity', 100)->nullable();
            $table->json('additional_info')->nullable();
            $table->date('today')->useCurrent();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['ip_address', 'today', 'url', 'activity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};
