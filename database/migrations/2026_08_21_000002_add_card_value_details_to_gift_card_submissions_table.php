<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gift_card_submissions', function (Blueprint $table): void {
            $table->decimal('card_value_per_image', 10, 2)->nullable()->after('card_amount');
            $table->unsignedTinyInteger('image_count')->nullable()->after('card_value_per_image');
        });
    }

    public function down(): void
    {
        Schema::table('gift_card_submissions', function (Blueprint $table): void {
            $table->dropColumn(['card_value_per_image', 'image_count']);
        });
    }
};