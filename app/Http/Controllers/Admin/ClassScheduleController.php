<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassScheduleSetup;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use App\Models\SubjectSchedule;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassScheduleController extends Controller
{
    // ══════════════════════════════════════════════════════
    // MAIN PAGE
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear = $request->get('school_year', SchoolYear::activeName());

        // ── Schedule Status table: sections + completion ──
        $sectionsQuery = Section::with([
            'allocationConfig' => fn($q) => $q->where('school_year', $schoolYear),
        ])
        ->where('school_year', $schoolYear)
        ->when($request->filled('grade_section'), fn($q) => $q->where(
            DB::raw("CONCAT(grade_level,' ',COALESCE(strand,''),' ',section_name)"), 'like', '%' . $request->grade_section . '%'
        ))
        ->when($request->filled('sched_status'), function ($q) use ($request, $schoolYear) {
            // filter by completion status (complete / pending)
            $q->when($request->sched_status === 'complete', function ($sq) use ($schoolYear) {
                $sq->whereHas('allocationConfig', fn($c) => $c
                    ->where('school_year', $schoolYear)
                    ->whereRaw('total_subjects_allocated > 0')
                    ->whereColumn('total_subjects_allocated', '>=', 'total_subjects_required')
                );
            })->when($request->sched_status === 'pending', function ($sq) use ($schoolYear) {
                $sq->where(function ($inner) use ($schoolYear) {
                    $inner->doesntHave('allocationConfig')
                          ->orWhereHas('allocationConfig', fn($c) => $c
                              ->where('school_year', $schoolYear)
                              ->whereColumn('total_subjects_allocated', '<', 'total_subjects_required'));
                });
            });
        })
        ->orderBy('grade_level')
        ->orderBy('section_name');

        $sections = $sectionsQuery->paginate(10, ['*'], 'sec_page')->withQueryString();

        $secIds = $sections->pluck('id');

        // Live count of scheduled allocations per section
        $scheduledCounts = SubjectAllocation::whereIn('section_id', $secIds)
            ->where('school_year', $schoolYear)
            ->whereHas('schedules')
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        // Live total allocation count per section (bypasses stale cache)
        $allocCounts = SubjectAllocation::whereIn('section_id', $secIds)
            ->where('school_year', $schoolYear)
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        // ── Class List Schedule table ──
        // Grade pill → grade level mapping
        $gradeMap = [
            'G1'=>'Grade 1','G2'=>'Grade 2','G3'=>'Grade 3','G4'=>'Grade 4',
            'G5'=>'Grade 5','G6'=>'Grade 6','G7'=>'Grade 7','G8'=>'Grade 8',
            'G9'=>'Grade 9','G10'=>'Grade 10','G11'=>'Grade 11','G12'=>'Grade 12',
            'K'=>'Kinder',
        ];
        $listGradeLevel = $gradeMap[$request->list_grade] ?? null;

        $classListQuery = SubjectSchedule::with(['allocation' => fn($q) => $q->with(['section', 'teacher'])])
            ->whereHas('allocation', fn($q) => $q
                ->where('school_year', $schoolYear)
                ->when($request->filled('list_section'), fn($q2) => $q2->where('section_id', $request->list_section))
                ->when($request->filled('list_teacher'), fn($q2) => $q2->where('teacher_id', $request->list_teacher))
                ->when($listGradeLevel, fn($q2) => $q2->whereHas('section', fn($q3) => $q3->where('grade_level', $listGradeLevel)))
            )
            ->when($request->filled('list_day'), fn($q) => $q->where('day_of_week', $request->list_day))
            ->orderByRaw("FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('time_start');

        $classList = $classListQuery->paginate(15, ['*'], 'list_page')->withQueryString();

        // ── Support data ──
        $allSections = Section::where('school_year', $schoolYear)
            ->orderBy('grade_level')->orderBy('section_name')
            ->get(['id', 'grade_level', 'section_name', 'program_level', 'track', 'strand']);

        $teachers   = User::role('teacher')->orderBy('name')->get(['id', 'name']);
        $schoolYears = SchoolYear::orderBy('name', 'desc')->pluck('name');
        $setups     = ClassScheduleSetup::orderBy('level_type')->orderBy('grade_level')->get();

        // ── Stats ──
        $totalSections  = Section::where('school_year', $schoolYear)->count();
        $totalSchedules = SubjectSchedule::whereHas('allocation', fn($q) => $q->where('school_year', $schoolYear))->count();

        // Calculate complete sections (all allocations scheduled)
        $completeSections = 0;
        $pendingSections  = 0;
        $allScheduledCounts = SubjectAllocation::where('school_year', $schoolYear)
            ->whereHas('schedules')
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        Section::with(['allocationConfig' => fn($q) => $q->where('school_year', $schoolYear)])
            ->where('school_year', $schoolYear)
            ->get()
            ->each(function ($sec) use (&$completeSections, &$pendingSections, $allScheduledCounts) {
                $cfg  = $sec->allocationConfig;
                $req  = $cfg?->total_subjects_required ?? 0;
                $sch  = $allScheduledCounts[$sec->id] ?? 0;
                ($req > 0 && $sch >= $req) ? $completeSections++ : $pendingSections++;
            });

        return view('admin.schedule.class', compact(
            'sections', 'scheduledCounts', 'allocCounts', 'classList',
            'allSections', 'teachers', 'schoolYears', 'setups',
            'schoolYear', 'totalSections', 'totalSchedules',
            'completeSections', 'pendingSections'
        ));
    }

    // ══════════════════════════════════════════════════════
    // GRID DATA (AJAX)
    // ══════════════════════════════════════════════════════
    public function gridData(Request $request)
    {
        $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'school_year' => 'required|string',
        ]);

        $section    = Section::findOrFail($request->section_id);
        $schoolYear = $request->school_year;

        // Get class setup
        $setup = ClassScheduleSetup::forSection($section);
        $timeStart   = $setup?->time_start   ?? '07:00';
        $timeEnd     = $setup?->time_end     ?? '17:00';
        $slotDur     = $setup?->slot_duration ?? 60;
        $breaks      = $setup?->breaks        ?? [];
        $slots       = $setup ? $setup->generateSlots() : $this->defaultSlots($timeStart, $timeEnd, $slotDur);

        // All schedules for this section
        $schedules = SubjectSchedule::whereHas('allocation', fn($q) =>
            $q->where('section_id', $section->id)->where('school_year', $schoolYear)
        )->with(['allocation.teacher'])->get();

        // Build grid [slot_start][day] = cell|null
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $grid = [];

        foreach ($slots as $slot) {
            if ($slot['type'] === 'break') {
                $grid[$slot['start']] = ['_break' => true, 'label' => $slot['label'], 'end' => $slot['end']];
                continue;
            }
            $grid[$slot['start']] = [];
            foreach ($days as $day) {
                $match = $schedules->first(fn($s) =>
                    $s->day_of_week === $day &&
                    substr($s->time_start, 0, 5) >= $slot['start'] &&
                    substr($s->time_start, 0, 5) < $slot['end']
                );
                $grid[$slot['start']][$day] = $match ? [
                    'schedule_id'   => $match->id,
                    'allocation_id' => $match->allocation_id,
                    'subject'       => $match->allocation->subject_name,
                    'subject_code'  => $match->allocation->subject_code,
                    'teacher'       => $match->allocation->teacher?->name ?? 'TBA',
                    'teacher_id'    => $match->allocation->teacher_id,
                    'room'          => $match->room ?? '',
                    'time_start'    => substr($match->time_start, 0, 5),
                    'time_end'      => substr($match->time_end, 0, 5),
                ] : null;
            }
        }

        // Allocations for this section (for assign modal)
        $allocations = SubjectAllocation::with(['teacher', 'subject', 'schedules'])
            ->where('section_id', $section->id)
            ->where('school_year', $schoolYear)
            ->get()
            ->map(fn($a) => [
                'id'                => $a->id,
                'subject_name'      => $a->subject_name,
                'subject_code'      => $a->subject_code,
                'teacher_id'        => $a->teacher_id,
                'teacher_name'      => $a->teacher?->name ?? 'No teacher',
                'hours_per_meeting' => (float) ($a->subject?->hours_per_meeting ?? 0),
                'meetings_per_week' => (int)   ($a->subject?->meetings_per_week ?? 0),
                'hours_per_week'    => (float) ($a->subject?->hours_per_week    ?? ($a->hours_per_week ?? 0)),
                'scheduled_count'   => $a->schedules->count(),
            ]);

        return response()->json([
            'success' => true,
            'section' => [
                'id'            => $section->id,
                'section_name'  => $section->section_name,
                'grade_level'   => $section->grade_level,
                'display_name'  => $section->display_name,
                'program_level' => $section->program_level ?? $section->applied_level,
                'school_year'   => $schoolYear,
            ],
            'setup' => [
                'time_start'    => $timeStart,
                'time_end'      => $timeEnd,
                'slot_duration' => $slotDur,
                'breaks'        => $breaks,
            ],
            'slots'       => $slots,
            'days'        => $days,
            'grid'        => $grid,
            'allocations' => $allocations,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // STORE SCHEDULE (AJAX)
    // ══════════════════════════════════════════════════════
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'allocation_id' => 'required|exists:subject_allocation,id',
            'day_of_week'   => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'time_start'    => 'required|date_format:H:i',
            'time_end'      => 'required|date_format:H:i|after:time_start',
            'room'          => 'nullable|string|max:100',
        ]);

        $allocation = SubjectAllocation::with('teacher')->findOrFail($request->allocation_id);

        // Block scheduling subjects without a teacher assigned
        if (!$allocation->teacher_id) {
            return response()->json([
                'success' => false,
                'message' => 'This subject has no teacher assigned. Please assign a teacher before scheduling.',
            ], 422);
        }

        // Conflict check
        $conflicts = $this->detectConflicts($request->all(), $allocation, null);
        $errors    = array_filter($conflicts, fn($c) => $c['severity'] === 'error');

        if (!empty($errors)) {
            return response()->json([
                'success'   => false,
                'conflicts' => array_values($conflicts),
                'message'   => 'Schedule conflict detected. Please review.',
            ], 422);
        }

        $schedule = SubjectSchedule::create([
            'allocation_id' => $request->allocation_id,
            'day_of_week'   => $request->day_of_week,
            'time_start'    => $request->time_start,
            'time_end'      => $request->time_end,
            'room'          => $request->room,
        ]);

        return response()->json([
            'success'   => true,
            'conflicts' => array_values($conflicts), // warnings still returned
            'schedule'  => $schedule,
            'message'   => 'Schedule saved.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // UPDATE SCHEDULE (AJAX)
    // ══════════════════════════════════════════════════════
    public function updateSchedule(Request $request, int $id)
    {
        $request->validate([
            'allocation_id' => 'required|exists:subject_allocation,id',
            'day_of_week'   => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'time_start'    => 'required|date_format:H:i',
            'time_end'      => 'required|date_format:H:i|after:time_start',
            'room'          => 'nullable|string|max:100',
        ]);

        $schedule   = SubjectSchedule::findOrFail($id);
        $allocation = SubjectAllocation::findOrFail($request->allocation_id);
        $conflicts  = $this->detectConflicts($request->all(), $allocation, $id);
        $errors     = array_filter($conflicts, fn($c) => $c['severity'] === 'error');

        if (!empty($errors)) {
            return response()->json([
                'success'   => false,
                'conflicts' => array_values($conflicts),
                'message'   => 'Schedule conflict detected.',
            ], 422);
        }

        $schedule->update([
            'allocation_id' => $request->allocation_id,
            'day_of_week'   => $request->day_of_week,
            'time_start'    => $request->time_start,
            'time_end'      => $request->time_end,
            'room'          => $request->room,
        ]);

        return response()->json([
            'success'   => true,
            'conflicts' => array_values($conflicts),
            'message'   => 'Schedule updated.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // DELETE SCHEDULE (AJAX)
    // ══════════════════════════════════════════════════════
    public function deleteSchedule(int $id)
    {
        $schedule = SubjectSchedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Schedule removed.']);
    }

    // ══════════════════════════════════════════════════════
    // GET SETUPS (AJAX)
    // ══════════════════════════════════════════════════════
    public function getSetups()
    {
        $setups = ClassScheduleSetup::orderBy('level_type')->orderBy('grade_level')->get();
        return response()->json(['success' => true, 'setups' => $setups]);
    }

    // ══════════════════════════════════════════════════════
    // SAVE SETUP (AJAX) — create or update
    // ══════════════════════════════════════════════════════
    public function saveSetup(Request $request)
    {
        $request->validate([
            'level_type'     => 'required|string|max:100',
            'grade_level'    => 'nullable|string|max:50',
            'time_start'     => 'required|regex:/^\d{2}:\d{2}$/',
            'time_end'       => 'required|regex:/^\d{2}:\d{2}$/',
            'slot_duration'  => 'required|integer|in:30,60,90',
            'breaks'         => 'nullable|array',
            'breaks.*.start' => 'required_with:breaks|regex:/^\d{2}:\d{2}$/',
            'breaks.*.end'   => 'required_with:breaks|regex:/^\d{2}:\d{2}$/',
            'breaks.*.label' => 'nullable|string|max:50',
        ]);

        $data = [
            'level_type'    => $request->level_type,
            'grade_level'   => $request->grade_level ?: null,
            'time_start'    => $request->time_start,
            'time_end'      => $request->time_end,
            'slot_duration' => $request->slot_duration,
            'breaks'        => $request->breaks ?? [],
            'is_active'     => true,
            'created_by'    => auth()->id(),
        ];

        $setup = $request->filled('id')
            ? tap(ClassScheduleSetup::findOrFail($request->id))->update($data)
            : ClassScheduleSetup::create($data);

        return response()->json(['success' => true, 'setup' => $setup, 'message' => 'Class setup saved.']);
    }

    // ══════════════════════════════════════════════════════
    // DELETE SETUP (AJAX)
    // ══════════════════════════════════════════════════════
    public function deleteSetup(int $id)
    {
        ClassScheduleSetup::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Setup removed.']);
    }

    // ══════════════════════════════════════════════════════
    // AUTO-ASSIGN SCHEDULES (AJAX)
    // ══════════════════════════════════════════════════════
    public function autoAssign(Request $request)
    {
        $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'school_year' => 'required|string',
        ]);

        $section    = Section::findOrFail($request->section_id);
        $schoolYear = $request->school_year;

        // ── Setup ──────────────────────────────────────────────
        $setup     = \App\Models\ClassScheduleSetup::forSection($section);
        $timeStart = $setup?->time_start   ?? '07:00';
        $timeEnd   = $setup?->time_end     ?? '17:00';
        $slotDur   = $setup?->slot_duration ?? 60;
        $breaks    = $setup?->breaks        ?? [];
        $slots     = $setup
            ? $setup->generateSlots()
            : $this->defaultSlots($timeStart, $timeEnd, $slotDur);

        // Only usable (non-break) slot start times
        $classSlotStarts = array_values(array_filter($slots, fn($s) => ($s['type'] ?? 'class') !== 'break'));

        $days    = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $toMin   = fn(string $t): int => (int) explode(':', $t)[0] * 60 + (int) explode(':', $t)[1];
        $fmtTime = fn(int $m): string => sprintf('%02d:%02d', intdiv($m, 60), $m % 60);

        // Overlap check: does [s,e) overlap any block in $blocks?
        $overlaps = fn(string $s, string $e, array $blocks): bool =>
            collect($blocks)->contains(fn($b) => $s < $b['end'] && $e > $b['start']);

        // Is $start..$end fully within a break?
        $inBreak = function (string $s, string $e) use ($breaks): bool {
            foreach ($breaks as $brk) {
                if ($s < $brk['end'] && $e > $brk['start']) return true;
            }
            return false;
        };

        // ── Seed busy maps from existing schedules ─────────────
        $existingSchedules = SubjectSchedule::whereHas('allocation', fn($q) =>
            $q->where('section_id', $section->id)->where('school_year', $schoolYear)
        )->with('allocation')->get();

        $sectionBusy = [];  // [day] => [{start, end}]
        foreach ($days as $d) $sectionBusy[$d] = [];
        foreach ($existingSchedules as $sch) {
            $sectionBusy[$sch->day_of_week][] = [
                'start' => substr($sch->time_start, 0, 5),
                'end'   => substr($sch->time_end, 0, 5),
            ];
        }

        // Seed teacher busy map from ALL schedules this school year (any section)
        $allTeacherIds = SubjectAllocation::where('section_id', $section->id)
            ->where('school_year', $schoolYear)
            ->whereNotNull('teacher_id')
            ->pluck('teacher_id')->unique()->values();

        $teacherBusy = [];  // [teacher_id][day] => [{start, end}]
        foreach ($allTeacherIds as $tid) {
            foreach ($days as $d) $teacherBusy[$tid][$d] = [];
        }
        SubjectSchedule::whereHas('allocation', fn($q) =>
            $q->whereIn('teacher_id', $allTeacherIds)->where('school_year', $schoolYear)
        )->with('allocation')->get()->each(function ($sch) use (&$teacherBusy) {
            $tid = $sch->allocation->teacher_id;
            if (!$tid) return;
            $teacherBusy[$tid][$sch->day_of_week][] = [
                'start' => substr($sch->time_start, 0, 5),
                'end'   => substr($sch->time_end, 0, 5),
            ];
        });

        // Track which days each allocation is already scheduled on
        $subjectDays = [];  // [allocation_id] => [days]
        foreach ($existingSchedules as $sch) {
            $subjectDays[$sch->allocation_id][] = $sch->day_of_week;
        }

        // ── Load unfinished allocations ────────────────────────
        $allAllocs = SubjectAllocation::with(['subject', 'schedules'])
            ->where('section_id', $section->id)
            ->where('school_year', $schoolYear)
            ->get();

        $assigned = [];
        $skipped  = [];

        // Pre-reject teacher-less subjects — they cannot be auto-assigned
        foreach ($allAllocs->filter(fn($a) => !$a->teacher_id) as $noTeacher) {
            $skipped[] = [
                'subject' => $noTeacher->subject_name,
                'needed'  => (int) ($noTeacher->subject?->meetings_per_week ?? 0),
                'placed'  => 0,
                'reason'  => 'No teacher assigned. Assign a teacher before scheduling.',
            ];
        }

        $allocations = $allAllocs
            ->filter(fn($a) => $a->teacher_id && (int) ($a->subject?->meetings_per_week ?? 0) > 0)
            ->sortByDesc(fn($a) => (int) ($a->subject?->meetings_per_week ?? 0));

        // Track how many more meetings each allocation still needs
        $remaining = [];
        foreach ($allocations as $alloc) {
            $mpw = (int) ($alloc->subject?->meetings_per_week ?? 0);
            $remaining[$alloc->id] = max(0, $mpw - $alloc->schedules->count());
        }

        // Day-centric fill: visit every slot in every day in order.
        // For each slot, pick the highest-priority unplaced subject that fits
        // (no same subject twice on a day, no teacher/section conflict).
        DB::transaction(function () use (
            $allocations, $days, $classSlotStarts,
            $toMin, $fmtTime, $overlaps, $inBreak,
            &$sectionBusy, &$teacherBusy, &$subjectDays,
            &$assigned, &$remaining,
            $timeEnd
        ) {
            foreach ($days as $day) {
                // Sort allocations by remaining need (most urgent first) at start of each day
                $dayAllocs = $allocations->sortByDesc(fn($a) => $remaining[$a->id] ?? 0);

                foreach ($classSlotStarts as $slot) {
                    foreach ($dayAllocs as $alloc) {
                        // Skip if fully scheduled
                        if (($remaining[$alloc->id] ?? 0) <= 0) continue;

                        // No same subject on same day
                        if (in_array($day, $subjectDays[$alloc->id] ?? [])) continue;

                        $hpm    = (float) ($alloc->subject?->hours_per_meeting ?? 0);
                        $durMin = $hpm > 0 ? (int) round($hpm * 60) : null;
                        $tid    = $alloc->teacher_id;

                        $sMin = $toMin($slot['start']);
                        $sEnd = $durMin !== null ? $fmtTime($sMin + $durMin) : $slot['end'];

                        if ($sEnd > $timeEnd) continue;
                        if ($inBreak($slot['start'], $sEnd)) continue;
                        if ($overlaps($slot['start'], $sEnd, $sectionBusy[$day])) continue;
                        if ($tid && $overlaps($slot['start'], $sEnd, $teacherBusy[$tid][$day] ?? [])) continue;

                        // ✅ Place it
                        SubjectSchedule::create([
                            'allocation_id' => $alloc->id,
                            'day_of_week'   => $day,
                            'time_start'    => $slot['start'],
                            'time_end'      => $sEnd,
                            'room'          => null,
                        ]);

                        $sectionBusy[$day][] = ['start' => $slot['start'], 'end' => $sEnd];
                        if ($tid) {
                            if (!isset($teacherBusy[$tid][$day])) $teacherBusy[$tid][$day] = [];
                            $teacherBusy[$tid][$day][] = ['start' => $slot['start'], 'end' => $sEnd];
                        }
                        $subjectDays[$alloc->id][] = $day;
                        $remaining[$alloc->id]--;

                        $assigned[] = [
                            'subject' => $alloc->subject_name,
                            'day'     => $day,
                            'time'    => $slot['start'] . '–' . $sEnd,
                        ];
                        break; // slot filled — move to the next slot
                    }
                }
            }
        });

        // Collect allocations that still have unplaced meetings
        foreach ($allocations as $alloc) {
            $stillNeeded = $remaining[$alloc->id] ?? 0;
            if ($stillNeeded > 0) {
                $mpw         = (int) ($alloc->subject?->meetings_per_week ?? 0);
                $placedCount = ($mpw - $alloc->schedules->count()) - $stillNeeded;
                $skipped[] = [
                    'subject' => $alloc->subject_name,
                    'needed'  => $stillNeeded,
                    'placed'  => $placedCount,
                    'reason'  => $placedCount === 0
                        ? 'No conflict-free slot found for any weekday.'
                        : "Only {$placedCount}/{$mpw} meetings placed — remaining slots have conflicts.",
                ];
            }
        }

        $total = count($assigned);
        $msg   = $total > 0
            ? "{$total} schedule slot(s) auto-assigned successfully."
            : 'No new slots could be assigned — all subjects may already be fully scheduled or no free slots exist.';
        if (count($skipped) > 0) {
            $msg .= ' ' . count($skipped) . ' subject(s) could not be fully placed.';
        }

        return response()->json([
            'success'  => $total > 0 || count($skipped) === 0,
            'assigned' => $assigned,
            'skipped'  => $skipped,
            'message'  => $msg,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // PRIVATE: CONFLICT DETECTION
    // ══════════════════════════════════════════════════════
    private function detectConflicts(array $data, SubjectAllocation $allocation, ?int $excludeScheduleId): array
    {
        $conflicts   = [];
        $day         = $data['day_of_week'];
        $start       = $data['time_start'];
        $end         = $data['time_end'];
        $sectionId   = $allocation->section_id;
        $teacherId   = $allocation->teacher_id;
        $schoolYear  = $allocation->school_year;

        // Base overlap query: schedules on same day that overlap the time window
        $overlapBase = fn() => SubjectSchedule::where('day_of_week', $day)
            ->where(fn($q) => $q
                ->where(fn($q2) => $q2->where('time_start', '<', $end)->where('time_end', '>', $start))
            )
            ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId));

        // 1. Section conflict
        $secConflict = $overlapBase()
            ->whereHas('allocation', fn($q) => $q->where('section_id', $sectionId)->where('school_year', $schoolYear)
                ->when($excludeScheduleId === null, fn() => true) // always check
            )
            ->with('allocation')
            ->first();

        if ($secConflict) {
            $conflicts[] = [
                'type'     => 'section',
                'severity' => 'error',
                'message'  => 'This section already has "' . $secConflict->allocation->subject_name . '" at ' . $day . ' ' . substr($secConflict->time_start, 0, 5) . '–' . substr($secConflict->time_end, 0, 5) . '.',
                'fix'      => 'Choose a different time slot or remove the conflicting class.',
            ];
        }

        // 2. Teacher conflict
        if ($teacherId) {
            $tchConflict = $overlapBase()
                ->whereHas('allocation', fn($q) => $q->where('teacher_id', $teacherId)->where('school_year', $schoolYear))
                ->with(['allocation.section'])
                ->first();

            if ($tchConflict && $tchConflict->allocation->section_id !== $sectionId) {
                // Collect all busy times for this teacher on this day to show in suggestion
                $busyTimes = SubjectSchedule::where('day_of_week', $day)
                    ->whereHas('allocation', fn($q) => $q->where('teacher_id', $teacherId)->where('school_year', $schoolYear))
                    ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
                    ->get()
                    ->map(fn($s) => substr($s->time_start, 0, 5) . '–' . substr($s->time_end, 0, 5))
                    ->join(', ');

                $conflicts[] = [
                    'type'     => 'teacher',
                    'severity' => 'error',
                    'message'  => 'Teacher is already teaching "' . ($tchConflict->allocation->subject_name ?? '?') . '" in section "' . ($tchConflict->allocation->section->section_name ?? '?') . '" at ' . substr($tchConflict->time_start, 0, 5) . '–' . substr($tchConflict->time_end, 0, 5) . ' on ' . $day . '.',
                    'fix'      => 'Teacher is busy on ' . $day . ' at: ' . $busyTimes . '. Choose a different time slot, a different day, or assign another teacher.',
                ];
            }
        }

        // 3. Room conflict (warning only)
        if (!empty($data['room'])) {
            $room = trim($data['room']);
            $roomConflict = $overlapBase()
                ->where('room', $room)
                ->first();

            if ($roomConflict) {
                $conflicts[] = [
                    'type'     => 'room',
                    'severity' => 'warning',
                    'message'  => 'Room "' . $room . '" appears to be occupied at this time.',
                    'fix'      => 'Verify room usage or assign a different room.',
                ];
            }
        }

        // 4. Time window / break check
        $setup = ClassScheduleSetup::forSection(Section::find($sectionId));
        if ($setup) {
            if ($start < $setup->time_start || $end > $setup->time_end) {
                $conflicts[] = [
                    'type'     => 'time_window',
                    'severity' => 'warning',
                    'message'  => 'Time ' . $start . '–' . $end . ' is outside allowed hours (' . $setup->time_start . '–' . $setup->time_end . ') for this level.',
                    'fix'      => 'Adjust the time or update the class setup for this level.',
                ];
            }

            foreach ($setup->breaks ?? [] as $brk) {
                if ($start < $brk['end'] && $end > $brk['start']) {
                    $conflicts[] = [
                        'type'     => 'break',
                        'severity' => 'warning',
                        'message'  => 'This slot overlaps with "' . ($brk['label'] ?? 'Break') . '" (' . $brk['start'] . '–' . $brk['end'] . ').',
                        'fix'      => 'Schedule before or after the break period.',
                    ];
                }
            }
        }

        // 5. Meetings-per-week limit
        $mpw = (int) ($allocation->subject?->meetings_per_week ?? 0);
        if ($mpw > 0) {
            $existingCount = SubjectSchedule::where('allocation_id', $allocation->id)
                ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
                ->count();
            if ($existingCount >= $mpw) {
                $conflicts[] = [
                    'type'     => 'meetings_limit',
                    'severity' => 'error',
                    'message'  => '"' . $allocation->subject_name . '" is limited to ' . $mpw . ' meeting(s) per week. Already scheduled ' . $existingCount . ' time(s).',
                    'fix'      => 'Remove an existing schedule for this subject first, or increase its Meetings/Wk in Subject Management.',
                ];
            }
        }

        // 6. Same subject already scheduled on this day
        $sameDayConflict = SubjectSchedule::where('allocation_id', $allocation->id)
            ->where('day_of_week', $day)
            ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
            ->first();

        if ($sameDayConflict) {
            $scheduledDays = SubjectSchedule::where('allocation_id', $allocation->id)
                ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
                ->pluck('day_of_week')->toArray();
            $allDays  = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $freeDays = array_values(array_diff($allDays, $scheduledDays));
            $conflicts[] = [
                'type'     => 'same_day_subject',
                'severity' => 'error',
                'message'  => '"' . $allocation->subject_name . '" is already scheduled on ' . $day . '.',
                'fix'      => !empty($freeDays)
                    ? 'Available days to schedule this subject: ' . implode(', ', $freeDays) . '.'
                    : 'All weekdays are already occupied for this subject.',
            ];
        }

        // 7. Duration mismatch (hours_per_meeting)
        $hpm = (float) ($allocation->subject?->hours_per_meeting ?? 0);
        if ($hpm > 0) {
            $toMin = fn(string $t): int => (int) explode(':', $t)[0] * 60 + (int) explode(':', $t)[1];
            $actualMin   = $toMin($end) - $toMin($start);
            $expectedMin = (int) round($hpm * 60);
            if (abs($actualMin - $expectedMin) > 5) {
                $expEnd = sprintf('%02d:%02d',
                    intdiv($toMin($start) + $expectedMin, 60),
                    ($toMin($start) + $expectedMin) % 60
                );
                $conflicts[] = [
                    'type'     => 'duration',
                    'severity' => 'warning',
                    'message'  => '"' . $allocation->subject_name . '" requires ' . $hpm . 'h/meeting (' . $expectedMin . ' min), but this slot is ' . $actualMin . ' min.',
                    'fix'      => 'Set end time to ' . $expEnd . ' to match the required duration.',
                ];
            }
        }

        return $conflicts;
    }

    // ── Helper: default slots when no setup found ──────────
    private function defaultSlots(string $start, string $end, int $dur): array
    {
        $slots   = [];
        $current = strtotime($start);
        $endTs   = strtotime($end);
        while ($current < $endTs) {
            $next    = min($current + $dur * 60, $endTs);
            $slots[] = ['start' => date('H:i', $current), 'end' => date('H:i', $next), 'type' => 'class', 'label' => ''];
            $current = $next;
        }
        return $slots;
    }
}
