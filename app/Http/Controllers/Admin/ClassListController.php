<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use App\Models\SubjectSchedule;
use Illuminate\Http\Request;

class ClassListController extends Controller
{
    // ══════════════════════════════════════════════════════
    // INDEX — list all classes (subject_allocation rows)
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear   = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $gradeSection = $request->get('grade_section');
        $subjectId    = $request->get('subject_id');
        $teacherId    = $request->get('teacher_id');
        $search       = $request->get('search');

        $query = SubjectAllocation::with(['section', 'subject', 'teacher', 'schedules'])
            ->where('school_year', $schoolYear)
            ->when($subjectId, fn($q) => $q->where('subject_id', $subjectId))
            ->when($teacherId, fn($q) => $q->where('teacher_id', $teacherId))
            ->when($gradeSection, fn($q) => $q->whereHas('section', fn($sq) =>
                $sq->where('grade_level', $gradeSection)
            ))
            ->when($search, fn($q) => $q->where(fn($sq) =>
                $sq->where('subject_name', 'like', "%{$search}%")
                   ->orWhereHas('section', fn($s) => $s->where('section_name', 'like', "%{$search}%"))
                   ->orWhereHas('teacher', fn($t) => $t->where('name', 'like', "%{$search}%"))
            ))
            ->orderBy('subject_name');

        $classes = $query->paginate(10)->withQueryString();

        // Flatten schedules for display (first slot shown inline, rest in modal)
        $classes->through(function ($alloc) {
            $alloc->first_schedule = $alloc->schedules->first();
            $alloc->schedule_summary = $alloc->schedules
                ->map(fn($s) => $s->day_of_week . ' ' . \Carbon\Carbon::parse($s->time_start)->format('g:i A'))
                ->implode(', ');
            return $alloc;
        });

        // Filter dropdowns
        $sections  = Section::where('school_year', $schoolYear)
            ->orderBy('grade_level')->orderBy('section_name')
            ->get(['id', 'grade_level', 'section_name']);

        $subjects  = Subject::where('is_active', true)->orderBy('subject_name')->get(['id', 'subject_name']);

        $teachers  = \App\Models\User::role('teacher')->orderBy('name')->get(['id', 'name']);

        return view('admin.class-list', compact(
            'classes', 'sections', 'subjects', 'teachers', 'schoolYear'
        ));
    }

    // ══════════════════════════════════════════════════════
    // SHOW — full class detail (AJAX)
    // ══════════════════════════════════════════════════════
    public function show(int $id)
    {
        $alloc = SubjectAllocation::with(['section', 'subject', 'teacher', 'schedules'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'class' => [
                'id'            => $alloc->id,
                'subject_code'  => $alloc->subject_code,
                'subject_name'  => $alloc->subject_name,
                'hours_per_week'=> $alloc->hours_per_week,
                'school_year'   => $alloc->school_year,
                'section_id'    => $alloc->section_id,
                'grade_level'   => $alloc->section?->grade_level ?? '—',
                'section_name'  => $alloc->section?->section_name ?? '—',
                'program_level' => $alloc->section?->program_level ?? '—',
                'track'         => $alloc->section?->track ?? null,
                'strand'        => $alloc->section?->strand ?? null,
                'teacher'       => $alloc->teacher?->name ?? 'No teacher assigned',
                'schedules'     => $alloc->schedules->map(fn($s) => [
                    'day'        => $s->day_of_week,
                    'time_start' => \Carbon\Carbon::parse($s->time_start)->format('g:i A'),
                    'time_end'   => \Carbon\Carbon::parse($s->time_end)->format('g:i A'),
                    'room'       => $s->room ?? '—',
                ])->toArray(),
            ],
        ]);
    }
}