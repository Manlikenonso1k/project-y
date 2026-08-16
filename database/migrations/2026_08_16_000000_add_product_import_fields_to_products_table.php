<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add spec/listing columns used by the CSV/Excel product importer.
     *
     * The FULLTEXT index is only created on MySQL/MariaDB because those are
     * the only drivers supported by the current schema out of the box.
     * SQLite (local/test) and other drivers fall back to LIKE searches.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('engine')->nullable()->after('description');
            $table->string('transmission')->nullable()->after('engine');
            $table->string('gvw')->nullable()->after('transmission');
            $table->string('store')->nullable()->after('gvw');
            $table->string('ecm_miles')->nullable()->after('store');
            $table->string('youtube_url')->nullable()->after('ecm_miles');
            $table->text('extra_description')->nullable()->after('youtube_url');
            $table->string('image_url')->nullable()->after('image');
        });

        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(
                'ALTER TABLE products ADD FULLTEXT INDEX products_search_fulltext
                 (name, engine, transmission, gvw, store, ecm_miles)'
            );
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_search_fulltext');
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'engine',
                'transmission',
                'gvw',
                'store',
                'ecm_miles',
                'youtube_url',
                'extra_description',
                'image_url',
            ]);
        });
    }
};
