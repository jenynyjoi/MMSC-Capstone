<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentPropertyRecord extends Model
{
    protected $fillable = [
        'student_id', 'school_year', 'status',
        'issued_at', 'issued_by', 'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StudentPropertyItem::class, 'record_id');
    }

    public function issuedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /** Default items every student should have tracked */
    public static function defaultItems(): array
    {
        return ['Student ID Card', 'Library Card', 'Locker Key', 'Textbooks'];
    }

    /**
     * Create a blank property record for a student (if one doesn't already exist).
     * Called when a student is approved/enrolled.
     */
    public static function ensureForStudent(int $studentId, string $schoolYear): self
    {
        $record = self::firstOrCreate(
            ['student_id' => $studentId, 'school_year' => $schoolYear],
            ['status' => 'for_issuance']
        );

        if ($record->wasRecentlyCreated) {
            foreach (self::defaultItems() as $itemName) {
                $record->items()->create(['item_name' => $itemName]);
            }
        }

        return $record;
    }

    /** Recompute status from items and save */
    public function recomputeStatus(): void
    {
        $items = $this->items;
        $issued = $items->where('issued', true);

        if ($issued->isEmpty()) {
            $this->status = 'for_issuance';
        } elseif ($issued->where('returned', false)->isEmpty()) {
            $this->status = 'cleared';
        } else {
            $this->status = 'issued';
        }

        $this->save();

        // Keep students.property_clearance in sync
        $map = [
            'for_issuance' => 'pending',
            'issued'       => 'pending',
            'cleared'      => 'cleared',
            'overdue'      => 'overdue',
        ];
        Student::where('id', $this->student_id)
            ->update(['property_clearance' => $map[$this->status] ?? 'pending']);
    }
}
