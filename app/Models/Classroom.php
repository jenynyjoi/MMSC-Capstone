<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Classroom extends Model
{
    protected $table = 'classrooms';

    protected $fillable = [
        'room_number', 'capacity', 'room_type',
        'grade_level_type', 'homeroom_adviser',
        'availability_status', 'room_status', 'notes',
    ];

    /**
     * Recompute availability_status based on whether this room is
     * referenced in subject_schedules for the current/active school year.
     * Rooms manually set to 'under_repair' (via room_status=under_maintenance)
     * are not overridden.
     */
    public function recomputeAvailability(): void
    {
        if ($this->room_status === 'under_maintenance') {
            $this->update(['availability_status' => 'under_repair']);
            return;
        }

        $isOccupied = DB::table('subject_schedule')
            ->where('room', $this->room_number)
            ->exists();

        $this->update([
            'availability_status' => $isOccupied ? 'occupied' : 'available',
        ]);
    }

    /**
     * Recompute availability for all classrooms at once.
     */
    public static function recomputeAll(): void
    {
        $occupiedRooms = DB::table('subject_schedule')
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->pluck('room')
            ->map(fn($r) => strtoupper(trim($r)))
            ->unique()
            ->all();

        static::all()->each(function (Classroom $c) use ($occupiedRooms) {
            if ($c->room_status === 'under_maintenance') {
                $c->update(['availability_status' => 'under_repair']);
                return;
            }
            $occupied = in_array(strtoupper(trim($c->room_number)), $occupiedRooms);
            $c->update(['availability_status' => $occupied ? 'occupied' : 'available']);
        });
    }
}
