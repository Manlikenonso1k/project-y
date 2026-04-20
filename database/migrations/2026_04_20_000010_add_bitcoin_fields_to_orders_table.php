<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('btc_address')->nullable()->unique()->after('total');
            $table->decimal('expected_btc', 18, 8)->nullable()->after('btc_address');
            $table->enum('payment_status', [
                'pending_address',
                'pending_confirmation',
                'paid',
                'underpaid',
                'failed',
            ])->default('pending_address')->after('status');
            $table->string('txid')->nullable()->unique()->after('payment_status');

            $table->index(['payment_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex(['payment_status', 'created_at']);
            $table->dropColumn(['btc_address', 'expected_btc', 'payment_status', 'txid']);
        });
    }
};
