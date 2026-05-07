<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_variable' => 'boolean',
        'views_count' => 'integer',
        'images' => 'array',
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
        return $this->resolveImageUrl($this->primary_image_path);
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
