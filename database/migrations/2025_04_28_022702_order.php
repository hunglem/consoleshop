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
            $table->enum('status', ['order', 'delivered', 'cancelled'])->default('order');
            $table->string('shipping_address');
            $table->string('shipping_phone');
            $table->string('shipping_email')->nullable();
            $table->string('shipping_name');
            $table->string('shipping_note')->nullable();
            $table->date('deliver_date')->nullable();
            $table->date('cancel_date')->nullable();
            $table->timestamps();
            $foreignKey = $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
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
