<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\GradeCurriculumConfig;
use App\Models\GradeCurriculumSubject;
use App\Models\GradeComponent;
use App\Models\Section;
use App\Models\SectionAllocationConfig;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use App\Models\SubjectSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicController extends Controller
{
    // ══════════════════════════════════════════════════════
    // MAIN PAGE
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        // ── Subject Master ────────────────────────────────
        $subjectQuery = Subject::query()
            ->when($request->department,     fn($q) => $q->where('department',     $request->department))
            ->when($request->grade_level,    fn($q) => $q->where('grade_level',    $request->grade_level))
            ->when($request->program_level,  fn($q) => $q->where('program_level',  $request->program_level))
            ->where('is_active', true)
            ->orderBy('subject_code');
        $subjects = $subjectQuery->paginate(10, ['*'], 'subject_page')->withQueryString();

        // ── Subject Allocation ────────────────────────────
        $allocQuery = Section::with(['allocationConfig' => fn($q) => $q->where('school_year', $schoolYear)])
            ->where('school_year', $schoolYear)
            ->when($request->alloc_grade, fn($q) => $q->where('grade_level', $request->alloc_grade))
            ->when($request->alloc_search, fn($q) => $q->where(function ($sq) use ($request) {
                $sq->where('section_name', 'like', '%'.$request->alloc_search.'%')
                   ->orWhere('grade_level',   'like', '%'.$request->alloc_search.'%');
            }))
            ->orderBy('grade_level')->orderBy('section_name');
        $allocations = $allocQuery->paginate(10, ['*'], 'alloc_page')->withQueryString();

        // Real allocation counts direct from SubjectAllocation (bypasses stale cache)
        $allocCounts = SubjectAllocation::where('school_year', $schoolYear)
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        // Teacher-assigned subject counts per section
        $teacherCounts = SubjectAllocation::where('school_year', $schoolYear)
            ->whereNotNull('teacher_id')
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        // ── Component List ────────────────────────────────
        $components = GradeComponent::where('is_active', true)
            ->orderBy('component_name')
            ->paginate(10, ['*'], 'comp_page')->withQueryString();

        // ── Assessment List ───────────────────────────────
        $assessQuery = Assessment::with(['allocation.section', 'component'])
            ->when($request->assess_allocation, fn($q) => $q->where('allocation_id', $request->assess_allocation))
            ->when($request->assess_component,  fn($q) => $q->where('component_id',  $request->assess_component))
            ->when($request->assess_quarter,    fn($q) => $q->where('quarter',        $request->assess_quarter));
        $assessments = $assessQuery->paginate(10, ['*'], 'assess_page')->withQueryString();

        // ── Supporting data for modals ────────────────────
        $sections = Section::where('school_year', $schoolYear)
            ->orderBy('grade_level')->orderBy('section_name')->get();
        $allocGradeLevels = Section::where('school_year', $schoolYear)
            ->distinct()->orderBy('grade_level')->pluck('grade_level');
        $teachers = \App\Models\User::role('teacher')->orderBy('name')->get(['id', 'name']);
        $allComponents = GradeComponent::where('is_active', true)->orderBy('component_name')->get();
        $allAllocations = SubjectAllocation::with(['section', 'subject'])->where('school_year', $schoolYear)->get();

        return view('admin.academics.subjects', compact(
            'subjects', 'allocations', 'components', 'assessments',
            'sections', 'teachers', 'allComponents', 'allAllocations',
            'schoolYear', 'allocCounts', 'teacherCounts', 'allocGradeLevels'
        ));
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT MASTER — STORE
    // ══════════════════════════════════════════════════════
    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_code'      => 'required|string|max:50|unique:subjects,subject_code',
            'subject_name'      => 'required|string|max:255',
            'description'       => 'nullable|string',
            'department'        => 'nullable|string|max:100',
            'grade_level'       => 'nullable|string|max:50',
            'program_level'     => 'nullable|string|max:50',
            'subject_type'      => 'nullable|string|max:50',
            'track'             => 'nullable|string|max:50',
            'strand'            => 'nullable|string|max:50',
            'hours_per_meeting' => 'required|numeric|min:0.5|max:8',
            'meetings_per_week' => 'required|integer|min:1|max:10',
            'has_semester'      => 'boolean',
            'default_semester'  => 'nullable|string|max:50',
        ]);

        $subject = Subject::create(array_merge($validated, ['created_by' => auth()->id()]));

        return response()->json([
            'success' => true,
            'message' => 'Subject "' . $subject->subject_name . '" created successfully.',
            'subject' => $subject,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT MASTER — SHOW (AJAX)
    // ══════════════════════════════════════════════════════
    public function showSubject(int $id)
    {
        $subject = Subject::findOrFail($id);
        return response()->json(['success' => true, 'subject' => $subject]);
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT MASTER — UPDATE
    // ══════════════════════════════════════════════════════
    public function updateSubject(Request $request, int $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'subject_code'      => 'required|string|max:50|unique:subjects,subject_code,' . $id,
            'subject_name'      => 'required|string|max:255',
            'description'       => 'nullable|string',
            'department'        => 'nullable|string|max:100',
            'grade_level'       => 'nullable|string|max:50',
            'program_level'     => 'nullable|string|max:50',
            'subject_type'      => 'nullable|string|max:50',
            'track'             => 'nullable|string|max:50',
            'strand'            => 'nullable|string|max:50',
            'hours_per_meeting' => 'required|numeric|min:0.5|max:8',
            'meetings_per_week' => 'required|integer|min:1|max:10',
            'has_semester'      => 'boolean',
            'default_semester'  => 'nullable|string|max:50',
        ]);

        // Check if allocated — warn but allow
        $allocatedCount = SubjectAllocation::where('subject_id', $id)->count();
        $subject->update($validated);

        return response()->json([
            'success'         => true,
            'message'         => 'Subject updated successfully.',
            'allocated_count' => $allocatedCount,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT MASTER — DESTROY
    // ══════════════════════════════════════════════════════
    public function destroySubject(int $id)
    {
        $subject        = Subject::findOrFail($id);
        $allocatedCount = SubjectAllocation::where('subject_id', $id)->count();

        if ($allocatedCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete: subject is allocated to ' . $allocatedCount . ' section(s). Remove all allocations first.',
            ], 422);
        }

        $subject->update(['is_active' => false]); // soft-delete
        return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
    }

    // ══════════════════════════════════════════════════════
    // ALLOCATION CONFIG — GET (for allocation detail modal)
    // ══════════════════════════════════════════════════════
    public function getAllocationConfig(Request $request)
    {
        $sectionId  = $request->section_id;
        $schoolYear = $request->school_year ?? \App\Models\SchoolYear::activeName();

        $section = Section::findOrFail($sectionId);
        $config  = SectionAllocationConfig::firstOrCreate(
            ['section_id' => $sectionId, 'school_year' => $schoolYear],
            ['total_subjects_required' => 0, 'total_subjects_allocated' => 0, 'allocation_status' => 'pending']
        );
        $config->recalculate();

        $assigned = SubjectAllocation::with(['subject', 'teacher'])
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->get();

        return response()->json([
            'success' => true,
            'section' => [
                'id'                => $section->id,
                'section_name'      => $section->section_name,
                'display_name'      => $section->display_name,
                'school_year'       => $schoolYear,
                'program_level'     => $section->program_level ?? '—',
                'grade_level'       => $section->grade_level,
                'adviser'           => $section->homeroom_adviser_name ?? '—',
                'room'              => $section->room ?? '—',
            ],
            'config' => [
                'id'                       => $config->id,
                'total_subjects_required'  => $config->total_subjects_required,
                'total_subjects_allocated' => $config->total_subjects_allocated,
                'remaining'                => max(0, $config->total_subjects_required - $config->total_subjects_allocated),
                'allocation_status'        => $config->allocation_status,
                'progress_pct'             => $config->total_subjects_required > 0
                    ? round(($config->total_subjects_allocated / $config->total_subjects_required) * 100)
                    : 0,
            ],
            'assigned' => $assigned->map(fn($a) => [
                'id'             => $a->id,
                'subject_code'   => $a->subject_code,
                'subject_name'   => $a->subject_name,
                'hours_per_week' => $a->hours_per_week,
                'teacher_id'     => $a->teacher_id,
                'teacher'        => $a->teacher?->name ?? '—',
            ]),
        ]);
    }

    // ══════════════════════════════════════════════════════
    // ALLOCATION CONFIG — SET TOTAL REQUIRED
    // ══════════════════════════════════════════════════════
    public function setAllocationRequired(Request $request)
    {
        $request->validate([
            'section_id'               => 'required|exists:sections,id',
            'school_year'              => 'required|string',
            'total_subjects_required'  => 'required|integer|min:1|max:30',
        ]);

        $config = SectionAllocationConfig::firstOrCreate(
            ['section_id' => $request->section_id, 'school_year' => $request->school_year]
        );
        $config->total_subjects_required = $request->total_subjects_required;
        $config->recalculate();

        return response()->json(['success' => true, 'message' => 'Total subjects required set.', 'config' => $config]);
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT ALLOCATION — STORE (assign subject to section)
    // ══════════════════════════════════════════════════════
    public function storeAllocation(Request $request)
    {
        $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'subject_id'  => 'required|exists:subjects,id',
            'school_year' => 'required|string',
            'teacher_id'  => 'nullable|exists:users,id',
        ]);

        // Check not already assigned
        $exists = SubjectAllocation::where([
            'section_id'  => $request->section_id,
            'subject_id'  => $request->subject_id,
            'school_year' => $request->school_year,
        ])->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'This subject is already assigned to this section.'], 422);
        }

        // Check remaining slots
        $config = SectionAllocationConfig::where([
            'section_id'  => $request->section_id,
            'school_year' => $request->school_year,
        ])->first();

        if ($config && $config->total_subjects_required > 0 &&
            $config->total_subjects_allocated >= $config->total_subjects_required) {
            return response()->json(['success' => false, 'message' => 'All subject slots are filled. Increase Total Subjects Required first.'], 422);
        }

        $subject = Subject::findOrFail($request->subject_id);

        DB::transaction(function () use ($request, $subject, $config) {
            $allocation = SubjectAllocation::create([
                'section_id'     => $request->section_id,
                'subject_id'     => $request->subject_id,
                'school_year'    => $request->school_year,
                'teacher_id'     => $request->teacher_id,
                'subject_code'   => $subject->subject_code,
                'subject_name'   => $subject->subject_name,
                'hours_per_week' => $subject->hours_per_week,
                'created_by'     => auth()->id(),
            ]);

            // Update teacher load
            if ($request->teacher_id) {
                $load = \App\Models\TeacherLoad::firstOrCreate(
                    ['teacher_id' => $request->teacher_id, 'school_year' => $request->school_year],
                    ['max_weekly_hours' => 40, 'current_weekly_hours' => 0]
                );
                $load->increment('current_weekly_hours', $subject->hours_per_week);
            }

            // Recalculate config
            if ($config) $config->recalculate();
            else {
                $cfg = SectionAllocationConfig::firstOrCreate(
                    ['section_id' => $request->section_id, 'school_year' => $request->school_year]
                );
                $cfg->recalculate();
            }
        });

        return response()->json(['success' => true, 'message' => '"' . $subject->subject_name . '" assigned successfully.']);
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT ALLOCATION — UPDATE (teacher / schedule)
    // ══════════════════════════════════════════════════════
    public function updateAllocation(Request $request, int $id)
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $allocation   = SubjectAllocation::findOrFail($id);
        $oldTeacherId = $allocation->teacher_id;

        DB::transaction(function () use ($request, $allocation, $oldTeacherId) {
            if ($oldTeacherId && $oldTeacherId !== $request->teacher_id) {
                \App\Models\TeacherLoad::where(['teacher_id' => $oldTeacherId, 'school_year' => $allocation->school_year])
                    ->decrement('current_weekly_hours', $allocation->hours_per_week);
            }
            if ($request->teacher_id && $request->teacher_id !== $oldTeacherId) {
                $load = \App\Models\TeacherLoad::firstOrCreate(
                    ['teacher_id' => $request->teacher_id, 'school_year' => $allocation->school_year],
                    ['max_weekly_hours' => 40, 'current_weekly_hours' => 0]
                );
                $load->increment('current_weekly_hours', $allocation->hours_per_week);
            }
            $allocation->update(['teacher_id' => $request->teacher_id]);
        });

        return response()->json(['success' => true, 'message' => 'Allocation updated.']);
    }

    // ══════════════════════════════════════════════════════
    // SUBJECT ALLOCATION — DESTROY (remove from section)
    // ══════════════════════════════════════════════════════
    public function destroyAllocation(int $id)
    {
        $allocation = SubjectAllocation::findOrFail($id);

        DB::transaction(function () use ($allocation) {
            // Adjust teacher load
            if ($allocation->teacher_id) {
                \App\Models\TeacherLoad::where(['teacher_id' => $allocation->teacher_id, 'school_year' => $allocation->school_year])
                    ->decrement('current_weekly_hours', $allocation->hours_per_week);
            }

            $allocation->schedules()->delete();
            $allocation->delete();

            $config = SectionAllocationConfig::where([
                'section_id'  => $allocation->section_id,
                'school_year' => $allocation->school_year,
            ])->first();
            if ($config) $config->recalculate();
        });

        return response()->json(['success' => true, 'message' => 'Subject removed from section.']);
    }

    // ══════════════════════════════════════════════════════
    // GET TEACHERS FOR ALLOCATION MODAL (with workload info)
    // ══════════════════════════════════════════════════════
    public function getTeachersForAllocation(Request $request)
    {
        $schoolYear = $request->school_year ?? \App\Models\SchoolYear::activeName();
        $subjectId  = $request->subject_id;   // optional — for workload-fit warning
        $sectionId  = $request->section_id;

        // Load section to get grade level (for context)
        $section = $sectionId ? Section::find($sectionId) : null;

        $hoursNeeded = 0;
        if ($subjectId) {
            $subject     = Subject::find($subjectId);
            $hoursNeeded = $subject ? (float) ($subject->hours_per_meeting * $subject->meetings_per_week) : 0;
        }

        // Pre-load teacher profiles for specialization/grade validation
        $profilesByUserId = \App\Models\TeacherProfile::whereIn(
            'user_id',
            \App\Models\User::role('teacher')->pluck('id')
        )->get()->keyBy('user_id');

        $subject     = $subjectId ? Subject::find($subjectId) : null;
        $subjectDept = $subject?->department ?? null;
        $subjectName = $subject?->subject_name ?? null;
        $sectionGrade = $section?->grade_level ?? null;

        $teachers = \App\Models\User::role('teacher')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(function ($t) use ($schoolYear, $hoursNeeded, $sectionId, $profilesByUserId, $subjectDept, $subjectName, $sectionGrade) {
                $load = \App\Models\TeacherLoad::where([
                    'teacher_id'  => $t->id,
                    'school_year' => $schoolYear,
                ])->first();

                $maxHours     = $load?->max_weekly_hours     ?? 40;
                $currentHours = $load?->current_weekly_hours ?? 0;
                $availableHours = max(0, $maxHours - $currentHours);
                $loadPct      = $maxHours > 0 ? min(100, round(($currentHours / $maxHours) * 100)) : 0;

                // Already assigned to this section?
                $alreadyInSection = $sectionId
                    ? SubjectAllocation::where('section_id', $sectionId)
                        ->where('teacher_id', $t->id)
                        ->where('school_year', $schoolYear)
                        ->count()
                    : 0;

                $status = match (true) {
                    $currentHours >= $maxHours => 'full',
                    $loadPct >= 80             => 'near_full',
                    default                    => 'available',
                };

                $conflict = false;
                $conflictMsg = null;
                if ($hoursNeeded > 0 && $availableHours < $hoursNeeded) {
                    $conflict    = true;
                    $conflictMsg = "Adding this subject ({$hoursNeeded}h/wk) exceeds this teacher's available hours ({$availableHours}h remaining).";
                }

                // Specialization & grade-level mismatch warnings
                $warnings = [];
                $profile  = $profilesByUserId[$t->id] ?? null;
                if ($profile) {
                    $specs       = $profile->specializations ?? [];
                    $gradeLevels = $profile->grade_levels    ?? [];

                    if ($subjectDept && count($specs) > 0 && !in_array($subjectDept, $specs) && !in_array($subjectName, $specs)) {
                        $warnings[] = 'Not specialized in ' . $subjectDept . ' (specializes in: ' . implode(', ', $specs) . ').';
                    }
                    if ($sectionGrade && count($gradeLevels) > 0 && !in_array($sectionGrade, $gradeLevels)) {
                        $warnings[] = 'Not assigned to ' . $sectionGrade . ' (assigned to: ' . implode(', ', $gradeLevels) . ').';
                    }
                }

                return [
                    'id'                 => $t->id,
                    'name'               => $t->name,
                    'current_hours'      => (float) $currentHours,
                    'max_hours'          => (float) $maxHours,
                    'available_hours'    => (float) $availableHours,
                    'load_pct'           => $loadPct,
                    'status'             => $status,
                    'already_in_section' => $alreadyInSection,
                    'conflict'           => $conflict,
                    'conflict_msg'       => $conflictMsg,
                    'warnings'           => $warnings,
                    'has_warnings'       => count($warnings) > 0,
                    'specializations'    => $profile?->specializations ?? [],
                    'grade_levels'       => $profile?->grade_levels    ?? [],
                ];
            });

        return response()->json(['success' => true, 'teachers' => $teachers]);
    }

    // ══════════════════════════════════════════════════════
    // GET SUBJECTS FOR ALLOCATION MODAL (filtered by grade/program)
    // ══════════════════════════════════════════════════════
    public function getSubjectsForSection(Request $request)
    {
        $section = Section::findOrFail($request->section_id);

        $alreadyAssigned = SubjectAllocation::where([
            'section_id'  => $request->section_id,
            'school_year' => $request->school_year ?? \App\Models\SchoolYear::activeName(),
        ])->pluck('subject_id');

        $subjects = Subject::where('is_active', true)
            ->where('grade_level', $section->grade_level)
            ->whereNotIn('id', $alreadyAssigned)
            ->orderBy('subject_name')
            ->get(['id', 'subject_code', 'subject_name', 'hours_per_meeting', 'meetings_per_week', 'department']);

        return response()->json(['success' => true, 'subjects' => $subjects]);
    }

    // ══════════════════════════════════════════════════════
    // GRADE COMPONENTS — STORE
    // ══════════════════════════════════════════════════════
    public function storeComponent(Request $request)
    {
        $validated = $request->validate([
            'component_code'     => 'required|string|max:50|unique:grade_components,component_code',
            'component_name'     => 'required|string|max:100',
            'grade_percentage'   => 'required|numeric|min:0|max:100',
            'grade_level'        => 'nullable|string|max:50',
        ]);

        $component = GradeComponent::create($validated);
        return response()->json(['success' => true, 'message' => 'Component "' . $component->component_name . '" created.', 'component' => $component]);
    }

    public function updateComponent(Request $request, int $id)
    {
        $component = GradeComponent::findOrFail($id);
        $validated = $request->validate([
            'component_code'   => 'required|string|max:50|unique:grade_components,component_code,' . $id,
            'component_name'   => 'required|string|max:100',
            'grade_percentage' => 'required|numeric|min:0|max:100',
            'grade_level'      => 'nullable|string|max:50',
        ]);
        $component->update($validated);
        return response()->json(['success' => true, 'message' => 'Component updated.']);
    }

    public function destroyComponent(int $id)
    {
        $component = GradeComponent::findOrFail($id);
        $inUse = Assessment::where('component_id', $id)->count();
        if ($inUse > 0) {
            return response()->json(['success' => false, 'message' => 'Component is used in ' . $inUse . ' assessment(s). Cannot delete.'], 422);
        }
        $component->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Component deleted.']);
    }

    // ══════════════════════════════════════════════════════
    // ASSESSMENTS — STORE
    // ══════════════════════════════════════════════════════
    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'allocation_id'   => 'required|exists:subject_allocation,id',
            'component_id'    => 'required|exists:grade_components,id',
            'quarter'         => 'required|in:First,Second,Third,Fourth',
            'assessment_name' => 'required|string|max:255',
            'max_score'       => 'required|numeric|min:1',
            'assessment_date' => 'nullable|date',
        ]);

        $assessment = Assessment::create(array_merge($validated, ['created_by' => auth()->id()]));
        return response()->json(['success' => true, 'message' => 'Assessment "' . $assessment->assessment_name . '" created.', 'assessment' => $assessment]);
    }

    public function updateAssessment(Request $request, int $id)
    {
        $assessment = Assessment::findOrFail($id);
        $validated  = $request->validate([
            'allocation_id'   => 'required|exists:subject_allocation,id',
            'component_id'    => 'required|exists:grade_components,id',
            'quarter'         => 'required|in:First,Second,Third,Fourth',
            'assessment_name' => 'required|string|max:255',
            'max_score'       => 'required|numeric|min:1',
            'assessment_date' => 'nullable|date',
        ]);
        $assessment->update($validated);
        return response()->json(['success' => true, 'message' => 'Assessment updated.']);
    }

    public function destroyAssessment(int $id)
    {
        Assessment::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Assessment deleted.']);
    }

    // ══════════════════════════════════════════════════════
    // CURRICULUM — per Grade Level
    // ══════════════════════════════════════════════════════
    public function getCurriculumGrades(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $configs = GradeCurriculumConfig::withCount('subjects')
            ->where('school_year', $schoolYear)
            ->get()
            ->keyBy('grade_level');

        $gradeLevels = [
            'Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6',
            'Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12',
        ];

        $result = [];
        foreach ($gradeLevels as $gl) {
            $cfg      = $configs->get($gl);
            $result[] = [
                'grade_level'   => $gl,
                'short'         => $this->gradeShort($gl),
                'has_config'    => (bool)$cfg,
                'assigned'      => $cfg ? $cfg->subjects_count : 0,
                'required'      => $cfg ? $cfg->total_subjects_required : 0,
                'program_level' => $cfg ? $cfg->program_level : $this->defaultProgramLevel($gl),
            ];
        }

        return response()->json(['success' => true, 'grades' => $result]);
    }

    public function getCurriculumDetail(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $gradeLevel = $request->get('grade_level');

        $config = GradeCurriculumConfig::with(['subjects.subject'])
            ->where('grade_level', $gradeLevel)
            ->where('school_year', $schoolYear)
            ->first();

        $programLevel = $config ? $config->program_level : $this->defaultProgramLevel($gradeLevel);
        $existingIds  = $config ? $config->subjects->pluck('subject_id')->toArray() : [];

        // Pre-compute section counts for subjects already in curriculum
        $sectionCounts = SubjectAllocation::whereIn('subject_id', $existingIds)
            ->where('school_year', $schoolYear)
            ->select('subject_id', DB::raw('count(*) as cnt'))
            ->groupBy('subject_id')
            ->pluck('cnt', 'subject_id');

        // Active subjects for this grade level not yet assigned to this curriculum
        $allSubjects = Subject::where('is_active', true)
            ->where('grade_level', $gradeLevel)
            ->whereNotIn('id', $existingIds)
            ->orderBy('subject_code')
            ->get(['id','subject_code','subject_name','meetings_per_week','hours_per_meeting','subject_type','default_semester']);

        return response()->json([
            'success'      => true,
            'config'       => $config ? [
                'id'                      => $config->id,
                'grade_level'             => $config->grade_level,
                'program_level'           => $config->program_level,
                'school_year'             => $config->school_year,
                'total_subjects_required' => $config->total_subjects_required,
                'assigned_count'          => $config->subjects->count(),
            ] : null,
            'subjects'     => $config ? $config->subjects->map(fn($cs) => [
                'id'                => $cs->id,
                'subject_id'        => $cs->subject_id,
                'subject_code'      => $cs->subject->subject_code ?? '—',
                'subject_name'      => $cs->subject->subject_name ?? '—',
                'hours_per_week'    => $cs->hours_per_week,
                'meetings_per_week' => $cs->meetings_per_week,
                'hours_per_meeting' => $cs->hours_per_meeting,
                'subject_type'      => $cs->subject_type,
                'is_required'       => $cs->is_required,
                'semester'          => $cs->semester,
                'section_count'     => $sectionCounts[$cs->subject_id] ?? 0,
            ])->values() : [],
            'all_subjects' => $allSubjects,
        ]);
    }

    public function saveCurriculum(Request $request)
    {
        $request->validate([
            'grade_level'             => 'required|string',
            'program_level'           => 'required|string',
            'school_year'             => 'required|string',
            'total_subjects_required' => 'required|integer|min:1|max:50',
            'subject_ids'             => 'required|array|min:1',
            'subject_ids.*'           => 'exists:subjects,id',
            'semester'                => 'nullable|string|in:1st Semester,2nd Semester,Full Year',
        ]);

        DB::transaction(function () use ($request) {
            $config = GradeCurriculumConfig::updateOrCreate(
                ['grade_level' => $request->grade_level, 'school_year' => $request->school_year],
                ['program_level' => $request->program_level, 'total_subjects_required' => $request->total_subjects_required]
            );

            $semesterOverride = $request->semester; // null if not SHS

            foreach ($request->subject_ids as $subjectId) {
                $subject = Subject::find($subjectId);
                GradeCurriculumSubject::firstOrCreate(
                    ['curriculum_config_id' => $config->id, 'subject_id' => $subjectId],
                    [
                        'hours_per_week'    => $subject?->hours_per_week,
                        'meetings_per_week' => $subject?->meetings_per_week,
                        'hours_per_meeting' => $subject?->hours_per_meeting,
                        'subject_type'      => $subject?->subject_type,
                        'is_required'       => true,
                        'semester'          => $semesterOverride ?? $subject?->default_semester,
                    ]
                );
            }
        });

        return response()->json(['success' => true, 'message' => 'Curriculum saved successfully.']);
    }

    public function updateCurriculumSubject(Request $request, int $id)
    {
        $cs = GradeCurriculumSubject::findOrFail($id);
        $request->validate([
            'hours_per_week'    => 'nullable|numeric|min:0',
            'meetings_per_week' => 'nullable|integer|min:0',
            'subject_type'      => 'nullable|string',
            'is_required'       => 'nullable|boolean',
            'semester'          => 'nullable|string',
        ]);

        $mpw = intval($request->meetings_per_week) ?: 1;
        $hpw = floatval($request->hours_per_week);
        $hpm = $mpw > 0 && $hpw > 0 ? round($hpw / $mpw, 2) : $cs->hours_per_meeting;

        $cs->update([
            'hours_per_week'    => $hpw ?: null,
            'meetings_per_week' => $mpw ?: null,
            'hours_per_meeting' => $hpm ?: null,
            'subject_type'      => $request->subject_type,
            'is_required'       => $request->boolean('is_required', true),
            'semester'          => $request->semester,
        ]);

        return response()->json(['success' => true, 'message' => 'Curriculum subject updated.']);
    }

    public function removeCurriculumSubject(int $id)
    {
        GradeCurriculumSubject::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Subject removed from curriculum.']);
    }

    // ══════════════════════════════════════════════════════
    // ASSIGN TEACHER — get subjects for a section with teacher info
    // ══════════════════════════════════════════════════════
    public function getSectionSubjectsWithTeachers(Request $request)
    {
        $sectionId  = $request->section_id;
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $subjects = SubjectAllocation::where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->with('teacher')
            ->orderBy('subject_name')
            ->get();

        $teachers = \App\Models\User::role('teacher')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success'  => true,
            'subjects' => $subjects->map(fn($s) => [
                'id'            => $s->id,
                'subject_id'    => $s->subject_id,
                'subject_code'  => $s->subject_code,
                'subject_name'  => $s->subject_name,
                'hours_per_week'=> $s->hours_per_week,
                'teacher_id'    => $s->teacher_id,
                'teacher_name'  => $s->teacher?->name,
            ]),
            'teachers' => $teachers,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // ASSIGN TEACHER — save teacher assignment to subject allocation
    // ══════════════════════════════════════════════════════
    public function assignTeacher(Request $request)
    {
        $request->validate([
            'allocation_id' => 'required|exists:subject_allocation,id',
            'teacher_id'    => 'nullable|exists:users,id',
        ]);

        SubjectAllocation::where('id', $request->allocation_id)->update([
            'teacher_id' => $request->teacher_id ?: null,
        ]);

        return response()->json(['success' => true, 'message' => 'Teacher assigned successfully.']);
    }

    private function gradeShort(string $g): string
    {
        return match ($g) {
            'Kinder'   => 'Kinder',
            'Grade 1'  => 'G1',  'Grade 2'  => 'G2',  'Grade 3'  => 'G3',
            'Grade 4'  => 'G4',  'Grade 5'  => 'G5',  'Grade 6'  => 'G6',
            'Grade 7'  => 'G7',  'Grade 8'  => 'G8',  'Grade 9'  => 'G9',
            'Grade 10' => 'G10', 'Grade 11' => 'G11', 'Grade 12' => 'G12',
            default    => $g,
        };
    }

    private function defaultProgramLevel(string $g): string
    {
        if (in_array($g, ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'])) return 'Elementary';
        if (in_array($g, ['Grade 7','Grade 8','Grade 9','Grade 10'])) return 'Junior High School';
        return 'Senior High School';
    }
}