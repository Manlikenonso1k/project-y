<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->json('images')->nullable()->after('image');
        });

        DB::table('products')
            ->select(['id', 'image'])
            ->whereNotNull('image')
            ->orderBy('id')
            ->chunkById(100, function ($products): void {
                foreach ($products as $product) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update([
                            'images' => json_encode([$product->image]),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('images');
        });
    }
};
