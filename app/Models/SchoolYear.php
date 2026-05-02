<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'effective_date',
        'class_days',
        'status',
        'description',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'effective_date' => 'date',
        'class_days'     => 'array',
    ];

    /** Return the name of the currently active school year, with a fallback */
    public static function activeName(): string
    {
        try {
            return static::where('status', 'active')->value('name') ?? '2026-2027';
        } catch (\Throwable $e) {
            return '2025-2026';
        }
    }

    /** Human-readable label for the configured class days */
    public function classDaysLabel(): string
    {
        $map = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $days = collect($this->class_days ?? [])->sort()->map(fn($d) => $map[$d] ?? $d);
        return $days->implode(', ');
    }

    /** Tailwind badge classes for status */
    public function statusBadge(): string
    {
        return match ($this->status) {
            'active'   => 'bg-green-100 text-green-700',
            'upcoming' => 'bg-blue-100 text-blue-700',
            'ended'    => 'bg-slate-100 text-slate-500',
            default    => 'bg-slate-100 text-slate-500',
        };
    }
}
