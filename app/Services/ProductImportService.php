<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use InvalidArgumentException;
use League\Csv\Reader as CsvReader;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;

/**
 * Imports products from CSV (via league/csv) or Excel .xlsx files
 * (via openspout). Header names are matched flexibly (case + separators
 * are ignored) so files exported from other systems import without
 * manual column mapping.
 */
class ProductImportService
{
    /**
     * Canonical product field => list of acceptable header names.
     *
     * @var array<string, list<string>>
     */
    protected const COLUMN_ALIASES = [
        'name' => ['name', 'product', 'product_name', 'title'],
        'slug' => ['slug', 'url_key'],
        'description' => ['description', 'desc', 'short_description', 'details'],
        'price' => ['price', 'unit_price', 'selling_price', 'sale_price'],
        'original_price' => ['original_price', 'compare_price', 'compare_at_price', 'old_price', 'list_price', 'regular_price'],
        'quantity' => ['quantity', 'qty', 'stock', 'stock_qty', 'inventory'],
        'category_id' => ['category', 'category_name', 'category_id', 'type'],
        'image' => ['image', 'image_path', 'local_image'],
        'image_url' => ['image_url', 'external_image_url', 'external_image', 'main_image_url', 'photo_url', 'picture_url'],
        'images' => ['images', 'gallery', 'gallery_images', 'extra_images', 'photos'],
        'is_featured' => ['is_featured', 'featured'],
        'is_active' => ['is_active', 'active', 'enabled', 'visible'],
        'is_variable' => ['is_variable', 'variable', 'variable_product'],
        'engine' => ['engine', 'engine_size', 'motor'],
        'transmission' => ['transmission', 'gear_box', 'gearbox', 'gearbox_type'],
        'gvw' => ['gvw', 'gwv', 'gross_vehicle_weight', 'gvwr', 'gvw_rating'],
        'store' => ['store', 'warehouse', 'location', 'store_location'],
        'ecm_miles' => ['ecm_miles', 'ecm_mileage', 'ecm_miless', 'ecm'],
        'youtube_url' => ['youtube_url', 'youtube', 'video_url', 'youtube_link'],
        'extra_description' => ['extra_description', 'additional_description', 'extra_details', 'notes', 'more_info'],
    ];

    /**
     * Assumed field order when importing a file without a header row.
     *
     * @var array<int, string>
     */
    protected const POSITIONAL_COLUMNS = [
        'name',
        'description',
        'price',
        'original_price',
        'quantity',
        'category_id',
        'image',
        'images',
        'is_featured',
        'is_active',
        'is_variable',
        'engine',
        'transmission',
        'gvw',
        'store',
        'ecm_miles',
        'youtube_url',
        'extra_description',
        'image_url',
    ];

    /**
     * @var array<string, string> normalized header => canonical field
     */
    protected array $aliasLookup = [];

    /**
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * Header names from the current file that did not map to a product column.
     *
     * @var array<int, string>
     */
    protected array $unknownColumns = [];

    /**
     * @param  array{create_missing_categories?: bool, update_existing?: bool, default_category?: string|null}  $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'create_missing_categories' => true,
            'update_existing' => true,
            'default_category' => null,
        ], $options);

        foreach (static::COLUMN_ALIASES as $field => $aliases) {
            foreach ($aliases as $alias) {
                $this->aliasLookup[$this->normalizeHeader($alias)] = $field;
            }
        }
    }

    /**
     * Import a CSV or Excel file into the products table.
     *
     * @throws InvalidArgumentException
     */
    public function import(string $path, bool $hasHeaderRow = true): ImportResult
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new InvalidArgumentException("Import file was not found or is not readable: {$path}");
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $rows = match ($extension) {
            'csv', 'txt' => $this->rowsFromCsv($path),
            'xlsx', 'xls' => $this->rowsFromXlsx($path),
            default => throw new InvalidArgumentException(
                "Unsupported file extension [{$extension}]. Please upload a .csv, .xlsx or .xls file."
            ),
        };

        if (empty($rows)) {
            throw new InvalidArgumentException('The uploaded file does not contain any data rows.');
        }

        $result = new ImportResult;

        $this->unknownColumns = [];

        if ($hasHeaderRow) {
            $header = array_shift($rows);

            foreach ($rows as $index => $row) {
                $mapped = $this->mapAssocRow(array_values($header), array_values($row));
                $this->processRow($mapped, $index + 2, $result);
            }
        } else {
            foreach ($rows as $index => $row) {
                $mapped = $this->mapPositionalRow(array_values($row));
                $this->processRow($mapped, $index + 1, $result);
            }
        }

        $result->unknownColumns = $this->unknownColumns;

        return $result;
    }

    /**
     * @return list<array<int, int|float|string|null>>
     */
    protected function rowsFromCsv(string $path): array
    {
        $reader = CsvReader::createFromPath($path, 'r');
        $reader->setHeaderOffset(null);

        $rows = [];

        foreach ($reader->getRecords() as $record) {
            $rows[] = array_map(
                fn ($value) => is_string($value) ? trim($value) : $value,
                array_values($record),
            );
        }

        return $rows;
    }

    /**
     * @return list<array<int, int|float|string|null>>
     */
    protected function rowsFromXlsx(string $path): array
    {
        $reader = new XlsxReader;
        $reader->open($path);

        $rows = [];

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    if ($row->isEmpty()) {
                        continue;
                    }

                    $rows[] = array_map(
                        fn ($value) => is_string($value) ? trim($value) : $value,
                        $row->toArray(),
                    );
                }

                // Only the first worksheet is imported.
                break;
            }
        } finally {
            $reader->close();
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapAssocRow(array $header, array $row): array
    {
        $mapped = [];

        foreach ($header as $index => $headerName) {
            $field = $this->resolveField((string) $headerName);

            if ($field === null) {
                $this->recordUnknownColumn((string) $headerName);

                continue;
            }

            $mapped[$field] = $row[$index] ?? null;
        }

        return $mapped;
    }

    protected function recordUnknownColumn(string $headerName): void
    {
        $normalized = $this->normalizeHeader($headerName);

        if ($normalized === '' || in_array($normalized, $this->unknownColumns, true)) {
            return;
        }

        $this->unknownColumns[] = $normalized;
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapPositionalRow(array $row): array
    {
        $mapped = [];

        foreach (static::POSITIONAL_COLUMNS as $index => $field) {
            $mapped[$field] = $row[$index] ?? null;
        }

        return $mapped;
    }

    protected function resolveField(string $headerName): ?string
    {
        $normalized = $this->normalizeHeader($headerName);

        if ($normalized === '') {
            return null;
        }

        return $this->aliasLookup[$normalized] ?? null;
    }

    protected function normalizeHeader(string $header): string
    {
        $header = trim($header);

        // Strip UTF-8 BOM that Excel sometimes prepends to the first header cell.
        $header = str_replace(["\xEF\xBB\xBF", "\u{FEFF}"], '', $header);

        $header = mb_strtolower($header, 'UTF-8');
        $header = preg_replace('/[^a-z0-9]+/', '_', $header) ?? '';

        return trim($header, '_');
    }

    /**
     * @param  array<string, mixed>  $mapped
     */
    protected function processRow(array $mapped, int $rowNumber, ImportResult $result): void
    {
        $result->rowsProcessed++;

        $errors = [];

        $name = trim((string) ($mapped['name'] ?? ''));

        if ($name === '') {
            $errors[] = 'name is required';
        }

        $isVariable = $this->resolveBoolean($mapped['is_variable'] ?? false);
        $price = $this->resolveNumber($mapped['price'] ?? null);

        if (! $isVariable && $price === null) {
            $errors[] = 'price is required for non-variable products';
        }

        $categoryValue = $mapped['category_id'] ?? null;

        if (blank($categoryValue) && filled($this->options['default_category'] ?? null)) {
            $categoryValue = $this->options['default_category'];
        }

        $categoryId = $this->resolveCategoryId($categoryValue, $errors);

        if ($errors !== []) {
            $result->failedRows++;
            $result->failures[] = ['row' => $rowNumber, 'errors' => $errors];

            return;
        }

        $product = null;

        if ($this->options['update_existing']) {
            $product ??= Product::query()->where('name', $name)->first();

            $slugHint = trim((string) ($mapped['slug'] ?? ''));
            if (filled($slugHint)) {
                $product ??= Product::query()->where('slug', Str::slug($slugHint))->first();
            }
        }

        $isNew = $product === null;
        $product ??= new Product;

        $slug = $this->resolveSlug($mapped, $product, $isNew);

        if ($errors !== []) {
            $result->failedRows++;
            $result->failures[] = ['row' => $rowNumber, 'errors' => $errors];

            return;
        }

        $attributes = [
            'name' => $name,
            'slug' => $slug,
            'description' => trim((string) ($mapped['description'] ?? $product->description ?? '')),
            'price' => $isVariable ? 0 : (float) $price,
            'original_price' => $this->resolveNumber($mapped['original_price'] ?? null),
            'quantity' => (int) ($this->resolveNumber($mapped['quantity'] ?? null) ?? 0),
            'category_id' => $categoryId,
            'is_featured' => $this->resolveBoolean($mapped['is_featured'] ?? $product->is_featured ?? false),
            'is_active' => $this->resolveBoolean($mapped['is_active'] ?? $product->is_active ?? true),
            'is_variable' => $isVariable,
            'engine' => $this->nullableString($mapped['engine'] ?? null, $product->engine),
            'transmission' => $this->nullableString($mapped['transmission'] ?? null, $product->transmission),
            'gvw' => $this->nullableString($mapped['gvw'] ?? null, $product->gvw),
            'store' => $this->nullableString($mapped['store'] ?? null, $product->store),
            'ecm_miles' => $this->nullableString($mapped['ecm_miles'] ?? null, $product->ecm_miles),
            'youtube_url' => $this->nullableString($mapped['youtube_url'] ?? null, $product->youtube_url),
            'extra_description' => $this->nullableString($mapped['extra_description'] ?? null, $product->extra_description),
            'image_url' => $this->nullableString($mapped['image_url'] ?? null, $product->image_url),
        ];

        $image = trim((string) ($mapped['image'] ?? $product->image ?? ''));
        $attributes['image'] = filled($image) ? $image : null;

        if (array_key_exists('images', $mapped)) {
            $attributes['images'] = $this->resolveImages($mapped['images']);
        }

        $product->fill($attributes);
        $product->save();

        if ($isNew) {
            $result->createdRows++;
        } else {
            $result->updatedRows++;
        }
    }

    /**
     * @param  array<string, mixed>  $mapped
     */
    protected function resolveSlug(array $mapped, Product $product, bool $isNew): string
    {
        $hint = trim((string) ($mapped['slug'] ?? ''));

        $name = trim((string) $mapped['name']);

        if ($isNew) {
            if (blank($hint)) {
                return $this->uniqueProductSlug($name, null);
            }

            $candidate = Str::slug($hint) ?: $this->uniqueProductSlug($name, null);

            $existing = Product::query()->where('slug', $candidate)->first();

            return $existing ? $existing->slug : $candidate;
        }

        if (blank($hint)) {
            return $product->slug;
        }

        $candidate = Str::slug($hint) ?: $product->slug;

        if (
            $candidate !== $product->slug
            && Product::query()->where('slug', $candidate)->whereKeyNot($product->getKey())->exists()
        ) {
            return $product->slug;
        }

        return $candidate;
    }

    protected function uniqueProductSlug(string $name, ?int $ignoreId): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (Product::query()->where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * @param  array<int, string>  $errors
     */
    protected function resolveCategoryId(mixed $value, array &$errors): ?int
    {
        if (blank($value)) {
            $errors[] = 'category is required';

            return null;
        }

        $category = null;

        if (is_numeric($value)) {
            $category = Category::find((int) $value);
        }

        if (! $category) {
            $category = Category::query()->where('name', (string) $value)->first();
        }

        if (! $category) {
            $category = Category::query()->where('slug', Str::slug((string) $value))->first();
        }

        if (! $category && $this->options['create_missing_categories']) {
            $category = Category::create([
                'name' => (string) $value,
                'slug' => $this->uniqueCategorySlug((string) $value),
            ]);
        }

        if (! $category) {
            $errors[] = "Category [{$value}] could not be found";

            return null;
        }

        return (int) $category->id;
    }

    protected function uniqueCategorySlug(string $name): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $suffix = 2;

        while (Category::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    protected function nullableString(mixed $value, mixed $fallback = null): ?string
    {
        $value = $value ?? $fallback;

        if (blank($value)) {
            return null;
        }

        return trim((string) $value);
    }

    protected function resolveBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (bool) $value;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'y', 'on'], true);
    }

    protected function resolveNumber(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        // Strip currency symbols, thousands separators and whitespace.
        $clean = preg_replace('/[^0-9eE+\-.]+/', '', (string) $value) ?? '';

        return is_numeric($clean) ? (float) $clean : null;
    }

    /**
     * @return array<int, string>|null
     */
    protected function resolveImages(mixed $value): ?array
    {
        if (blank($value)) {
            return null;
        }

        $parts = preg_split('/[,;|]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY);
        $parts = array_values(array_filter(array_map(
            fn (string $part): string => trim($part),
            $parts ?: [],
        ), filled(...)));

        return $parts ?: null;
    }
}
