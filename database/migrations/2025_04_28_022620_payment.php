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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->default(0);
            $table->enum('payment_method', ['Credit Card', 'Bank Transfer', 'Cash']);
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed', 'Refunded']);
            $table->timestamps();
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
