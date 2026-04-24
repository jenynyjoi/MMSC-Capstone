<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLibraryBook extends Model
{
    protected $fillable = [
        'record_id', 'book_title', 'book_id',
        'date_borrowed', 'due_date', 'date_returned',
        'fines', 'remarks', 'librarian_name',
    ];

    protected $casts = [
        'date_borrowed'  => 'date',
        'due_date'       => 'date',
        'date_returned'  => 'date',
        'fines'          => 'decimal:2',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(StudentLibraryRecord::class, 'record_id');
    }

    public function getStatusAttribute(): string
    {
        if ($this->date_returned) return 'returned';
        if ($this->due_date < now()->toDateString()) return 'overdue';
        return 'borrowed';
    }
}
