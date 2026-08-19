<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * Builds dynamic range buckets for numeric product fields (mileage, horsepower).
 *
 * Buckets are computed once from the unfiltered product set for a given scope
 * (e.g. a main category), then facet counts are recalculated against active
 * filters using those fixed bucket boundaries.
 */
class FacetBucketService
{
    /**
     * Nice rounding values used to snap bucket widths to human-friendly numbers.
     *
     * @var array<int, int>
     */
    private const NICE_NUMBERS = [
        10, 25, 50, 100, 250, 500, 1_000, 2_500, 5_000,
        10_000, 25_000, 50_000, 100_000, 250_000, 500_000,
    ];

    /**
     * Target number of buckets (we aim for 6-10).
     */
    private const TARGET_BUCKETS = 8;

    /**
     * Build bucket definitions for a numeric field.
     *
     * Returns an ordered array of buckets, each with:
     *   - 'key'   => string like "100000-199999" or "na"
     *   - 'label' => string like "100,000–199,999" or "N/A"
     *   - 'min'   => int|null (null for N/A bucket)
     *   - 'max'   => int|null (null for N/A bucket)
     *
     * @param  string       $field    The database column name (e.g. 'mileage', 'horsepower')
     * @param  Builder|null $baseQuery  Base query scoped to main category + is_active (no facet filters applied)
     * @return array<int, array{key: string, label: string, min: int|null, max: int|null}>
     */
    public function buildBuckets(string $field, ?Builder $baseQuery = null): array
    {
        $query = $baseQuery ? (clone $baseQuery) : Product::query()->where('is_active', true);

        $stats = $query
            ->whereNotNull($field)
            ->selectRaw("MIN({$field}) as field_min, MAX({$field}) as field_max")
            ->first();

        $min = $stats?->field_min;
        $max = $stats?->field_max;

        // If there are no values at all, return only N/A bucket
        if ($min === null || $max === null) {
            return [['key' => 'na', 'label' => 'N/A', 'min' => null, 'max' => null]];
        }

        $min = (int) $min;
        $max = (int) $max;

        // If min == max, just one bucket + N/A
        if ($min === $max) {
            $buckets = [
                ['key' => 'na', 'label' => 'N/A', 'min' => null, 'max' => null],
                ['key' => "{$min}-{$max}", 'label' => number_format($min), 'min' => $min, 'max' => $max],
            ];

            return $buckets;
        }

        $range = $max - $min;
        $rawWidth = (int) ceil($range / self::TARGET_BUCKETS);
        $width = $this->snapToNiceNumber($rawWidth);

        // Round floor down to nearest multiple of width
        $bucketStart = (int) (floor($min / $width) * $width);
        $bucketEnd = (int) (ceil(($max + 1) / $width) * $width);

        $buckets = [
            ['key' => 'na', 'label' => 'N/A', 'min' => null, 'max' => null],
        ];

        for ($lo = $bucketStart; $lo < $bucketEnd; $lo += $width) {
            $hi = $lo + $width - 1;
            $key = "{$lo}-{$hi}";
            $label = number_format($lo) . '–' . number_format($hi);
            $buckets[] = ['key' => $key, 'label' => $label, 'min' => $lo, 'max' => $hi];
        }

        return $buckets;
    }

    /**
     * Count products in each bucket, respecting active filters (excluding the
     * filter for this facet's own field).
     *
     * @param  string  $field       The database column name
     * @param  array   $buckets     Bucket definitions from buildBuckets()
     * @param  Builder $filteredQuery  Query with all OTHER filters applied (not this facet's filter)
     * @return array<string, int>   Map of bucket key => count. Empty buckets are omitted.
     */
    public function countBuckets(string $field, array $buckets, Builder $filteredQuery): array
    {
        $query = clone $filteredQuery;

        // Build SQL CASE expressions for each bucket
        $selects = [];
        foreach ($buckets as $bucket) {
            $key = $bucket['key'];
            if ($key === 'na') {
                $selects[] = "SUM(CASE WHEN {$field} IS NULL THEN 1 ELSE 0 END) as bucket_na";
            } else {
                $safeKey = str_replace('-', '_', $key);
                $min = $bucket['min'];
                $max = $bucket['max'];
                $selects[] = "SUM(CASE WHEN {$field} BETWEEN {$min} AND {$max} THEN 1 ELSE 0 END) as bucket_{$safeKey}";
            }
        }

        $row = $query->selectRaw(implode(', ', $selects))->first();

        $counts = [];
        foreach ($buckets as $bucket) {
            $key = $bucket['key'];
            $col = $key === 'na' ? 'bucket_na' : 'bucket_' . str_replace('-', '_', $key);
            $count = (int) ($row?->{$col} ?? 0);
            if ($count > 0) {
                $counts[$key] = $count;
            }
        }

        return $counts;
    }

    /**
     * Apply a set of selected bucket filters to a query.
     *
     * @param  Builder       $query     The query to apply filters to
     * @param  string        $field     The database column name
     * @param  array<string> $selected  Array of bucket key strings (e.g. ['na', '100000-199999'])
     */
    public function applyBucketFilter(Builder $query, string $field, array $selected): void
    {
        if (empty($selected)) {
            return;
        }

        $query->where(function (Builder $q) use ($field, $selected): void {
            foreach ($selected as $bucketKey) {
                if ($bucketKey === 'na') {
                    $q->orWhereNull($field);
                } else {
                    $parts = explode('-', $bucketKey);
                    if (count($parts) === 2) {
                        $q->orWhereBetween($field, [(int) $parts[0], (int) $parts[1]]);
                    }
                }
            }
        });
    }

    /**
     * Snap a raw bucket width to the nearest "nice" number.
     */
    private function snapToNiceNumber(int $rawWidth): int
    {
        $best = $rawWidth;
        $bestDiff = PHP_INT_MAX;

        foreach (self::NICE_NUMBERS as $nice) {
            $diff = abs($nice - $rawWidth);
            if ($diff < $bestDiff) {
                $bestDiff = $diff;
                $best = $nice;
            }
        }

        // Ensure width is at least 1
        return max(1, $best);
    }
}
