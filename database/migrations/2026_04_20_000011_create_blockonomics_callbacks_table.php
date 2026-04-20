<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blockonomics_callbacks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payload_hash')->unique();
            $table->string('address');
            $table->string('txid')->nullable();
            $table->unsignedBigInteger('value_satoshi')->default(0);
            $table->integer('status_code')->default(0);
            $table->json('payload');
            $table->timestamps();

            $table->index(['order_id', 'status_code']);
            $table->index('txid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blockonomics_callbacks');
    }
};
