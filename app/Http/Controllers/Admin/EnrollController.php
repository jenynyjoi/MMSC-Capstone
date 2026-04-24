<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EnrollController extends Controller
{
    private const MAX_CAPACITY = 30;
    private const MIN_CAPACITY = 20;

    // ══════════════════════════════════════════════════════
    // ENROLL PAGE — Regular & Irregular tabs
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $tab       = $request->get('tab', 'regular');
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $baseQuery = StudentEnrollment::with('student')
            ->where('school_year', $schoolYear);

        // ── Regular Tab ────────────────────────────────────
        $regularQuery = (clone $baseQuery)->where('student_type', 'regular');
        if ($request->filled('grade'))  $regularQuery->where('grade_level', $request->grade);
        if ($request->filled('gender')) $regularQuery->where('gender', $request->gender);
        if ($request->filled('status')) $regularQuery->where('assignment_status', $request->status);
        if ($request->filled('search')) {
            $s = $request->search;
            $regularQuery->whereHas('student', fn($q) =>
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('student_id', 'like', "%$s%")
            );
        }
        $regularStudents = $regularQuery->latest()->paginate(10, ['*'], 'rp')->withQueryString();

        // ── Irregular Tab ──────────────────────────────────
        $irregularQuery = (clone $baseQuery)->where('student_type', 'irregular_shs');
        if ($request->filled('grade'))  $irregularQuery->where('grade_level', $request->grade);
        if ($request->filled('track'))  $irregularQuery->where('track', $request->track);
        if ($request->filled('strand')) $irregularQuery->where('strand', $request->strand);
        $irregularStudents = $irregularQuery->latest()->paginate(10, ['*'], 'ip')->withQueryString();

        // ── Stats ──────────────────────────────────────────
        $regularStats = [
            'total'     => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'regular'])->count(),
            'pending'   => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'regular','assignment_status'=>'pending'])->count(),
            'assigned'  => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'regular','assignment_status'=>'assigned'])->count(),
            'available_sections' => Section::where('school_year', $schoolYear)->where('section_status','active')->where('availability','!=','full')->count(),
        ];

        $irregularStats = [
            'total'            => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'irregular_shs'])->count(),
            'pending'          => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'irregular_shs','assignment_status'=>'pending'])->count(),
            'fully_scheduled'  => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'irregular_shs','assignment_status'=>'fully_scheduled'])->count(),
            'incomplete'       => StudentEnrollment::where(['school_year'=>$schoolYear,'student_type'=>'irregular_shs','assignment_status'=>'incomplete'])->count(),
        ];

        return view('admin.enrollment.enroll', compact(
            'tab', 'schoolYear',
            'regularStudents', 'regularStats',
            'irregularStudents', 'irregularStats'
        ));
    }

    // ══════════════════════════════════════════════════════
    // GET available sections for a student (AJAX)
    // ══════════════════════════════════════════════════════
    public function getAvailableSections(Request $request)
    {
        // Direct enroll path — no enrollment_id, use grade_level + school_year directly
        if (!$request->filled('enrollment_id')) {
            $request->validate([
                'grade_level' => 'required|string|max:50',
                'school_year' => 'required|string|max:20',
            ]);
            $sections = $this->fetchSections(
                $request->school_year,
                $request->grade_level,
                $request->track,
                $request->strand
            );
            return response()->json(['sections' => $sections]);
        }

        $request->validate(['enrollment_id' => 'required|exists:student_enrollment,id']);
        $enrollment = StudentEnrollment::with('student')->findOrFail($request->enrollment_id);

        $isEdit = $request->boolean('edit');
        $sections = $this->fetchSections(
            $enrollment->school_year,
            $enrollment->grade_level,
            $enrollment->track,
            $enrollment->strand,
            $isEdit ? $enrollment->section_id : null,
            $isEdit
        );

        return response()->json([
            'enrollment' => [
                'id'         => $enrollment->id,
                'student_id' => $enrollment->student->student_id ?? '—',
                'name'       => $enrollment->student->full_name ?? '—',
                'grade'      => $enrollment->grade_level,
                'type'       => $enrollment->student_type,
                'track'      => $enrollment->track,
                'strand'     => $enrollment->strand,
            ],
            'sections' => $sections,
        ]);
    }

    private function fetchSections(string $schoolYear, string $gradeLevel, ?string $track, ?string $strand, ?int $excludeSectionId = null, bool $availableOnly = false): \Illuminate\Support\Collection
    {
        return Section::where('school_year', $schoolYear)
            ->where('grade_level', $gradeLevel)
            ->where('section_status', 'active')
            ->where('is_subject_section', false)
            ->when($track,             fn($q) => $q->where('track',  $track))
            ->when($strand,            fn($q) => $q->where('strand', $strand))
            ->when($excludeSectionId,  fn($q) => $q->where('id', '!=', $excludeSectionId))
            ->when($availableOnly,     fn($q) => $q->where('availability', '!=', 'full'))
            ->orderBy('section_name')
            ->get()
            ->map(fn($s) => [
                'id'              => $s->id,
                'section_name'    => $s->section_name,
                'display_name'    => $s->display_name,
                'full_name'       => $s->full_name ?? $s->section_name,
                'room'            => $s->room ?? 'TBA',
                'adviser'         => $s->homeroom_adviser_name ?? 'TBA',
                'current'         => $s->current_enrollment,
                'capacity'        => $s->capacity,
                'available_slots' => $s->available_slots,
                'availability'    => $s->availability,
            ]);
    }

    // ══════════════════════════════════════════════════════
    // ASSIGN SECTION — individual
    // ══════════════════════════════════════════════════════
    public function assignSection(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:student_enrollment,id',
            'section_id'    => 'required|exists:sections,id',
        ]);

        $enrollment = StudentEnrollment::with('student')->findOrFail($request->enrollment_id);
        $section    = Section::findOrFail($request->section_id);

        if ($section->is_full) {
            return response()->json(['success' => false, 'message' => 'Section is already full.'], 422);
        }

        DB::transaction(function () use ($enrollment, $section, $request) {
            $oldSectionId   = $enrollment->section_id;
            $oldSectionName = $enrollment->section_name;

            // Update enrollment
            $enrollment->update([
                'section_id'        => $section->id,
                'section_name'      => $section->section_name,
                'assignment_status' => 'assigned',
                'assigned_at'       => now(),
                'assigned_by'       => auth()->id(),
            ]);

            // Increment section count
            $section->increment('current_enrollment');
            $section->updateAvailability();

            // If moved from another section, decrement old
            if ($oldSectionId && $oldSectionId !== $section->id) {
                $old = Section::find($oldSectionId);
                if ($old) { $old->decrement('current_enrollment'); $old->updateAvailability(); }
            }

            // Audit history
            DB::table('section_assignment_history')->insert([
                'enrollment_id'   => $enrollment->id,
                'student_id'      => $enrollment->student_id,
                'school_year'     => $enrollment->school_year,
                'old_section_id'  => $oldSectionId,
                'old_section_name'=> $oldSectionName,
                'new_section_id'  => $section->id,
                'new_section_name'=> $section->section_name,
                'assignment_type' => 'individual',
                'performed_by'    => auth()->id(),
                'performed_at'    => now(),
            ]);

            // Audit log
            DB::table('audit_log')->insert([
                'student_id'     => $enrollment->student_id,
                'enrollment_id'  => $enrollment->id,
                'section_id'     => $section->id,
                'action'         => 'section_assigned',
                'action_type'    => 'individual',
                'action_category'=> 'assignment',
                'new_value'      => json_encode(['section' => $section->section_name]),
                'performed_by'   => auth()->id(),
                'performed_at'   => now(),
            ]);

            // Queue notification email
            $this->queueAssignmentEmail($enrollment, $section, 'section_assigned');
        });

        return response()->json([
            'success' => true,
            'message' => $enrollment->student->full_name . ' has been assigned to ' . $section->section_name . '.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // BULK ASSIGN — preview + process
    // ══════════════════════════════════════════════════════
    public function bulkAssignPreview(Request $request)
    {
        $request->validate([
            'enrollment_ids'    => 'required|array',
            'enrollment_ids.*'  => 'integer|exists:student_enrollment,id',
        ]);

        $allSelected  = StudentEnrollment::whereIn('id', $request->enrollment_ids)->get();
        $enrollments  = $allSelected->where('assignment_status', 'pending');
        $alreadyCount = $allSelected->count() - $enrollments->count();

        if ($enrollments->isEmpty()) {
            return response()->json([
                'total_students'       => 0,
                'already_assigned_count' => $alreadyCount,
                'has_enough_slots'     => false,
                'mixed_grades'         => false,
                'grade_levels'         => [],
                'sections'             => [],
                'enrollment_ids'       => [],
                'grade_level'          => null,
                'school_year'          => null,
                'distribution_preview' => [],
            ]);
        }

        // Validate same grade level
        $gradeLevels = $enrollments->pluck('grade_level')->unique()->values();
        $mixedGrades = $gradeLevels->count() > 1;

        // Get available sections for this grade
        $firstEnrollment = $enrollments->first();
        $sections = Section::where('school_year', $firstEnrollment->school_year)
            ->where('grade_level', $firstEnrollment->grade_level)
            ->where('section_status', 'active')
            ->where('is_subject_section', false)
            ->orderBy('section_name')
            ->get()
            ->map(fn($s) => [
                'id'              => $s->id,
                'section_name'    => $s->section_name,
                'room'            => $s->room ?? 'TBA',
                'adviser'         => $s->homeroom_adviser_name ?? 'TBA',
                'current'         => $s->current_enrollment,
                'capacity'        => $s->capacity,
                'available_slots' => $s->available_slots,
                'is_full'         => $s->is_full,
            ]);

        $totalStudents     = $enrollments->count();
        $totalAvailSlots   = $sections->sum('available_slots');
        $hasEnoughSlots    = $totalAvailSlots >= $totalStudents;

        // Build distribute-across preview (round-robin fill)
        $distributionPreview = [];
        if ($hasEnoughSlots) {
            $remaining = $totalStudents;
            foreach ($sections->sortByDesc('available_slots') as $s) {
                if ($remaining <= 0) break;
                if ($s['available_slots'] <= 0) continue;
                $assign = min($s['available_slots'], $remaining);
                $distributionPreview[] = ['section' => $s['section_name'], 'count' => $assign];
                $remaining -= $assign;
            }
        }

        return response()->json([
            'total_students'        => $totalStudents,
            'already_assigned_count'=> $alreadyCount,
            'total_avail_slots'     => $totalAvailSlots,
            'has_enough_slots'      => $hasEnoughSlots,
            'mixed_grades'          => $mixedGrades,
            'grade_levels'          => $gradeLevels,
            'sections'              => $sections,
            'enrollment_ids'        => $enrollments->pluck('id')->values(),
            'grade_level'           => $firstEnrollment->grade_level,
            'school_year'           => $firstEnrollment->school_year,
            'distribution_preview'  => $distributionPreview,
        ]);
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'enrollment_ids'       => 'required|array',
            'enrollment_ids.*'     => 'integer|exists:student_enrollment,id',
            'distribution_method'  => 'required|in:single_section,distribute_across,split_sections,assign_available',
            'section_id'           => 'nullable|exists:sections,id',
        ]);

        $enrollments = StudentEnrollment::with('student')
            ->whereIn('id', $request->enrollment_ids)
            ->where('assignment_status', 'pending')
            ->get();

        $batchId = 'BULK-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $batchData = [
            'batch_id'                  => $batchId,
            'school_year'               => $enrollments->first()->school_year,
            'grade_level'               => $enrollments->first()->grade_level,
            'student_type'              => $enrollments->first()->student_type,
            'total_students_selected'   => $enrollments->count(),
            'distribution_method'       => $request->distribution_method,
            'selected_section_id'       => $request->section_id,
            'batch_status'              => 'processing',
            'performed_by'              => auth()->id(),
            'started_at'                => now(),
        ];

        DB::table('bulk_assignment_batches')->insert($batchData);

        $assigned = 0;
        $failed   = 0;

        switch ($request->distribution_method) {
            case 'single_section':
                [$assigned, $failed] = $this->assignToSingleSection($enrollments, $request->section_id, $batchId);
                break;

            case 'distribute_across':
                [$assigned, $failed] = $this->distributeAcrossSections($enrollments, $batchId);
                break;

            case 'split_sections':
                [$assigned, $failed] = $this->splitAcrossSections($enrollments, $batchId);
                break;

            case 'assign_available':
                [$assigned, $failed] = $this->assignAvailableOnly($enrollments, $batchId);
                break;
        }

        DB::table('bulk_assignment_batches')
            ->where('batch_id', $batchId)
            ->update([
                'total_students_assigned' => $assigned,
                'total_students_failed'   => $failed,
                'batch_status'            => 'completed',
                'completed_at'            => now(),
            ]);

        return response()->json([
            'success'  => true,
            'message'  => "{$assigned} student(s) assigned. {$failed} could not be assigned.",
            'assigned' => $assigned,
            'failed'   => $failed,
            'batch_id' => $batchId,
        ]);
    }

    // ── Single section assignment ───────────────────────
    private function assignToSingleSection($enrollments, int $sectionId, string $batchId): array
    {
        $section  = Section::findOrFail($sectionId);
        $assigned = 0;
        $failed   = 0;

        foreach ($enrollments as $enrollment) {
            if ($section->current_enrollment >= $section->capacity) {
                $failed++;
                DB::table('bulk_assignment_details')->insert([
                    'batch_id'          => $batchId,
                    'enrollment_id'     => $enrollment->id,
                    'student_id'        => $enrollment->student_id,
                    'student_name'      => $enrollment->student->full_name ?? '',
                    'assignment_status' => 'failed',
                    'failure_reason'    => 'Section is full',
                ]);
                continue;
            }

            DB::transaction(function () use ($enrollment, $section, $batchId, &$assigned) {
                $enrollment->update([
                    'section_id'        => $section->id,
                    'section_name'      => $section->section_name,
                    'assignment_status' => 'assigned',
                    'assigned_at'       => now(),
                    'assigned_by'       => auth()->id(),
                ]);
                $section->increment('current_enrollment');
                $section->updateAvailability();

                DB::table('bulk_assignment_details')->insert([
                    'batch_id'              => $batchId,
                    'enrollment_id'         => $enrollment->id,
                    'student_id'            => $enrollment->student_id,
                    'student_name'          => $enrollment->student->full_name ?? '',
                    'assigned_section_id'   => $section->id,
                    'assigned_section_name' => $section->section_name,
                    'assignment_status'     => 'assigned',
                    'assigned_at'           => now(),
                ]);

                $this->logAssignmentHistory($enrollment, null, null, $section, $batchId, 'bulk');
                $this->queueAssignmentEmail($enrollment, $section, 'section_assigned');
                $assigned++;
            });
        }

        return [$assigned, $failed];
    }

    // ── Distribute evenly across available sections ─────
    private function distributeAcrossSections($enrollments, string $batchId): array
    {
        $gradeLevel = $enrollments->first()->grade_level;
        $schoolYear = $enrollments->first()->school_year;

        $sections = Section::where('school_year', $schoolYear)
            ->where('grade_level', $gradeLevel)
            ->where('section_status', 'active')
            ->where('current_enrollment', '<', self::MAX_CAPACITY)
            ->orderBy('current_enrollment')
            ->get();

        $assigned = 0;
        $failed   = 0;
        $sIdx     = 0;

        foreach ($enrollments as $enrollment) {
            $section = null;
            for ($i = 0; $i < $sections->count(); $i++) {
                $s = $sections->get(($sIdx + $i) % $sections->count());
                if ($s->current_enrollment < $s->capacity) { $section = $s; break; }
            }

            if (!$section) { $failed++; continue; }

            DB::transaction(function () use ($enrollment, $section, $batchId, &$assigned) {
                $enrollment->update([
                    'section_id'        => $section->id,
                    'section_name'      => $section->section_name,
                    'assignment_status' => 'assigned',
                    'assigned_at'       => now(),
                    'assigned_by'       => auth()->id(),
                ]);
                $section->increment('current_enrollment');
                $section->refresh();
                $section->updateAvailability();
                DB::table('bulk_assignment_details')->insert([
                    'batch_id'              => $batchId,
                    'enrollment_id'         => $enrollment->id,
                    'student_id'            => $enrollment->student_id,
                    'student_name'          => $enrollment->student->full_name ?? '',
                    'assigned_section_id'   => $section->id,
                    'assigned_section_name' => $section->section_name,
                    'assignment_status'     => 'assigned',
                    'assigned_at'           => now(),
                ]);
                $this->queueAssignmentEmail($enrollment, $section, 'section_assigned');
                $assigned++;
            });

            $sIdx = ($sIdx + 1) % max(1, $sections->count());
        }

        return [$assigned, $failed];
    }

    // ── Split sections (balancing logic) ────────────────
    private function splitAcrossSections($enrollments, string $batchId): array
    {
        $gradeLevel = $enrollments->first()->grade_level;
        $schoolYear = $enrollments->first()->school_year;

        $sections = Section::where('school_year', $schoolYear)
            ->where('grade_level', $gradeLevel)
            ->where('section_status', 'active')
            ->orderBy('current_enrollment', 'desc')
            ->get();

        $assigned   = 0;
        $failed     = 0;
        $remaining  = collect($enrollments);

        foreach ($sections as $section) {
            if ($remaining->isEmpty()) break;
            $available = $section->capacity - $section->current_enrollment;
            if ($available <= 0) continue;

            // Bring section to minimum first if below min
            $toFill = ($section->current_enrollment < self::MIN_CAPACITY)
                ? max($available, self::MIN_CAPACITY - $section->current_enrollment)
                : $available;

            $toAssign = $remaining->splice(0, min($toFill, $remaining->count()));

            foreach ($toAssign as $enrollment) {
                if ($section->current_enrollment >= $section->capacity) { $failed++; continue; }
                DB::transaction(function () use ($enrollment, $section, $batchId, &$assigned) {
                    $enrollment->update([
                        'section_id'        => $section->id,
                        'section_name'      => $section->section_name,
                        'assignment_status' => 'assigned',
                        'assigned_at'       => now(),
                        'assigned_by'       => auth()->id(),
                    ]);
                    $section->increment('current_enrollment');
                    $section->refresh();
                    $section->updateAvailability();
                    $this->queueAssignmentEmail($enrollment, $section, 'section_assigned');
                    $assigned++;
                });
            }
        }

        $failed += $remaining->count();
        return [$assigned, $failed];
    }

    // ── Assign available only (leave rest pending) ──────
    private function assignAvailableOnly($enrollments, string $batchId): array
    {
        [$assigned, $failed] = $this->distributeAcrossSections($enrollments, $batchId);
        return [$assigned, $failed];
    }

    // ══════════════════════════════════════════════════════
    // EDIT SECTION (section change request)
    // ══════════════════════════════════════════════════════
    public function editSection(Request $request)
    {
        $request->validate([
            'enrollment_id'  => 'required|exists:student_enrollment,id',
            'new_section_id' => 'required|exists:sections,id',
            'reason'         => 'required|string|max:500',
        ]);

        $enrollment = StudentEnrollment::with('student')->findOrFail($request->enrollment_id);
        $newSection = Section::findOrFail($request->new_section_id);

        if ($newSection->is_full) {
            return response()->json(['success' => false, 'message' => 'Target section is full.'], 422);
        }

        DB::transaction(function () use ($enrollment, $newSection, $request) {
            $oldSectionId   = $enrollment->section_id;
            $oldSectionName = $enrollment->section_name;

            // Decrement old section
            if ($oldSectionId) {
                $old = Section::find($oldSectionId);
                if ($old) { $old->decrement('current_enrollment'); $old->updateAvailability(); }
            }

            // Update enrollment
            $enrollment->update([
                'section_id'        => $newSection->id,
                'section_name'      => $newSection->section_name,
                'assignment_status' => 'assigned',
                'assigned_at'       => now(),
                'assigned_by'       => auth()->id(),
            ]);

            $newSection->increment('current_enrollment');
            $newSection->updateAvailability();

            // Log history
            DB::table('section_assignment_history')->insert([
                'enrollment_id'    => $enrollment->id,
                'student_id'       => $enrollment->student_id,
                'school_year'      => $enrollment->school_year,
                'old_section_id'   => $oldSectionId,
                'old_section_name' => $oldSectionName,
                'new_section_id'   => $newSection->id,
                'new_section_name' => $newSection->section_name,
                'assignment_type'  => 'edit',
                'change_reason'    => $request->reason,
                'performed_by'     => auth()->id(),
                'performed_at'     => now(),
            ]);

            // Section change request record
            DB::table('section_change_requests')->insert([
                'enrollment_id'           => $enrollment->id,
                'student_id'              => $enrollment->student_id,
                'student_name'            => $enrollment->student->full_name ?? '',
                'current_section_id'      => $oldSectionId,
                'current_section_name'    => $oldSectionName,
                'requested_section_id'    => $newSection->id,
                'requested_section_name'  => $newSection->section_name,
                'request_type'            => 'admin_edit',
                'request_reason'          => $request->reason,
                'request_status'          => 'approved',
                'admin_remarks'           => 'Approved immediately by admin.',
                'requested_by'            => auth()->id(),
                'requested_at'            => now(),
                'reviewed_by'             => auth()->id(),
                'reviewed_at'             => now(),
            ]);

            $this->queueAssignmentEmail($enrollment, $newSection, 'section_changed');
        });

        return response()->json([
            'success' => true,
            'message' => $enrollment->student->full_name . ' moved to ' . $newSection->section_name . '.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // SECTION BALANCING PREVIEW (AJAX)
    // ══════════════════════════════════════════════════════
    public function balancingPreview(Request $request)
    {
        $request->validate([
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'integer|exists:student_enrollment,id',
        ]);

        $enrollments = StudentEnrollment::whereIn('id', $request->enrollment_ids)
            ->where('assignment_status', 'pending')
            ->get();
        $gradeLevel  = $enrollments->first()->grade_level;
        $schoolYear  = $enrollments->first()->school_year;
        $total       = $enrollments->count();

        $sections = Section::where('school_year', $schoolYear)
            ->where('grade_level', $gradeLevel)
            ->where('section_status', 'active')
            ->orderBy('current_enrollment', 'desc')
            ->get();

        $preview    = [];
        $remaining  = $total;

        foreach ($sections as $section) {
            $available = $section->capacity - $section->current_enrollment;
            if ($available <= 0) { $preview[] = ['section' => $section->section_name, 'current' => $section->current_enrollment, 'new' => $section->current_enrollment, 'added' => 0, 'status' => 'full']; continue; }

            $toAdd     = 0;
            $newEnroll = $section->current_enrollment;

            if ($section->current_enrollment < self::MIN_CAPACITY) {
                $toAdd     = min($available, max(self::MIN_CAPACITY - $section->current_enrollment, 0));
                $newEnroll = $section->current_enrollment + $toAdd;
            } else {
                $toAdd     = min($available, $remaining);
                $newEnroll = $section->current_enrollment + $toAdd;
            }

            $toAdd    = min($toAdd, $remaining);
            $remaining -= $toAdd;
            $newEnroll = $section->current_enrollment + $toAdd;

            $status = $newEnroll >= $section->capacity ? 'FULL'
                : ($newEnroll >= self::MIN_CAPACITY ? 'BALANCED' : 'BELOW MIN');

            $preview[] = [
                'section'  => $section->section_name,
                'current'  => $section->current_enrollment,
                'new'      => $newEnroll,
                'added'    => $toAdd,
                'capacity' => $section->capacity,
                'status'   => $status,
            ];
        }

        $needNewSection = $remaining > 0;

        if ($needNewSection) {
            $preview[] = [
                'section'  => 'New Section [Required]',
                'current'  => 0,
                'new'      => $remaining,
                'added'    => $remaining,
                'capacity' => self::MAX_CAPACITY,
                'status'   => $remaining < self::MIN_CAPACITY ? 'BELOW MIN' : 'BALANCED',
            ];
        }

        return response()->json([
            'preview'              => $preview,
            'total_students'       => $total,
            'students_assigned'    => $total - $remaining,
            'students_unassigned'  => $remaining,
            'needs_new_section'    => $needNewSection,
            'new_section_students' => $remaining,
            'warning'              => $remaining > 0 && $remaining < self::MIN_CAPACITY
                ? "New section will have {$remaining} students (minimum " . self::MIN_CAPACITY . " required). Recommended: add " . (self::MIN_CAPACITY - $remaining) . " more students or merge."
                : null,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // PROMOTE page
    // ══════════════════════════════════════════════════════
    // ══════════════════════════════════════════════════════
    // DIRECT ENROLL (bypass admission)
    // ══════════════════════════════════════════════════════
    public function directEnroll(Request $request)
    {
        $validated = $request->validate([
            'first_name'             => 'required|string|max:100',
            'middle_name'            => 'nullable|string|max:100',
            'last_name'              => 'required|string|max:100',
            'suffix'                 => 'nullable|string|max:20',
            'gender'                 => 'required|in:Male,Female',
            'date_of_birth'          => 'required|date',
            'mobile_number'          => 'nullable|string|max:20',
            'personal_email'         => 'nullable|email|max:255',
            'lrn'                    => 'nullable|string|max:30',
            'home_address'           => 'nullable|string|max:500',
            'guardian_name'          => 'required|string|max:255',
            'guardian_relationship'  => 'nullable|string|max:50',
            'guardian_contact'       => 'nullable|string|max:20',
            'guardian_email'         => 'nullable|email|max:255',
            'school_year'            => 'required|string|max:20',
            'program_level'          => 'required|string|max:50',
            'grade_level'            => 'required|string|max:50',
            'enrollment_type'        => 'required|in:new,transferee,return',
            'track'                  => 'nullable|string|max:100',
            'strand'                 => 'nullable|string|max:100',
            'shs_student_type'       => 'nullable|in:Regular,Irregular',
            'section_id'             => 'required|exists:sections,id',
        ]);

        $section = Section::findOrFail($validated['section_id']);

        if ($section->is_full) {
            return response()->json(['success' => false, 'message' => 'Selected section is already full.'], 422);
        }

        $portalCreated = false;
        $schoolEmailOut = null;
        $tempPasswordOut = null;
        $studentOut = null;

        DB::transaction(function () use ($validated, $section, &$portalCreated, &$schoolEmailOut, &$tempPasswordOut, &$studentOut) {
            // Create student record
            $studentId   = Student::generateStudentId();
            $schoolEmail = Student::generateSchoolEmail(
                $validated['first_name'],
                $validated['last_name'],
                $validated['middle_name'] ?? ''
            );

            $student = Student::create([
                'student_id'            => $studentId,
                'first_name'            => $validated['first_name'],
                'middle_name'           => $validated['middle_name'] ?? null,
                'last_name'             => $validated['last_name'],
                'suffix'                => $validated['suffix'] ?? null,
                'gender'                => $validated['gender'],
                'date_of_birth'         => $validated['date_of_birth'],
                'mobile_number'         => $validated['mobile_number'] ?? null,
                'personal_email'        => $validated['personal_email'] ?? null,
                'school_email'          => $schoolEmail,
                'lrn'                   => $validated['lrn'] ?? null,
                'home_address'          => $validated['home_address'] ?? null,
                'guardian_name'         => $validated['guardian_name'],
                'guardian_relationship' => $validated['guardian_relationship'] ?? null,
                'guardian_contact'      => $validated['guardian_contact'] ?? null,
                'guardian_email'        => $validated['guardian_email'] ?? null,
                'school_year'           => $validated['school_year'],
                'applied_level'         => $validated['program_level'],
                'grade_level'           => $validated['grade_level'],
                'track'                 => $validated['track'] ?? null,
                'strand'                => $validated['strand'] ?? null,
                'section_id'            => $section->id,
                'section_name'          => $section->section_name,
                'admission_type'        => match($validated['enrollment_type']) {
                    'transferee' => 'Transferee',
                    'return'     => 'Return',
                    default      => 'New',
                },
                'enrollment_date'  => now()->toDateString(),
                'enrolled_at'      => now(),
                'student_status'   => 'active',
                'academic_status'  => 'in_progress',
                'clearance_status' => 'pending',
                'enrollment_status'=> 'enrolled',
                'portal_account_created' => false,
            ]);

            // Create enrollment record (already assigned)
            $isShs       = $validated['program_level'] === 'Senior High School';
            $studentType = ($isShs && ($validated['shs_student_type'] ?? 'Regular') === 'Irregular')
                ? 'irregular_shs'
                : 'regular';

            $enrollment = StudentEnrollment::create([
                'student_id'        => $student->id,
                'school_year'       => $validated['school_year'],
                'grade_level'       => $validated['grade_level'],
                'grade_level_applied'=> $validated['grade_level'],
                'program_level'     => $validated['program_level'],
                'track'             => $validated['track'] ?? null,
                'strand'            => $validated['strand'] ?? null,
                'student_type'      => $studentType,
                'enrollment_type'   => $validated['enrollment_type'],
                'gender'            => $validated['gender'],
                'section_id'        => $section->id,
                'section_name'      => $section->section_name,
                'enrollment_date'   => now()->toDateString(),
                'enrollment_status' => 'enrolled',
                'assignment_status' => 'assigned',
                'assigned_at'       => now(),
                'assigned_by'       => auth()->id(),
            ]);

            // Update section count
            $section->increment('current_enrollment');
            $section->updateAvailability();

            // Audit log
            DB::table('section_assignment_history')->insert([
                'enrollment_id'    => $enrollment->id,
                'student_id'       => $student->id,
                'school_year'      => $validated['school_year'],
                'old_section_id'   => null,
                'old_section_name' => null,
                'new_section_id'   => $section->id,
                'new_section_name' => $section->section_name,
                'assignment_type'  => 'direct_enroll',
                'performed_by'     => auth()->id(),
                'performed_at'     => now(),
            ]);

            // Create portal account
            if (!User::where('email', $schoolEmail)->exists()) {
                $tempPassword = Str::random(10);
                $user = User::create([
                    'name'     => $student->full_name,
                    'email'    => $schoolEmail,
                    'password' => Hash::make($tempPassword),
                ]);
                $user->assignRole('student');

                $student->update([
                    'user_id'                => $user->id,
                    'portal_account_created' => true,
                    'account_created_at'     => now(),
                    'password_changed'       => false,
                ]);

                $portalCreated   = true;
                $schoolEmailOut  = $schoolEmail;
                $tempPasswordOut = $tempPassword;
            }

            $studentOut = $student;
        });

        // Send credentials email outside transaction (mail failures won't roll back DB)
        if ($portalCreated && $studentOut && !empty($validated['personal_email'])) {
            $this->sendDirectEnrollCredentialsEmail($studentOut, $schoolEmailOut, $tempPasswordOut, $validated['personal_email']);
        }

        return response()->json([
            'success' => true,
            'message' => $validated['first_name'] . ' ' . $validated['last_name'] . ' enrolled and assigned to ' . $section->section_name . '.',
        ]);
    }

    public function promote()
    {
        return view('admin.enrollment.promote');
    }

    private function sendDirectEnrollCredentialsEmail(Student $student, string $schoolEmail, string $tempPassword, string $personalEmail): void
    {
        $name = $student->first_name . ' ' . $student->last_name;
        $body = <<<TEXT
Dear {$name},

Your student portal account has been created.

STUDENT ACCOUNT DETAILS
==================================================
Student ID:           {$student->student_id}
School Email:         {$schoolEmail}
Temporary Password:   {$tempPassword}

LOGIN INSTRUCTIONS
==================================================
Portal URL:  https://portal.mmsc.edu.ph
Username:    {$schoolEmail}
Password:    {$tempPassword}

1. Go to the portal URL above
2. Log in using your school email and temporary password
3. You will be asked to change your password on first login

PASSWORD RESET
==================================================
If you forget your password, use "Forgot Password".
A reset link will be sent to: {$personalEmail}

For support: it@mmsc.edu.ph

Registrar's Office — My Messiah School of Cavite
TEXT;

        try {
            Mail::raw($body, function ($mail) use ($name, $schoolEmail, $personalEmail) {
                $mail->from('registrar@mmsc.edu.ph', 'MMSC Registrar')
                     ->to($personalEmail, $name)
                     ->subject("Your MMSC Student Portal Account — {$name}");
            });
        } catch (\Exception $e) {
            Log::error('DirectEnroll credentials email failed', [
                'student_id' => $student->student_id,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    // ══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════════════
    private function logAssignmentHistory($enrollment, $oldSectionId, $oldSectionName, $newSection, $batchId, $type): void
    {
        DB::table('section_assignment_history')->insert([
            'enrollment_id'    => $enrollment->id,
            'student_id'       => $enrollment->student_id,
            'school_year'      => $enrollment->school_year,
            'old_section_id'   => $oldSectionId,
            'old_section_name' => $oldSectionName,
            'new_section_id'   => $newSection->id,
            'new_section_name' => $newSection->section_name,
            'assignment_type'  => $type,
            'bulk_batch_id'    => $batchId,
            'performed_by'     => auth()->id(),
            'performed_at'     => now(),
        ]);
    }

    private function queueAssignmentEmail($enrollment, $section, $type): void
    {
        DB::table('assignment_notifications')->insert([
            'enrollment_id'    => $enrollment->id,
            'student_id'       => $enrollment->student_id,
            'section_id'       => $section->id,
            'notification_type'=> $type,
            'recipient_email'  => $enrollment->student->personal_email ?? null,
            'recipient_type'   => 'student',
            'email_subject'    => $type === 'section_assigned'
                ? 'Your Section Assignment — MMSC SY ' . $enrollment->school_year
                : 'Section Change Notice — MMSC SY ' . $enrollment->school_year,
            'email_body'       => $this->buildAssignmentEmailBody($enrollment, $section, $type),
            'status'           => 'pending',
            'queued_at'        => now(),
        ]);
        // TODO: dispatch actual mail job
    }

    private function buildAssignmentEmailBody($enrollment, $section, $type): string
    {
        $name    = $enrollment->student->full_name ?? 'Student';
        $sid     = $enrollment->student->student_id ?? '—';
        $grade   = $enrollment->grade_level;
        $section_name = $section->section_name;
        $adviser = $section->homeroom_adviser_name ?? 'TBA';
        $room    = $section->room ?? 'TBA';
        $sy      = $enrollment->school_year;

        $action = $type === 'section_assigned' ? 'assigned to' : 'moved to';

        return <<<TEXT
Dear {$name},

You have been {$action} Section {$section_name} for School Year {$sy}.

ENROLLMENT DETAILS
==================
Student Name: {$name}
Student ID:   {$sid}
Grade Level:  {$grade}
Section:      {$section_name}
Adviser:      {$adviser}
Room:         {$room}
School Year:  {$sy}

Please log in to the student portal to view your complete class schedule.

Registrar's Office — My Messiah School of Cavite
TEXT;
    }
}