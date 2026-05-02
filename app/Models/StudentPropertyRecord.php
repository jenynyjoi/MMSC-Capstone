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
     * Create a property record for a student on enrollment.
     * All default items are pre-assigned (issued = true).
     */
    public static function ensureForStudent(int $studentId, string $schoolYear): self
    {
        $record = self::firstOrCreate(
            ['student_id' => $studentId, 'school_year' => $schoolYear],
            ['status' => 'pending']
        );

        if ($record->wasRecentlyCreated) {
            foreach (self::defaultItems() as $itemName) {
                $record->items()->create([
                    'item_name' => $itemName,
                    'issued'    => true,
                    'returned'  => false, // repurposed: true = lost
                    'damaged'   => false,
                ]);
            }
        }

        return $record;
    }

    /**
     * Recompute status from item conditions.
     * lost (returned=true) or damaged on any item → overdue.
     * Otherwise stays pending until admin manually marks cleared.
     */
    public function recomputeStatus(): void
    {
        $items = $this->items;

        if ($items->where('returned', true)->isNotEmpty() || $items->where('damaged', true)->isNotEmpty()) {
            $this->status = 'overdue';
        } elseif ($this->status === 'cleared') {
            // Keep cleared if admin already approved
        } else {
            $this->status = 'pending';
        }

        $this->save();

        $map = ['pending' => 'pending', 'cleared' => 'cleared', 'overdue' => 'overdue'];
        Student::where('id', $this->student_id)
            ->update(['property_clearance' => $map[$this->status] ?? 'pending']);
    }
}
