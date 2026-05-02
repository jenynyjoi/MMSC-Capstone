<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\User;
use App\Models\StudentEnrollment;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        // Sync adviser_status and full_name for any sections where they don't match reality
        Section::where('school_year', $schoolYear)
            ->where('is_subject_section', false)
            ->each(function ($section) {
                $correctName = Section::formatName($section->grade_level, $section->section_name, $section->strand);
                $correctAdviserStatus = $section->homeroom_adviser_id || $section->homeroom_adviser_name ? 'assigned' : 'tba';
                $updates = [];
                if ($section->full_name !== $correctName) {
                    $updates['full_name'] = $correctName;
                }
                if ($section->adviser_status !== $correctAdviserStatus) {
                    $updates['adviser_status'] = $correctAdviserStatus;
                }
                if ($updates) {
                    $section->update($updates);
                }
            });

        $query = Section::query()->where('school_year', $schoolYear)->where('is_subject_section', false);

        if ($request->filled('grade'))        $query->where('grade_level', $request->grade);
        if ($request->filled('adviser_status')) $query->where('adviser_status', $request->adviser_status);
        if ($request->filled('availability')) $query->where('availability', $request->availability);
        if ($request->filled('status'))       $query->where('section_status', $request->status);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('section_name','like',"%$s%")->orWhere('section_id','like',"%$s%")->orWhere('homeroom_adviser_name','like',"%$s%"));
        }

        $sections = $query->orderBy('grade_level')->orderBy('section_name')->paginate(10)->withQueryString();

        $stats = [
            'total'           => Section::where(['school_year'=>$schoolYear,'is_subject_section'=>false])->count(),
            'available'       => Section::where(['school_year'=>$schoolYear,'is_subject_section'=>false,'availability'=>'available'])->count(),
            'without_adviser' => Section::where(['school_year'=>$schoolYear,'is_subject_section'=>false,'adviser_status'=>'tba'])->count(),
            'available_slots' => Section::where(['school_year'=>$schoolYear,'is_subject_section'=>false])->sum(DB::raw('capacity - current_enrollment')),
        ];

        // Teachers with role = teacher (for adviser dropdown)
        try {
            $teachers = User::role('teacher')
                ->orderBy('name')
                ->get(['id','name'])
                ->map(fn($t) => ['id' => $t->id, 'name' => $t->name]);
        } catch (\Exception $e) {
            $teachers = collect();
        }

        // Pending student counts per grade (for "Students to Assign" hint)
        try {
            $pendingByGrade = StudentEnrollment::where('school_year', $schoolYear)
                ->where('assignment_status', 'pending')
                ->select('grade_level', DB::raw('count(*) as total'))
                ->groupBy('grade_level')
                ->pluck('total', 'grade_level');
        } catch (\Exception $e) {
            $pendingByGrade = collect();
        }

        $schoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get(['name']);

        $availableRooms = Classroom::where('room_status', 'active')
            ->where('availability_status', 'available')
            ->orderBy('room_number')
            ->get(['room_number', 'room_type', 'capacity']);

        $allActiveRooms = Classroom::where('room_status', 'active')
            ->orderBy('room_number')
            ->get(['room_number', 'room_type', 'capacity', 'availability_status']);

        return view('admin.classes.sections', compact(
            'sections', 'stats', 'schoolYear', 'teachers', 'pendingByGrade', 'schoolYears',
            'availableRooms', 'allActiveRooms'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year'          => 'required|string|max:20',
            'grade_level'          => 'required|string|max:50',
            'section_name'         => 'required|string|max:100',
            'room'                 => 'nullable|string|max:50',
            'capacity'             => 'nullable|integer|min:20|max:30',
            'homeroom_adviser_id'  => 'nullable|integer|exists:users,id',
            'track'                => 'nullable|string|max:100',
            'strand'               => 'nullable|string|max:100',
        ]);

        // Auto-generate section_id
        $year  = date('Y');
        $count = Section::whereYear('created_at', $year)->count() + 1;
        $sectionId = 'SEC-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $fullName = Section::formatName($validated['grade_level'], $validated['section_name'], $validated['strand'] ?? null);

        // Determine program level from grade
        $grade = $validated['grade_level'];
        if (in_array($grade, ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'])) {
            $programLevel = 'Elementary';
        } elseif (in_array($grade, ['Grade 7','Grade 8','Grade 9','Grade 10'])) {
            $programLevel = 'Junior High School';
        } else {
            $programLevel = 'Senior High School';
        }

        $adviserId   = $validated['homeroom_adviser_id'] ?? null;
        $adviserUser = $adviserId ? User::find($adviserId) : null;

        $section = Section::create([
            'section_id'            => $sectionId,
            'school_year'           => $validated['school_year'],
            'grade_level'           => $validated['grade_level'],
            'section_name'          => $validated['section_name'],
            'full_name'             => $fullName,
            'room'                  => $validated['room'] ?? null,
            'capacity'              => $validated['capacity'] ?? 30,
            'current_enrollment'    => 0,
            'homeroom_adviser_id'   => $adviserId,
            'homeroom_adviser_name' => $adviserUser?->name,
            'adviser_status'        => $adviserUser ? 'assigned' : 'tba',
            'track'                 => $validated['track'] ?? null,
            'strand'                => $validated['strand'] ?? null,
            'program_level'         => $programLevel,
            'section_status'        => 'active',
            'availability'          => 'available',
            'section_type'          => 'regular',
            'is_subject_section'    => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'section' => $section]);
        }

        return redirect()->route('admin.classes.sections', ['school_year' => $validated['school_year']])
            ->with('success', 'Section "' . $section->section_name . '" created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'section_name'         => 'required|string|max:100',
            'room'                 => 'nullable|string|max:50',
            'capacity'             => 'integer|min:20|max:30',
            'homeroom_adviser_id'  => 'nullable|integer|exists:users,id',
            'section_status'       => 'required|in:active,inactive,archived',
        ]);

        $section     = Section::findOrFail($id);
        $adviserId   = $request->homeroom_adviser_id ?: null;
        $adviserUser = $adviserId ? User::find($adviserId) : null;

        // Clear old adviser's TeacherProfile advisory if adviser changed
        if ($section->homeroom_adviser_id && $section->homeroom_adviser_id != $adviserId) {
            Section::where('id', $section->id)->update(['homeroom_adviser_id' => null]);
        }

        $section->update([
            'section_name'          => $request->section_name,
            'full_name'             => Section::formatName($section->grade_level, $request->section_name, $section->strand),
            'room'                  => $request->room,
            'capacity'              => $request->capacity ?? $section->capacity,
            'homeroom_adviser_id'   => $adviserId,
            'homeroom_adviser_name' => $adviserUser?->name,
            'adviser_status'        => $adviserUser ? 'assigned' : 'tba',
            'section_status'        => $request->section_status,
        ]);
        $section->updateAvailability();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'section' => $section]);
        }

        return back()->with('success', 'Section updated.');
    }

    public function destroy(int $id)
    {
        $section = Section::findOrFail($id);
        if ($section->current_enrollment > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete a section with enrolled students.'], 422);
        }
        $section->delete();
        return response()->json(['success' => true]);
    }

    public function show(int $id)
    {
        $section = Section::findOrFail($id);
        return response()->json($section);
    }
}