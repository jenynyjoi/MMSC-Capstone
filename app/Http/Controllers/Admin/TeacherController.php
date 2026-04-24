<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectAllocation;
use App\Models\SubjectSchedule;
use App\Models\TeacherLoad;
use App\Models\TeacherProfile;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    // ══════════════════════════════════════════════════════
    // MAIN PAGE — Teacher List + Teaching Load tabs
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        // ── Teacher List ────────────────────────────────────
        $teacherQuery = TeacherProfile::with('user')
            ->where(function ($q) use ($schoolYear) {
                $q->where('school_year', $schoolYear)->orWhereNull('school_year');
            })
            ->when($request->specialization, fn($q) =>
                $q->whereJsonContains('specializations', $request->specialization))
            ->when($request->department, fn($q) =>
                $q->where('department', $request->department))
            ->when($request->grade_level, fn($q) =>
                $q->whereJsonContains('grade_levels', $request->grade_level))
            ->when($request->status, fn($q) =>
                $q->where('status', strtolower($request->status)));

        $teachers = $teacherQuery->paginate(10, ['*'], 'teacher_page')->withQueryString();

        // Attach computed load status
        $teachers->through(function ($tp) use ($schoolYear) {
            $load = TeacherLoad::where('teacher_id', $tp->user_id)
                ->where('school_year', $schoolYear)->first();
            $tp->_load           = $load;
            $tp->current_weekly  = $load?->current_weekly_hours ?? 0;
            $tp->max_weekly      = $load?->max_weekly_hours ?? 0;
            $tp->remaining_hours = max(0, ($load?->max_weekly_hours ?? 0) - ($load?->current_weekly_hours ?? 0));
            return $tp;
        });

        // Stats
        $allProfiles = TeacherProfile::where(function ($q) use ($schoolYear) {
            $q->where('school_year', $schoolYear)->orWhereNull('school_year');
        });
        $stats = [
            'total'    => $allProfiles->count(),
            'active'   => (clone $allProfiles)->where('status', 'active')->count(),
            'inactive' => (clone $allProfiles)->whereIn('status', ['inactive','resigned','on_leave'])->count(),
        ];

        // ── Teaching Load ────────────────────────────────────
        $loads = TeacherLoad::with('teacher')
            ->where('school_year', $schoolYear)
            ->paginate(10, ['*'], 'load_page')->withQueryString();

        $loads->through(function ($l) {
            $pct = $l->max_weekly_hours > 0
                ? round(($l->current_weekly_hours / $l->max_weekly_hours) * 100)
                : 0;
            $l->pct         = $pct;
            $l->load_status = $pct >= 100 ? 'overloaded' : ($pct >= 60 ? 'loaded' : 'underloaded');
            $l->remaining   = max(0, $l->max_weekly_hours - $l->current_weekly_hours);
            return $l;
        });

        $loadStats = [
            'total'       => TeacherLoad::where('school_year', $schoolYear)->count(),
            'underloaded' => TeacherLoad::where('school_year', $schoolYear)
                ->whereRaw('current_weekly_hours / NULLIF(max_weekly_hours,0) < 0.6')->count(),
            'loaded'      => TeacherLoad::where('school_year', $schoolYear)
                ->whereRaw('current_weekly_hours / NULLIF(max_weekly_hours,0) BETWEEN 0.6 AND 0.999')->count(),
            'overloaded'  => TeacherLoad::where('school_year', $schoolYear)
                ->whereRaw('current_weekly_hours >= max_weekly_hours')->count(),
        ];

        $unassignedSections = Section::where('school_year', $schoolYear)
            ->where('is_subject_section', false)
            ->whereNull('homeroom_adviser_id')
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get(['id', 'grade_level', 'section_name', 'strand', 'full_name'])
            ->map(fn($s) => (object)[
                'id'           => $s->id,
                'display_name' => $s->full_name ?: ($s->grade_level . ' - ' . $s->section_name),
            ]);

        // ── Assign Teacher tab data ───────────────────────────
        $allocQuery = Section::where('school_year', $schoolYear)
            ->when($request->alloc_grade, fn($q) => $q->where('grade_level', $request->alloc_grade))
            ->when($request->alloc_search, fn($q) => $q->where(function ($sq) use ($request) {
                $sq->where('section_name', 'like', '%'.$request->alloc_search.'%')
                   ->orWhere('grade_level',   'like', '%'.$request->alloc_search.'%');
            }))
            ->orderBy('grade_level')->orderBy('section_name');
        $allocations = $allocQuery->paginate(10, ['*'], 'alloc_page')->withQueryString();

        $allocCounts = SubjectAllocation::where('school_year', $schoolYear)
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        $teacherCounts = SubjectAllocation::where('school_year', $schoolYear)
            ->whereNotNull('teacher_id')
            ->select('section_id', DB::raw('count(*) as cnt'))
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id');

        $allocGradeLevels = Section::where('school_year', $schoolYear)
            ->distinct()->orderBy('grade_level')->pluck('grade_level');

        return view('admin.teachers', compact(
            'teachers', 'stats', 'loads', 'loadStats', 'schoolYear', 'unassignedSections',
            'allocations', 'allocCounts', 'teacherCounts', 'allocGradeLevels'
        ));
    }

    // ══════════════════════════════════════════════════════
    // STORE — create teacher user + profile
    // ══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'middle_name'       => 'nullable|string|max:100',
            'contact_number'    => 'nullable|string|max:30',
            'personal_email'    => 'nullable|email|max:255',
            'academic_rank'     => 'nullable|string|max:100',
            'employment_status' => 'nullable|string|max:50',
            'status'            => 'nullable|string|max:50',
            'department'        => 'nullable|string|max:100',
            'specializations'   => 'nullable|array',
            'grade_levels'      => 'nullable|array',
            'advisory_section_id' => 'nullable|integer|exists:sections,id',
            'weekly_days_available' => 'nullable|integer|min:1|max:7',
            'available_from'    => 'nullable|date_format:H:i',
            'available_to'      => 'nullable|date_format:H:i',
            'lunch_start'       => 'nullable|date_format:H:i',
            'lunch_end'         => 'nullable|date_format:H:i',
            'max_weekly_hours'  => 'nullable|numeric|min:1',
        ]);

        $firstName  = trim($request->first_name);
        $lastName   = trim($request->last_name);
        $fullName   = $firstName . ' ' . $lastName;
        $instEmail  = $this->generateInstitutionalEmail($firstName, $lastName);
        $tempPass   = Str::random(10);
        $personalEmail = $request->personal_email;

        $createdProfile = null;

        DB::transaction(function () use ($request, $firstName, $lastName, $fullName, $instEmail, $tempPass, &$createdProfile) {
            // Create user account with auto-generated institutional email
            $user = \App\Models\User::create([
                'name'     => $fullName,
                'email'    => $instEmail,
                'password' => Hash::make($tempPass),
            ]);
            $user->assignRole('teacher');

            // Generate teacher_id_code
            $count = TeacherProfile::count() + 1;
            $code  = 'TCH-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Resolve advisory class label from section
            $advisoryClass = null;
            if ($request->advisory_section_id) {
                $section = Section::find($request->advisory_section_id);
                if ($section) {
                    $advisoryClass = $section->display_name;
                    $section->update([
                        'homeroom_adviser_id'   => $user->id,
                        'homeroom_adviser_name' => $fullName,
                        'adviser_status'        => 'assigned',
                    ]);
                }
            }

            // Create profile
            $createdProfile = TeacherProfile::create([
                'user_id'               => $user->id,
                'teacher_id_code'       => $code,
                'first_name'            => $firstName,
                'last_name'             => $lastName,
                'middle_name'           => $request->middle_name,
                'contact_number'        => $request->contact_number,
                'personal_email'        => $request->personal_email,
                'academic_rank'         => $request->academic_rank,
                'employment_status'     => $request->employment_status,
                'status'                => $request->status ?? 'active',
                'department'            => $request->department,
                'specializations'       => $request->specializations ?? [],
                'grade_levels'          => $request->grade_levels ?? [],
                'advisory_class'        => $advisoryClass,
                'school_year'           => $request->school_year ?? \App\Models\SchoolYear::activeName(),
                'weekly_days_available' => $request->weekly_days_available ?? 5,
                'available_from'        => $request->available_from,
                'available_to'          => $request->available_to,
                'lunch_start'           => $request->lunch_start,
                'lunch_end'             => $request->lunch_end,
            ]);

            // Create initial teacher_load record
            TeacherLoad::create([
                'teacher_id'           => $user->id,
                'school_year'          => $request->school_year ?? \App\Models\SchoolYear::activeName(),
                'max_weekly_hours'     => $request->max_weekly_hours ?? 40,
                'current_weekly_hours' => 0,
            ]);
        });

        // Send credentials to personal email outside transaction
        if ($personalEmail && $createdProfile) {
            $this->sendTeacherCredentialsEmail($fullName, $instEmail, $tempPass, $personalEmail);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Teacher added successfully.' . ($personalEmail ? ' Credentials sent to ' . $personalEmail . '.' : ''),
            'inst_email' => $instEmail,
        ]);
    }

    private function generateInstitutionalEmail(string $firstName, string $lastName): string
    {
        $base  = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName))
               . '.'
               . strtolower(preg_replace('/[^a-zA-Z]/', '', $lastName))
               . '@mmsc.edu.ph';

        if (!\App\Models\User::where('email', $base)->exists()) {
            return $base;
        }

        // Handle duplicates: append incrementing number
        $counter = 2;
        $prefix  = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName))
                 . '.'
                 . strtolower(preg_replace('/[^a-zA-Z]/', '', $lastName));
        do {
            $candidate = $prefix . $counter . '@mmsc.edu.ph';
            $counter++;
        } while (\App\Models\User::where('email', $candidate)->exists());

        return $candidate;
    }

    private function sendTeacherCredentialsEmail(string $name, string $instEmail, string $tempPass, string $personalEmail): void
    {
        $body = <<<TEXT
Dear {$name},

Your MMSC Teacher portal account has been created.

TEACHER ACCOUNT DETAILS
==================================================
School Email:         {$instEmail}
Temporary Password:   {$tempPass}

LOGIN INSTRUCTIONS
==================================================
Portal URL:  https://portal.mmsc.edu.ph
Username:    {$instEmail}
Password:    {$tempPass}

1. Go to the portal URL above
2. Log in using your school email and temporary password
3. You will be asked to change your password on first login

PASSWORD RESET
==================================================
If you forget your password, use "Forgot Password".
A reset link will be sent to this personal email: {$personalEmail}

For support: it@mmsc.edu.ph

Human Resources — My Messiah School of Cavite
TEXT;

        try {
            Mail::raw($body, function ($mail) use ($name, $instEmail, $personalEmail) {
                $mail->from('hr@mmsc.edu.ph', 'MMSC Human Resources')
                     ->to($personalEmail, $name)
                     ->subject("Your MMSC Teacher Portal Account — {$name}");
            });
        } catch (\Exception $e) {
            Log::error('Teacher credentials email failed', [
                'inst_email'     => $instEmail,
                'personal_email' => $personalEmail,
                'error'          => $e->getMessage(),
            ]);
        }
    }

    // ══════════════════════════════════════════════════════
    // SHOW — teacher detail (AJAX)
    // ══════════════════════════════════════════════════════
    public function show(int $id)
    {
        $profile = TeacherProfile::with('user')->findOrFail($id);
        $load    = TeacherLoad::where('teacher_id', $profile->user_id)
            ->where('school_year', $profile->school_year ?? \App\Models\SchoolYear::activeName())
            ->first();

        $advisorySection = Section::where('homeroom_adviser_id', $profile->user_id)
            ->where('school_year', $profile->school_year ?? \App\Models\SchoolYear::activeName())
            ->first(['id', 'grade_level', 'section_name']);

        return response()->json([
            'success' => true,
            'teacher' => [
                'id'                    => $profile->id,
                'user_id'               => $profile->user_id,
                'teacher_id_code'       => $profile->teacher_id_code,
                'name'                  => $profile->user?->name,
                'email'                 => $profile->user?->email,
                'first_name'            => $profile->first_name,
                'last_name'             => $profile->last_name,
                'middle_name'           => $profile->middle_name,
                'contact_number'        => $profile->contact_number,
                'personal_email'        => $profile->personal_email,
                'academic_rank'         => $profile->academic_rank,
                'employment_status'     => $profile->employment_status,
                'status'                => $profile->status,
                'department'            => $profile->department,
                'specializations'       => $profile->specializations ?? [],
                'grade_levels'          => $profile->grade_levels ?? [],
                'advisory_class'        => $profile->advisory_class,
                'advisory_section_id'   => $advisorySection?->id,
                'weekly_days_available' => $profile->weekly_days_available,
                'available_from'        => $profile->available_from,
                'available_to'          => $profile->available_to,
                'lunch_start'           => $profile->lunch_start,
                'lunch_end'             => $profile->lunch_end,
                'max_weekly_hours'      => $load?->max_weekly_hours ?? 40,
                'current_weekly_hours'  => $load?->current_weekly_hours ?? 0,
                'remaining_hours'       => max(0, ($load?->max_weekly_hours ?? 40) - ($load?->current_weekly_hours ?? 0)),
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════
    // UPDATE
    // ══════════════════════════════════════════════════════
    public function update(Request $request, int $id)
    {
        $profile = TeacherProfile::findOrFail($id);

        $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'middle_name'       => 'nullable|string|max:100',
            'contact_number'    => 'nullable|string|max:30',
            'personal_email'    => 'nullable|email|max:255',
            'academic_rank'     => 'nullable|string|max:100',
            'employment_status' => 'nullable|string|max:50',
            'status'            => 'nullable|string|max:50',
            'department'        => 'nullable|string|max:100',
            'specializations'   => 'nullable|array',
            'grade_levels'      => 'nullable|array',
            'advisory_section_id' => 'nullable|integer|exists:sections,id',
            'max_weekly_hours'  => 'nullable|numeric|min:1',
        ]);

        $firstName = trim($request->first_name);
        $lastName  = trim($request->last_name);
        $fullName  = $firstName . ' ' . $lastName;

        DB::transaction(function () use ($request, $profile, $firstName, $lastName, $fullName) {
            $profile->user->update([
                'name'  => $fullName,
            ]);

            // Sync advisory section
            $advisoryClass = $profile->advisory_class;
            $newSectionId  = $request->advisory_section_id ? (int)$request->advisory_section_id : null;

            // Find current section assigned to this teacher
            $currentSection = Section::where('homeroom_adviser_id', $profile->user_id)
                ->where('school_year', $profile->school_year ?? \App\Models\SchoolYear::activeName())
                ->first();

            if ($currentSection?->id !== $newSectionId) {
                // Clear old section
                if ($currentSection) {
                    $currentSection->update([
                        'homeroom_adviser_id'   => null,
                        'homeroom_adviser_name' => null,
                        'adviser_status'        => null,
                    ]);
                }
                // Assign new section
                if ($newSectionId) {
                    $newSection = Section::find($newSectionId);
                    if ($newSection) {
                        $newSection->update([
                            'homeroom_adviser_id'   => $profile->user_id,
                            'homeroom_adviser_name' => $request->name,
                            'adviser_status'        => 'assigned',
                        ]);
                        $advisoryClass = $newSection->display_name;
                    }
                } else {
                    $advisoryClass = null;
                }
            }

            $profile->update([
                'first_name'            => $firstName,
                'last_name'             => $lastName,
                'middle_name'           => $request->middle_name,
                'contact_number'        => $request->contact_number,
                'personal_email'        => $request->personal_email,
                'academic_rank'         => $request->academic_rank,
                'employment_status'     => $request->employment_status,
                'status'                => $request->status ?? $profile->status,
                'department'            => $request->department,
                'specializations'       => $request->specializations ?? [],
                'grade_levels'          => $request->grade_levels ?? [],
                'advisory_class'        => $advisoryClass,
                'weekly_days_available' => $request->weekly_days_available ?? $profile->weekly_days_available,
                'available_from'        => $request->available_from,
                'available_to'          => $request->available_to,
                'lunch_start'           => $request->lunch_start,
                'lunch_end'             => $request->lunch_end,
            ]);

            // Update max weekly hours in load
            if ($request->max_weekly_hours) {
                TeacherLoad::where('teacher_id', $profile->user_id)
                    ->where('school_year', $profile->school_year ?? \App\Models\SchoolYear::activeName())
                    ->update(['max_weekly_hours' => $request->max_weekly_hours]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Teacher updated successfully.']);
    }

    // ══════════════════════════════════════════════════════
    // DESTROY
    // ══════════════════════════════════════════════════════
    public function destroy(int $id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $allocCount = SubjectAllocation::where('teacher_id', $profile->user_id)->count();

        if ($allocCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete: teacher has ' . $allocCount . ' active subject allocation(s). Remove allocations first.',
            ], 422);
        }

        $profile->update(['status' => 'inactive']);
        return response()->json(['success' => true, 'message' => 'Teacher deactivated successfully.']);
    }

    // ══════════════════════════════════════════════════════
    // SCHEDULE — Section Schedule (Phase 4.1)
    // ══════════════════════════════════════════════════════
    public function sectionSchedule(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $sectionId  = $request->get('section_id');
        $sections   = Section::where('school_year', $schoolYear)->orderBy('grade_level')->orderBy('section_name')->get();

        $schedule = [];
        if ($sectionId) {
            $allocations = SubjectAllocation::with(['subject', 'teacher', 'schedules'])
                ->where('section_id', $sectionId)
                ->where('school_year', $schoolYear)
                ->get();

            foreach ($allocations as $alloc) {
                foreach ($alloc->schedules as $slot) {
                    $schedule[] = [
                        'subject'      => $alloc->subject_name,
                        'subject_code' => $alloc->subject_code,
                        'teacher'      => $alloc->teacher?->name ?? '—',
                        'day'          => $slot->day_of_week,
                        'time_start'   => $slot->time_start,
                        'time_end'     => $slot->time_end,
                        'room'         => $slot->room ?? '—',
                    ];
                }
            }
        }

        return view('admin.schedules.section', compact('sections', 'schedule', 'schoolYear', 'sectionId'));
    }

    // ══════════════════════════════════════════════════════
    // SCHEDULE — Teacher Schedule (Phase 4.2)
    // ══════════════════════════════════════════════════════
    public function teacherSchedule(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $teacherId  = $request->get('teacher_id');
        $teachers   = TeacherProfile::with('user')->get();

        $schedule = [];
        if ($teacherId) {
            $allocations = SubjectAllocation::with(['section', 'schedules'])
                ->where('teacher_id', $teacherId)
                ->where('school_year', $schoolYear)
                ->get();

            foreach ($allocations as $alloc) {
                foreach ($alloc->schedules as $slot) {
                    $schedule[] = [
                        'subject'      => $alloc->subject_name,
                        'section'      => $alloc->section?->display_name ?? '—',
                        'day'          => $slot->day_of_week,
                        'time_start'   => $slot->time_start,
                        'time_end'     => $slot->time_end,
                        'room'         => $slot->room ?? '—',
                    ];
                }
            }
        }

        return view('admin.schedules.teacher', compact('teachers', 'schedule', 'schoolYear', 'teacherId'));
    }

    // ══════════════════════════════════════════════════════
    // SCHEDULE — Room Schedule (Phase 4.3)
    // ══════════════════════════════════════════════════════
    public function roomSchedule(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $room       = $request->get('room');

        // Get unique rooms from schedules
        $rooms = SubjectSchedule::whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        $schedule = [];
        if ($room) {
            $slots = SubjectSchedule::where('room', $room)
                ->with(['allocation.section', 'allocation.teacher', 'allocation'])
                ->get();

            foreach ($slots as $slot) {
                $alloc = $slot->allocation;
                $schedule[] = [
                    'subject'    => $alloc?->subject_name ?? '—',
                    'section'    => $alloc?->section?->display_name ?? '—',
                    'teacher'    => $alloc?->teacher?->name ?? '—',
                    'day'        => $slot->day_of_week,
                    'time_start' => $slot->time_start,
                    'time_end'   => $slot->time_end,
                ];
            }
        }

        return view('admin.schedules.room', compact('rooms', 'schedule', 'schoolYear', 'room'));
    }
}