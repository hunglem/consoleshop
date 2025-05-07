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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('status')->unique();
            $table->timestamp('order_date_shipped');
            $table->timestamp('order_date_received')->nullable();
            $foreignKey = $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $foreignKey = $table->foreignId('payment_id')->constrained('payment')->onDelete('cascade')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
