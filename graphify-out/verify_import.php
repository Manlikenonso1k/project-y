<?php

/**
 * Smoke-test the product importer against real CSV files without persisting
 * anything: every run happens inside a transaction that gets rolled back.
 */

require __DIR__.'/../vendor/autoload.php';

/** @var Illuminate\Foundation\Application $app */
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Services\ProductImportService;
use Illuminate\Support\Facades\DB;

function dryRun(ProductImportService $service, string $file): void
{
    if (! is_file($file)) {
        echo basename($file)."  =>  SKIPPED (file not found)\n";

        return;
    }

    DB::beginTransaction();

    try {
        $result = $service->import($file, true);
        echo basename($file)."  =>  created={$result->createdRows}  updated={$result->updatedRows}  skipped={$result->skippedRows}  failed={$result->failedRows}  (of {$result->rowsProcessed})\n";

        $sample = Product::query()->latest('id')->limit(3)->get();

        foreach ($sample as $product) {
            echo "    name='{$product->name}'  slug={$product->slug}\n";
        }

        foreach (array_slice($result->failures, 0, 3) as $failure) {
            echo "    row {$failure['row']}: ".implode('; ', $failure['errors'])."\n";
        }
    } finally {
        DB::rollBack();
    }
}

$dir = __DIR__;
$fixed = $dir.'/combined data - Combined Data.fixed.csv';
$original = $dir.'/combined data - Combined Data.csv';

echo "-- original file, old behavior (no default category) --\n";
dryRun(new ProductImportService, $original);

echo "-- original file, new default_category='Trucks' --\n";
dryRun(new ProductImportService(['default_category' => 'Trucks']), $original);

echo "-- re-import with default_category='Trucks' (skip by Item) --\n";

DB::beginTransaction();

try {
    $service = new ProductImportService(['default_category' => 'Trucks']);
    $pass1 = $service->import($original, true);
    $pass2 = $service->import($original, true);

    echo "  pass 1 => created={$pass1->createdRows} updated={$pass1->updatedRows} skipped={$pass1->skippedRows} failed={$pass1->failedRows}\n";
    echo "  pass 2 => created={$pass2->createdRows} updated={$pass2->updatedRows} skipped={$pass2->skippedRows} failed={$pass2->failedRows}\n";
} finally {
    DB::rollBack();
}