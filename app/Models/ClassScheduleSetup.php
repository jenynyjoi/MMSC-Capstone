<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassScheduleSetup extends Model
{
    protected $table = 'class_schedule_setups';

    protected $fillable = [
        'level_type', 'grade_level', 'time_start', 'time_end',
        'slot_duration', 'breaks', 'is_active', 'created_by',
    ];

    protected $casts = [
        'breaks'    => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the best-matching setup for a section.
     * Grade-level-specific setup beats program-level setup.
     */
    public static function forSection(Section $section): ?self
    {
        return self::where('is_active', true)
            ->where(function ($q) use ($section) {
                $q->where('grade_level', $section->grade_level)
                  ->orWhere(function ($q2) use ($section) {
                      $q2->whereNull('grade_level')
                         ->where('level_type', $section->program_level ?? $section->applied_level);
                  });
            })
            ->orderByRaw('grade_level IS NULL ASC') // grade-specific first
            ->first();
    }

    /**
     * Generate time slots for this setup.
     * Returns array of ['start'=>'07:00','end'=>'08:00','type'=>'class'|'break','label'=>string]
     */
    public function generateSlots(): array
    {
        $slots   = [];
        $breaks  = $this->breaks ?? [];
        $current = strtotime($this->time_start);
        $end     = strtotime($this->time_end);
        $dur     = $this->slot_duration * 60; // seconds

        while ($current < $end) {
            $slotEnd   = $current + $dur;
            $slotStart = date('H:i', $current);

            // Check if current time falls inside a break
            $inBreak = null;
            foreach ($breaks as $brk) {
                $bStart = strtotime($brk['start']);
                $bEnd   = strtotime($brk['end']);
                if ($current >= $bStart && $current < $bEnd) {
                    $inBreak = $brk;
                    $slotEnd = $bEnd; // end of break becomes next start
                    break;
                }
            }

            $slots[] = [
                'start' => $slotStart,
                'end'   => date('H:i', min($slotEnd, $end)),
                'type'  => $inBreak ? 'break' : 'class',
                'label' => $inBreak['label'] ?? 'Break',
            ];

            $current = min($slotEnd, $end);
        }

        return $slots;
    }
}
