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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->enum('status', ['còn hàng', 'hết hàng'])->default('còn hàng');
            $table->boolean('is_featured')->default(false);
            $table->text('processor_info')->nullable();
            $table->unsignedBigInteger('amount')->default(1);
            $table->text('image_name')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            // Only define foreign keys once, and do not duplicate columns
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
