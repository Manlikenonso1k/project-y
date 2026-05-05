<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight', 8, 2);
            $table->enum('unit', ['g', 'kg'])->default('g');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'weight', 'unit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
