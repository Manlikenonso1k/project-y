<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_adds_all_import_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'item',
            'engine',
            'transmission',
            'gvw',
            'store',
            'ecm_miles',
            'youtube_url',
            'extra_description',
            'image_url',
        ]));
    }

    public function test_csv_import_creates_products_with_new_fields(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Quantity', 'Category', 'Engine', 'Transmission', 'GVW', 'Store', 'Ecm Miles', 'YouTube URL', 'Extra Description', 'Image URL'],
            ['2020 Ford F-150', '45,000.00', '3', 'Trucks', '5.0L V8', 'Automatic', '7000 lbs', 'North', '125000', 'https://youtube.com/watch?v=abc', 'Extra details about the truck.', 'https://img.example.com/f150.jpg'],
            ['2019 Ram 1500', '$38000', '1', 'Trucks', '5.7L HEMI', 'Automatic', '6900 lbs', 'South', '98000', '', '', ''],
        ]);

        $result = app(ProductImportService::class)->import($path);

        $this->assertSame(2, $result->createdRows);
        $this->assertSame(0, $result->updatedRows);
        $this->assertSame(0, $result->failedRows);

        $this->assertDatabaseHas('categories', ['name' => 'Trucks']);

        $f150 = Product::where('slug', '2020-ford-f-150')->first();
        $this->assertNotNull($f150);
        $this->assertSame('5.0L V8', $f150->engine);
        $this->assertSame('Automatic', $f150->transmission);
        $this->assertSame('7000 lbs', $f150->gvw);
        $this->assertSame('North', $f150->store);
        $this->assertSame('125000', $f150->ecm_miles);
        $this->assertSame('https://youtube.com/watch?v=abc', $f150->youtube_url);
        $this->assertSame('https://img.example.com/f150.jpg', $f150->image_url);
        $this->assertSame('https://img.example.com/f150.jpg', $f150->primary_image_url);

        $ram = Product::where('slug', '2019-ram-1500')->first();
        $this->assertNotNull($ram);
        $this->assertSame('Automatic', $ram->transmission);
        $this->assertSame(38000.0, (float) $ram->price);
        $this->assertSame(1, $ram->quantity);
    }

    public function test_csv_import_updates_existing_product_by_name(): void
    {
        $category = Category::create(['name' => 'Trucks', 'slug' => 'trucks']);
        Product::create([
            'name' => '2020 Ford F-150',
            'slug' => '2020-ford-f-150',
            'description' => 'Old description',
            'price' => 40000,
            'quantity' => 0,
            'category_id' => $category->id,
        ]);

        $path = $this->writeCsv([
            ['Name', 'Price', 'Quantity', 'Category', 'Engine'],
            ['2020 Ford F-150', '45500', '5', 'Trucks', '5.0L V8'],
        ]);

        $result = app(ProductImportService::class)->import($path);

        $this->assertSame(0, $result->createdRows);
        $this->assertSame(1, $result->updatedRows);
        $this->assertSame(1, Product::count());

        $f150 = Product::first();
        $this->assertSame('2020-ford-f-150', $f150->slug);
        $this->assertSame(45500.0, (float) $f150->price);
        $this->assertSame(5, $f150->quantity);
        $this->assertSame('5.0L V8', $f150->engine);
    }

    public function test_csv_import_reports_failed_rows(): void
    {
        Category::create(['name' => 'Trucks', 'slug' => 'trucks']);

        $path = $this->writeCsv([
            ['Name', 'Price', 'Category'],
            ['Valid Product', '10', 'Trucks'],
            ['Missing Price', '', 'Trucks'],
            ['Missing Category', '20', ''],
            ['', '30', 'Trucks'],
        ]);

        $result = app(ProductImportService::class, [
            'options' => ['create_missing_categories' => false],
        ])->import($path);

        $this->assertSame(1, $result->createdRows);
        $this->assertSame(3, $result->failedRows);
        $this->assertCount(3, $result->failures);

        $errors = collect($result->failures)
            ->map(fn (array $failure): string => implode('; ', $failure['errors']))
            ->implode(' | ');

        $this->assertStringContainsString('price is required', $errors);
        $this->assertStringContainsString('category is required', $errors);
        $this->assertStringContainsString('name is required', $errors);
    }

    public function test_xlsx_import_creates_products(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'products_').'.xlsx';

        $writer = new XlsxWriter;
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues(['Name', 'Price', 'Category', 'Engine', 'Store', 'Ecm Miles']));
        $writer->addRow(Row::fromValues(['2020 Silverado', 42000, 'Trucks', '6.2L V8', 'East', 150000]));
        $writer->addRow(Row::fromValues(['2021 Toyota Tundra', 48000, 'Trucks', '5.7L V8', 'East', 90000]));
        $writer->close();

        $result = app(ProductImportService::class)->import($path);

        $this->assertSame(2, $result->createdRows);
        $this->assertSame(0, $result->failedRows);

        $tundra = Product::where('slug', '2021-toyota-tundra')->first();
        $this->assertNotNull($tundra);
        $this->assertSame('5.7L V8', $tundra->engine);
        $this->assertSame('East', $tundra->store);
        $this->assertSame('90000', $tundra->ecm_miles);
        $this->assertSame(48000.0, (float) $tundra->price);
    }

    public function test_import_without_header_row_uses_positional_columns(): void
    {
        // Positional order: name, description, price, original_price, quantity, category_id, ...
        $path = $this->writeCsv([
            ['Chevy Silverado', 'A well optioned pickup.', '25000', '', '7', 'Trucks'],
        ]);

        $result = app(ProductImportService::class)->import($path, hasHeaderRow: false);

        $this->assertSame(1, $result->createdRows);
        $this->assertSame(0, $result->failedRows);

        $silverado = Product::where('slug', 'chevy-silverado')->first();
        $this->assertNotNull($silverado);
        $this->assertSame(25000.0, (float) $silverado->price);
        $this->assertSame(7, $silverado->quantity);
    }

    public function test_import_reports_unmapped_header_columns(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Category', 'Super Secret Field'],
            ['Tesla Cybertruck', '65000', 'Trucks', 'x'],
        ]);

        $result = app(ProductImportService::class)->import($path);

        $this->assertSame(1, $result->createdRows);
        $this->assertContains('super_secret_field', $result->unknownColumns);
        $this->assertNotContains('name', $result->unknownColumns);
    }

    public function test_import_uses_default_category_for_rows_without_one(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Category'],
            ['Truck A', '10000', ''],
            ['Truck B', '20000', ''],
        ]);

        $result = app(ProductImportService::class, [
            'options' => ['default_category' => 'Trucks'],
        ])->import($path);

        $this->assertSame(2, $result->createdRows);
        $this->assertSame(0, $result->failedRows);
        $this->assertDatabaseHas('categories', ['name' => 'Trucks']);
        $this->assertSame('Trucks', Product::where('slug', 'truck-a')->first()->category->name);
        $this->assertSame('Trucks', Product::where('slug', 'truck-b')->first()->category->name);
    }

    public function test_import_does_not_overwrite_name_with_item_column(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Category', 'Item'],
            ['2022 Kenworth T680', '29900', 'Trucks', 'Item:22KN023'],
        ]);

        $result = app(ProductImportService::class)->import($path);

        $this->assertSame(1, $result->createdRows);
        $this->assertSame(0, $result->failedRows);

        $product = Product::where('slug', '2022-kenworth-t680')->first();
        $this->assertNotNull($product);
        $this->assertSame('2022 Kenworth T680', $product->name);
    }

    public function test_import_skips_duplicate_items_on_reimport(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Category', 'Item'],
            ['2022 Kenworth T680', '29900', 'Trucks', 'Item:22KN023'],
            ['2016 Durastar', '74900', 'Trucks', 'Item:16SADUMP'],
        ]);

        $first = app(ProductImportService::class)->import($path);

        $this->assertSame(2, $first->createdRows);
        $this->assertSame(0, $first->skippedRows);
        $this->assertSame(0, $first->failedRows);
        $this->assertSame('Item:22KN023', Product::where('slug', '2022-kenworth-t680')->first()->item);

        // Re-importing the same file must skip both rows and create nothing new.
        $second = app(ProductImportService::class)->import($path);

        $this->assertSame(0, $second->createdRows);
        $this->assertSame(2, $second->skippedRows);
        $this->assertSame(0, $second->failedRows);
        $this->assertSame(2, Product::count());
    }

    public function test_import_updates_duplicate_item_when_skip_disabled(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Category', 'Item'],
            ['2022 Kenworth T680', '29900', 'Trucks', 'Item:22KN023'],
        ]);

        app(ProductImportService::class)->import($path);

        $updated = app(ProductImportService::class, [
            'options' => ['skip_existing_by_item' => false],
        ])->import($path);

        $this->assertSame(0, $updated->createdRows);
        $this->assertSame(1, $updated->updatedRows);
        $this->assertSame(0, $updated->skippedRows);
        $this->assertSame(1, Product::count());
    }

    public function test_import_skips_duplicate_items_within_the_same_file(): void
    {
        $path = $this->writeCsv([
            ['Name', 'Price', 'Category', 'Item'],
            ['2022 Kenworth T680', '29900', 'Trucks', 'Item:22KN023'],
            ['2022 Kenworth T680', '29900', 'Trucks', 'Item:22KN023'],
        ]);

        $result = app(ProductImportService::class)->import($path);

        $this->assertSame(1, $result->createdRows);
        $this->assertSame(1, $result->skippedRows);
        $this->assertSame(0, $result->failedRows);
        $this->assertSame(1, Product::count());
    }

    public function test_import_rejects_unsupported_file_extension(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'products_').'.pdf';
        file_put_contents($path, '%PDF-1.4');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported file extension');

        app(ProductImportService::class)->import($path);
    }

    /**
     * @param  array<int, array<int, string>>  $rows
     */
    private function writeCsv(array $rows): string
    {
        $path = tempnam(sys_get_temp_dir(), 'products_').'.csv';

        $handle = fopen($path, 'w');

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return $path;
    }
}
