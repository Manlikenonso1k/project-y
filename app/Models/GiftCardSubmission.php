<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftCardSubmission extends Model
{
    protected $fillable = [
        'order_id',
        'card_type',
        'card_currency',
        'card_amount',
        'card_value_per_image',
        'image_count',
        'status',
        'image_paths',
    ];

    protected function casts(): array
    {
        return [
            'card_amount' => 'decimal:2',
            'card_value_per_image' => 'decimal:2',
            'image_count' => 'integer',
            'image_paths' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
