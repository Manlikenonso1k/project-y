<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('item', 'item_number');

            $table->smallInteger('year')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('subcategory')->nullable();
            $table->bigInteger('mileage')->nullable();
            $table->smallInteger('horsepower')->nullable();
            $table->string('url')->nullable();
        });

        // Normalise any previously stored item values that still carry the
        // 'Item:' prefix so item_number is a clean unique key.
        $legacyItems = DB::table('products')
            ->where('item_number', 'like', 'Item%')
            ->get(['id', 'item_number']);

        foreach ($legacyItems as $row) {
            DB::table('products')->where('id', $row->id)->update([
                'item_number' => preg_replace('/^item\s*:\s*/i', '', (string) $row->item_number),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['year', 'manufacturer', 'subcategory', 'mileage', 'horsepower', 'url']);
            $table->renameColumn('item_number', 'item');
        });
    }
};