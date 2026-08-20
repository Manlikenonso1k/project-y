<?php
// Quick diagnostic: check what filter data is available
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Services\FacetBucketService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Product table columns ===\n";
$columns = Schema::getColumnListing('products');
echo implode(', ', $columns) . "\n\n";

echo "=== Active products: " . Product::where('is_active', true)->count() . " ===\n\n";

echo "=== Subcategory counts ===\n";
$subcats = Product::where('is_active', true)
    ->whereNotNull('subcategory')
    ->select('subcategory', DB::raw('count(*) as total'))
    ->groupBy('subcategory')
    ->orderBy('subcategory')
    ->get();
echo $subcats->toJson(JSON_PRETTY_PRINT) . "\n\n";

echo "=== Year counts ===\n";
$years = Product::where('is_active', true)
    ->whereNotNull('year')
    ->select('year', DB::raw('count(*) as total'))
    ->groupBy('year')
    ->orderBy('year')
    ->get();
echo $years->toJson(JSON_PRETTY_PRINT) . "\n\n";

echo "=== Sample product (first) ===\n";
$p = Product::where('is_active', true)->first();
if ($p) {
    echo "subcategory: " . var_export($p->subcategory, true) . "\n";
    echo "year: " . var_export($p->year, true) . "\n";
    echo "mileage: " . var_export($p->mileage, true) . "\n";
    echo "horsepower: " . var_export($p->horsepower, true) . "\n";
}

echo "\n=== FacetBucketService check ===\n";
try {
    $fbs = new FacetBucketService();
    $baseScope = Product::where('is_active', true);
    $mileageBuckets = $fbs->buildBuckets('mileage', clone $baseScope);
    echo "Mileage buckets: " . json_encode($mileageBuckets, JSON_PRETTY_PRINT) . "\n";
    $hpBuckets = $fbs->buildBuckets('horsepower', clone $baseScope);
    echo "HP buckets: " . json_encode($hpBuckets, JSON_PRETTY_PRINT) . "\n";
} catch (\Throwable $e) {
    echo "FacetBucketService error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
