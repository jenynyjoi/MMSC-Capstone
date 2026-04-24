<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentLibraryRecord extends Model
{
    protected $fillable = [
        'student_id', 'school_year', 'status',
        'cleared_by', 'cleared_at', 'remarks',
    ];

    protected $casts = [
        'cleared_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(StudentLibraryBook::class, 'record_id')->orderByDesc('date_borrowed');
    }

    public static function ensureForStudent(int $studentId, string $schoolYear): self
    {
        return self::firstOrCreate(
            ['student_id' => $studentId, 'school_year' => $schoolYear],
            ['status' => 'no_record']
        );
    }

    /**
     * Recompute status from books and sync to students.library_clearance.
     */
    public function recomputeStatus(): void
    {
        $today = now()->toDateString();
        $books = $this->books()->get();

        if ($books->isEmpty()) {
            $this->status = 'no_record';
        } else {
            $hasOverdue = $books->contains(
                fn($b) => is_null($b->date_returned) && $b->due_date < $today
            );
            $this->status = $hasOverdue ? 'overdue' : 'pending';
        }

        $this->save();
    }

    public function getBorrowedCountAttribute(): int
    {
        return $this->books->whereNull('date_returned')->count();
    }

    public function getOverdueCountAttribute(): int
    {
        return $this->books->filter(
            fn($b) => is_null($b->date_returned) && $b->due_date < now()->toDateString()
        )->count();
    }

    public function getTotalFinesAttribute(): float
    {
        return (float) $this->books->sum('fines');
    }

    public function getLastBorrowedDateAttribute(): ?string
    {
        return $this->books->max('date_borrowed');
    }
}
