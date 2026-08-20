<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockonomicsCallback extends Model
{
    protected $fillable = [
        'order_id',
        'payload_hash',
        'address',
        'txid',
        'value_satoshi',
        'status_code',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'value_satoshi' => 'integer',
            'status_code' => 'integer',
            'payload' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
