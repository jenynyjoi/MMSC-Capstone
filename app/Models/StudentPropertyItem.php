<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPropertyItem extends Model
{
    protected $fillable = [
        'record_id', 'item_name',
        'issued', 'returned', 'damaged',
        'replacement_fee', 'issued_at', 'returned_at',
    ];

    protected $casts = [
        'issued'          => 'boolean',
        'returned'        => 'boolean',
        'damaged'         => 'boolean',
        'replacement_fee' => 'decimal:2',
        'issued_at'       => 'datetime',
        'returned_at'     => 'datetime',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(StudentPropertyRecord::class, 'record_id');
    }
}
