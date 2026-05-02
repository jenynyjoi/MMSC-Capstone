<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Section;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        // Recompute availability for all rooms before rendering
        Classroom::recomputeAll();

        $query = Classroom::query();

        if ($request->filled('type'))         $query->where('room_type', $request->type);
        if ($request->filled('avail_status')) $query->where('availability_status', $request->avail_status);
        if ($request->filled('room_status'))  $query->where('room_status', $request->room_status);
        if ($request->filled('search'))       $query->where('room_number', 'like', '%' . $request->search . '%');

        $classrooms = $query->orderBy('room_number')->paginate(15)->withQueryString();

        $total       = Classroom::count();
        $available   = Classroom::where('availability_status', 'available')->count();
        $occupied    = Classroom::where('availability_status', 'occupied')->count();
        $underRepair = Classroom::where('availability_status', 'under_repair')->count();

        $activeSchoolYear = SchoolYear::activeName();
        $sectionsByRoom = Section::where('school_year', $activeSchoolYear)
            ->where('is_subject_section', false)
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->get()
            ->keyBy(fn($s) => strtoupper(trim($s->room)))
            ->map(fn($s) => [
                'id'                    => $s->id,
                'display_name'          => $s->display_name,
                'grade_level'           => $s->grade_level,
                'section_name'          => $s->section_name,
                'school_year'           => $s->school_year,
                'room'                  => $s->room,
                'homeroom_adviser_name' => $s->homeroom_adviser_name ?? 'TBA',
                'current_enrollment'    => $s->current_enrollment,
                'capacity'              => $s->capacity,
                'availability'          => $s->availability,
                'section_status'        => $s->section_status,
                'adviser_status'        => $s->adviser_status,
                'track'                 => $s->track,
                'strand'                => $s->strand,
            ]);

        return view('admin.classes.classrooms', compact(
            'classrooms', 'total', 'available', 'occupied', 'underRepair', 'sectionsByRoom'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number'      => 'required|string|max:50|unique:classrooms,room_number',
            'capacity'         => 'required|integer|min:1|max:500',
            'room_type'        => 'required|string|max:50',
            'grade_level_type' => 'nullable|string|max:50',
            'homeroom_adviser' => 'nullable|string|max:100',
            'room_status'      => 'required|in:active,inactive,under_maintenance',
            'notes'            => 'nullable|string|max:500',
        ]);

        $data['availability_status'] = $data['room_status'] === 'under_maintenance'
            ? 'under_repair' : 'available';

        $classroom = Classroom::create($data);
        $classroom->recomputeAvailability();

        return response()->json([
            'success'   => true,
            'message'   => "Room {$classroom->room_number} added.",
            'classroom' => $classroom->fresh(),
        ]);
    }

    public function update(Request $request, Classroom $classroom)
    {
        $data = $request->validate([
            'room_number'      => "required|string|max:50|unique:classrooms,room_number,{$classroom->id}",
            'capacity'         => 'required|integer|min:1|max:500',
            'room_type'        => 'required|string|max:50',
            'grade_level_type' => 'nullable|string|max:50',
            'homeroom_adviser' => 'nullable|string|max:100',
            'room_status'      => 'required|in:active,inactive,under_maintenance',
            'notes'            => 'nullable|string|max:500',
        ]);

        $classroom->update($data);
        $classroom->recomputeAvailability();

        return response()->json([
            'success'   => true,
            'message'   => "Room {$classroom->room_number} updated.",
            'classroom' => $classroom->fresh(),
        ]);
    }

    public function destroy(Classroom $classroom)
    {
        $num = $classroom->room_number;
        $classroom->delete();

        return response()->json(['success' => true, 'message' => "Room {$num} deleted."]);
    }

    /**
     * API: return available rooms list for subject schedule dropdowns.
     * Returns all active rooms (available + occupied) so teachers can still assign.
     */
    public function apiList()
    {
        Classroom::recomputeAll();

        $rooms = Classroom::where('room_status', '!=', 'under_maintenance')
            ->orderBy('room_number')
            ->get(['id', 'room_number', 'room_type', 'capacity', 'availability_status']);

        return response()->json(['rooms' => $rooms]);
    }
}
