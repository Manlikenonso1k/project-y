<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /**
     * Columns covered by the products_search_fulltext FULLTEXT index (MySQL/MariaDB)
     * and used as fallback LIKE searches on every other driver.
     *
     * @var array<int, string>
     */
    protected const SEARCHABLE_FIELDS = [
        'name',
        'engine',
        'transmission',
        'gvw',
        'store',
        'ecm_miles',
    ];

    protected $fillable = [
        'name',
        'item_number',
        'slug',
        'description',
        'price',
        'original_price',
        'quantity',
        'image',
        'images',
        'category_id',
        'is_featured',
        'is_active',
        'views_count',
        'is_variable',
        'engine',
        'transmission',
        'gvw',
        'store',
        'ecm_miles',
        'youtube_url',
        'extra_description',
        'image_url',
        'url',
        'year',
        'manufacturer',
        'subcategory',
        'mileage',
        'horsepower',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_variable' => 'boolean',
        'views_count' => 'integer',
        'images' => 'array',
        'year' => 'integer',
        'mileage' => 'integer',
        'horsepower' => 'integer',
    ];

    public function getGalleryImagePathsAttribute(): array
    {
        $paths = array_values(array_filter((array) ($this->images ?? [])));

        if (empty($paths) && filled($this->image)) {
            $paths[] = $this->image;
        }

        return $paths;
    }

    public function getPrimaryImagePathAttribute(): ?string
    {
        return $this->gallery_image_paths[0] ?? null;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $resolved = $this->resolveImageUrl($this->primary_image_path)
            ?? (filled($this->image_url) ? $this->image_url : null);

        // Fallback to unavailable image when no product image is set
        if (blank($resolved)) {
            return asset('images/unavailable.jpg');
        }

        return $resolved;
    }

    public function getGalleryImageUrlsAttribute(): array
    {
        return array_values(array_filter(array_map(
            fn (?string $path): ?string => $this->resolveImageUrl($path),
            $this->gallery_image_paths,
        )));
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'data:')) {
            return $path;
        }

        if (str_starts_with($path, 'img/')) {
            return asset($path);
        }

        if (str_starts_with($path, '/storage/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Full-text search across the SEARCHABLE_FIELDS columns.
     *
     * On MySQL/MariaDB this uses the products_search_fulltext FULLTEXT index
     * with a boolean-mode query. Every other driver (SQLite, PostgreSQL, ...)
     * falls back to the LIKE-based scopeSearch() so the feature keeps working
     * everywhere, including the local SQLite environment.
     */
    public function scopeSearchable(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        $driver = $query->getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $columns = implode(', ', array_map(
                fn (string $column): string => $query->getConnection()->getQueryGrammar()->wrap($column),
                static::SEARCHABLE_FIELDS,
            ));

            // Sanitize the term and run it as a boolean-mode phrase with a trailing
            // wildcard so "tro" also matches "transmission", etc.
            $escaped = str_replace('"', '', $term);

            return $query->whereRaw(
                "MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)",
                [sprintf('"%s"*', $escaped)],
            );
        }

        return $query->search($term);
    }

    /**
     * Portable "contains" search across the searchable columns.
     * Used as a fallback when the database driver has no FULLTEXT index.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term): void {
            foreach (static::SEARCHABLE_FIELDS as $field) {
                $query->orWhere($field, 'like', '%'.$term.'%');
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
