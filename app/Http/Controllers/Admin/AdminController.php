<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AcademicController;
use App\Models\Student;
use App\Models\Application;
use App\Models\StudentPropertyRecord;
use App\Models\StudentLibraryRecord;
use App\Models\StudentLibraryBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('status', 'active')->value('name') ?? '2025-2026';

        $calEvents = \App\Models\SchoolCalendarEvent::where('school_year', $activeSchoolYear)
            ->where('day_type', '!=', 'regular')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($e) => [
                $e->date->format('Y-m-d') => [
                    'id'          => $e->id,
                    'day_type'    => $e->day_type,
                    'event_title' => $e->event_title,
                    'description' => $e->description,
                    'badge_class' => $e->badgeClass(),
                    'label'       => $e->event_title ?: $e->dayTypeLabel(),
                ]
            ]);

        $totalStudents  = \App\Models\Student::where('school_year', $activeSchoolYear)->where('enrollment_status', 'enrolled')->count();
        $totalTeachers  = \App\Models\User::role('teacher')->count();
        $totalParents   = \App\Models\User::role('parent')->count();
        $totalSections  = \App\Models\Section::where('school_year', $activeSchoolYear)->where('section_status', 'active')->count();
        $maleStudents   = \App\Models\Student::where('school_year', $activeSchoolYear)->where('enrollment_status', 'enrolled')->where('gender', 'Male')->count();
        $femaleStudents = \App\Models\Student::where('school_year', $activeSchoolYear)->where('enrollment_status', 'enrolled')->where('gender', 'Female')->count();

        // Student Status breakdown for doughnut chart
        $statusCounts = \App\Models\Student::where('school_year', $activeSchoolYear)
            ->selectRaw('COALESCE(student_status, "active") as status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $studentStatusData = [
            'active'    => (int)($statusCounts['active']    ?? 0),
            'graduated' => (int)($statusCounts['graduated'] ?? 0),
            'completed' => (int)($statusCounts['completed'] ?? 0),
            'inactive'  => (int)($statusCounts['inactive']  ?? 0),
            'withdrawn' => (int)($statusCounts['withdrawn'] ?? 0),
        ];

        $latestAnnouncements = \App\Models\Announcement::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'calEvents', 'activeSchoolYear',
            'totalStudents', 'totalTeachers', 'totalParents', 'totalSections',
            'maleStudents', 'femaleStudents',
            'latestAnnouncements', 'studentStatusData'
        ));
    }
    public function admission()                 { return view('admin.admission'); }

    // ── Enrollment: Pending Section Assignment ──
    public function enrollStudent(Request $request)
    {
        $query = Student::whereNull('section_name')
                        ->orWhere('section_name', '');

        if ($request->filled('level')) {
            $query->where('applied_level', $request->level);
        }

        $students     = $query->latest('enrolled_at')->paginate(10)->withQueryString();
        $pendingCount = Student::where(function($q) {
            $q->whereNull('section_name')->orWhere('section_name', '');
        })->count();

        return view('admin.enrollment.enroll', compact('students', 'pendingCount'));
    }

    // ── Enrollment: Assign Section ──
    public function assignSection(Request $request, int $id)
    {
        $request->validate(['section_name' => 'required|string|max:100']);

        $student = Student::findOrFail($id);
        $student->update([
            'section_name'     => $request->section_name,
            'enrollment_status'=> 'enrolled',
        ]);

        return back()->with('success', $student->full_name . ' has been assigned to ' . $request->section_name . '.');
    }

    public function promoteStudent()            { return view('admin.enrollment.promote'); }

    public function studentList(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $query = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled');

        if ($request->filled('grade_section')) {
            $query->where('grade_level', $request->grade_section);
        }
        if ($request->has('student_status') && $request->student_status !== '') {
            $query->where('student_status', $request->student_status);
        } elseif (!$request->has('student_status')) {
            // First load default: show active only
            $query->where('student_status', 'active');
        }
        // student_status='' (All) → no filter applied
        if ($request->filled('academic_status')) {
            $query->where('academic_status', $request->academic_status);
        }
        if ($request->filled('clearance_status')) {
            $query->where('clearance_status', $request->clearance_status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name',  'like', "%$s%")
                  ->orWhere('last_name',  'like', "%$s%")
                  ->orWhere('student_id', 'like', "%$s%");
            });
        }

        $students = $query->latest('enrolled_at')->paginate(10)->withQueryString();

        $stats = [
            'total'   => Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->count(),
            'active'  => Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->where('student_status', 'active')->count(),
            'cleared' => Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->where('clearance_status', 'cleared')->count(),
        ];

        return view('admin.student-records.list', compact('students', 'stats'));
    }

    public function studentProfile($id)         { return view('admin.student-records.profile', compact('id')); }
    public function withdrawnStudents()         { return view('admin.student-records.withdrawn'); }
    public function behavioralRecord()          { return view('admin.student-records.behavioral'); }
    public function studentArchives()           { return view('admin.student-records.archives'); }

    public function withdrawStudent(Request $request)
    {
        $request->validate([
            'student_id'     => 'required|exists:students,id',
            'reason'         => 'required|string',
            'effective_date' => 'required|date',
        ]);

        $student = Student::findOrFail($request->student_id);

        $reasonMap = [
            'transfer'  => 'Transfer to another school',
            'financial' => 'Financial reasons',
            'relocation'=> 'Relocation',
            'health'    => 'Health reasons',
            'academic'  => 'Academic reasons',
            'family'    => 'Personal/Family reasons',
            'other'     => $request->other_reason ?? 'Other',
        ];
        $reasonText = $reasonMap[$request->reason] ?? $request->reason;

        $student->update([
            'student_status'    => 'withdrawn',
            'enrollment_status' => 'withdrawn',
        ]);

        Log::info('Student withdrawn', [
            'student_id'     => $student->student_id,
            'reason'         => $reasonText,
            'effective_date' => $request->effective_date,
            'details'        => $request->details,
            'by'             => auth()->id(),
        ]);

        // Notify guardian if requested
        if ($request->boolean('notify_guardian') && $student->guardian_email) {
            $name        = $student->full_name;
            $guardianEmail = $student->guardian_email;
            $guardianName  = $student->guardian_name ?? 'Parent/Guardian';
            $body = <<<TEXT
Dear {$guardianName},

This is to inform you that {$name} (Student ID: {$student->student_id}) has been officially withdrawn from My Messiah School of Cavite.

Withdrawal Details:
  Reason:         {$reasonText}
  Effective Date: {$request->effective_date}
  Details:        {$request->details}

For concerns, please contact the Registrar's Office.

Registrar's Office — My Messiah School of Cavite
TEXT;
            try {
                Mail::raw($body, fn($m) => $m
                    ->from('registrar@mmsc.edu.ph', 'MMSC Registrar')
                    ->to($guardianEmail, $guardianName)
                    ->subject("Student Withdrawal Notice — {$name}"));
            } catch (\Exception $e) {
                Log::error('Withdrawal notification email failed', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true, 'message' => $student->full_name . ' has been withdrawn.']);
    }

    public function sendStudentNotice(Request $request)
    {
        $request->validate([
            'student_ids'   => 'required|array',
            'notice_type'   => 'required|string',
            'subject'       => 'required|string|max:255',
            'message'       => 'required|string',
            'send_to'       => 'required|array',
        ]);

        $students = Student::whereIn('id', $request->student_ids)->get();
        $sent = 0;

        foreach ($students as $student) {
            $recipients = [];
            if (in_array('student', $request->send_to) && $student->personal_email) {
                $recipients[] = [$student->personal_email, $student->full_name];
            }
            if (in_array('parent', $request->send_to) && $student->guardian_email) {
                $recipients[] = [$student->guardian_email, $student->guardian_name ?? 'Parent/Guardian'];
            }
            foreach ($recipients as [$email, $name]) {
                try {
                    $body = $request->message;
                    Mail::raw($body, fn($m) => $m
                        ->from('registrar@mmsc.edu.ph', 'MMSC Registrar')
                        ->to($email, $name)
                        ->subject($request->subject));
                    $sent++;
                } catch (\Exception $e) {
                    Log::error('Student notice email failed', ['email' => $email, 'error' => $e->getMessage()]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => "Notice sent to {$sent} recipient(s)."]);
    }

    public function exportStudents(Request $request)
    {
        // Placeholder — returns JSON for now; wire up PhpSpreadsheet/Maatwebsite when ready
        return response()->json(['success' => false, 'message' => 'Export feature coming soon.'], 501);
    }

    public function clearanceAcademicStanding() { return view('admin.clearance.academic-standing'); }
    public function clearanceFinance(Request $request)
    {
        $schoolYear     = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get();

        $query = Student::with(['finance' => fn($q) => $q->where('school_year', $schoolYear)])
            ->where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled');

        if ($request->filled('student_category')) $query->where('student_category', $request->student_category);
        if ($request->filled('grade_section'))    $query->where('grade_level', $request->grade_section);
        if ($request->filled('clearance_status')) $query->whereHas('finance', fn($q) => $q->where('finance_clearance', $request->clearance_status));
        if ($request->filled('program_level'))    $query->where('applied_level', $request->program_level);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('first_name','like',"%$s%")
                ->orWhere('last_name','like',"%$s%")
                ->orWhere('student_id','like',"%$s%"));
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')
            ->paginate($request->get('per_page', 10))->withQueryString();

        // Sync overdue on visible records
        foreach ($students as $student) {
            $student->finance?->syncOverdueStatuses();
        }

        $totalStudents  = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->count();

        // Finance-clearance counts from student_finance table
        $clearedCount = \App\Models\StudentFinance::where('school_year', $schoolYear)
            ->whereNotNull('student_id')->where('finance_clearance', 'cleared')->count();
        $pendingCount = \App\Models\StudentFinance::where('school_year', $schoolYear)
            ->whereNotNull('student_id')->where('finance_clearance', 'pending')->count();
        $overdueCount = \App\Models\StudentFinance::where('school_year', $schoolYear)
            ->whereNotNull('student_id')->where('finance_clearance', 'overdue')->count();
        // Students with no finance config yet also count as pending
        $noConfigCount = $totalStudents
            - \App\Models\StudentFinance::where('school_year', $schoolYear)->whereNotNull('student_id')->count();
        $pendingCount += max(0, $noConfigCount);

        $sections = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->whereNotNull('grade_level')->distinct()->pluck('grade_level')->sort()->values();

        return view('admin.clearance.finance', compact(
            'students', 'schoolYear', 'allSchoolYears',
            'totalStudents', 'clearedCount', 'pendingCount', 'overdueCount', 'sections'
        ));
    }
    public function clearanceLibrary(Request $request)
    {
        $schoolYear     = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get();

        $query = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled');

        if ($request->filled('grade_section'))  $query->where('grade_level', $request->grade_section);
        if ($request->filled('program_level'))  $query->where('applied_level', $request->program_level);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('first_name','like',"%$s%")
                ->orWhere('last_name','like',"%$s%")
                ->orWhere('student_id','like',"%$s%"));
        }

        // Filter by library record status
        if ($request->filled('clearance_status')) {
            $st = $request->clearance_status;
            if ($st === 'cleared') {
                $query->whereHas('libraryRecord', fn($q) => $q->where('school_year', $schoolYear)->where('status', 'cleared'));
            } elseif ($st === 'overdue') {
                $query->whereHas('libraryRecord', fn($q) => $q->where('school_year', $schoolYear)->where('status', 'overdue'));
            } elseif ($st === 'pending') {
                $query->whereHas('libraryRecord', fn($q) => $q->where('school_year', $schoolYear)->whereIn('status', ['pending','no_record']));
            }
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')
            ->paginate($request->get('per_page', 10))->withQueryString();

        // Auto-create library records for enrolled students
        $students->each(fn($s) => StudentLibraryRecord::ensureForStudent($s->id, $schoolYear));

        // Eager-load records + books
        $libraryRecords = StudentLibraryRecord::with('books')
            ->where('school_year', $schoolYear)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()->keyBy('student_id');

        $students->each(function ($student) use ($libraryRecords) {
            $student->libraryRecord = $libraryRecords->get($student->id);
        });

        $totalStudents = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->count();
        $enrolledIds   = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->pluck('id');

        $clearedCount  = StudentLibraryRecord::where('school_year', $schoolYear)->whereIn('student_id', $enrolledIds)->where('status', 'cleared')->count();
        $overdueCount  = StudentLibraryRecord::where('school_year', $schoolYear)->whereIn('student_id', $enrolledIds)->where('status', 'overdue')->count();
        $pendingCount  = $totalStudents - $clearedCount - $overdueCount;

        $sections = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->whereNotNull('grade_level')->distinct()->pluck('grade_level')->sort()->values();

        return view('admin.clearance.library', compact(
            'students', 'schoolYear', 'allSchoolYears',
            'totalStudents', 'clearedCount', 'pendingCount', 'overdueCount', 'sections'
        ));
    }

    public function addLibraryBook(Request $request, int $id)
    {
        $validated = $request->validate([
            'book_title'     => 'required|string|max:255',
            'book_id'        => 'nullable|string|max:100',
            'date_borrowed'  => 'required|date',
            'due_date'       => 'required|date|after_or_equal:date_borrowed',
            'fines'          => 'nullable|numeric|min:0',
            'remarks'        => 'nullable|string|max:500',
            'librarian_name' => 'nullable|string|max:150',
        ]);

        $student = Student::findOrFail($id);
        $record  = StudentLibraryRecord::ensureForStudent($student->id, $student->school_year);

        $record->books()->create([
            'book_title'     => $validated['book_title'],
            'book_id'        => $validated['book_id'] ?? null,
            'date_borrowed'  => $validated['date_borrowed'],
            'due_date'       => $validated['due_date'],
            'fines'          => $validated['fines'] ?? 0,
            'remarks'        => $validated['remarks'] ?? null,
            'librarian_name' => $validated['librarian_name'] ?? null,
        ]);

        $record->load('books');
        $record->recomputeStatus();

        return response()->json([
            'success' => true,
            'message' => 'Book added for ' . $student->full_name . '.',
        ]);
    }

    public function returnLibraryBook(Request $request, int $id)
    {
        $request->validate([
            'book_id'       => 'required|integer|exists:student_library_books,id',
            'date_returned' => 'required|date',
            'fines'         => 'nullable|numeric|min:0',
        ]);

        $book = StudentLibraryBook::findOrFail($request->book_id);
        $book->update([
            'date_returned' => $request->date_returned,
            'fines'         => $request->fines ?? $book->fines,
        ]);

        $record = $book->record;
        $record->load('books');
        $record->recomputeStatus();

        return response()->json(['success' => true, 'message' => 'Book marked as returned.']);
    }

    public function updateLibraryStatus(Request $request, int $id)
    {
        $request->validate([
            'status'     => 'required|in:cleared,pending,overdue,no_record',
            'cleared_by' => 'nullable|string|max:150',
            'remarks'    => 'nullable|string|max:500',
        ]);

        $student = Student::findOrFail($id);
        $record  = StudentLibraryRecord::ensureForStudent($student->id, $student->school_year);

        $record->update([
            'status'     => $request->status,
            'cleared_by' => $request->status === 'cleared' ? ($request->cleared_by ?? auth()->user()?->name) : $record->cleared_by,
            'cleared_at' => $request->status === 'cleared' ? now() : $record->cleared_at,
            'remarks'    => $request->remarks ?? $record->remarks,
        ]);

        return response()->json(['success' => true, 'message' => 'Library status updated.']);
    }
    public function clearanceRecords(Request $request)
    {
        $schoolYear   = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $statusFilter = $request->get('status');
        $gradeFilter  = $request->get('grade_section');
        $search       = $request->get('search');

        $query = Student::where('school_year', $schoolYear)
            ->whereIn('student_status', ['active','enrolled'])
            ->when($gradeFilter, fn($q) => $q->where('grade_level', $gradeFilter))
            ->when($search, fn($q) => $q->where(fn($sq) =>
                $sq->where('first_name', 'like', "%$search%")
                   ->orWhere('last_name',  'like', "%$search%")
                   ->orWhere('student_id', 'like', "%$search%")
            ))
            ->orderBy('last_name')->orderBy('first_name');

        $allStudents = $query->get();

        // Load linked applications
        $refs         = $allStudents->pluck('reference_number')->filter()->unique()->values();
        $applications = Application::whereIn('reference_number', $refs)->get()->keyBy('reference_number');

        // Compute records_status per student
        $allStudents = $allStudents->map(function (Student $s) use ($applications) {
            $app = $applications->get($s->reference_number);
            $s->application = $app;
            if ($app) {
                $allApproved = $app->psa_status === 'approved'
                            && $app->report_card_status === 'approved'
                            && $app->good_moral_status === 'approved';
                $anyMissing  = in_array('not_uploaded', [
                    $app->psa_status, $app->report_card_status, $app->good_moral_status,
                ]);
                $s->records_status = $allApproved ? 'cleared' : ($anyMissing ? 'missing' : 'pending');
            } else {
                $s->records_status = 'missing';
            }
            return $s;
        });

        if ($statusFilter) {
            $allStudents = $allStudents->filter(fn($s) => $s->records_status === $statusFilter)->values();
        }

        $totalStudents = $allStudents->count();
        $clearedCount  = $allStudents->where('records_status', 'cleared')->count();
        $pendingCount  = $allStudents->where('records_status', 'pending')->count();
        $missingCount  = $allStudents->where('records_status', 'missing')->count();

        $sections = Student::where('school_year', $schoolYear)
            ->whereIn('student_status', ['active','enrolled'])
            ->whereNotNull('grade_level')->distinct()->pluck('grade_level')->sort()->values();

        $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get();

        return view('admin.clearance.records', compact(
            'allStudents', 'totalStudents', 'clearedCount', 'pendingCount', 'missingCount',
            'schoolYear', 'allSchoolYears', 'sections'
        ));
    }
    public function clearanceSummary(Request $request)
    {
        $schoolYear     = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get();

        // ── Load ALL enrolled students (for stat cards + progress bars) ──────
        $allEnrolled = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->select(['id', 'reference_number', 'behavioral_clearance', 'property_clearance', 'first_name', 'last_name', 'grade_level', 'applied_level'])
            ->get();

        $allIds  = $allEnrolled->pluck('id');
        $allRefs = $allEnrolled->pluck('reference_number')->filter()->unique();

        $libAll  = StudentLibraryRecord::where('school_year', $schoolYear)->whereIn('student_id', $allIds)->select(['student_id','status'])->get()->keyBy('student_id');
        $propAll = StudentPropertyRecord::where('school_year', $schoolYear)->whereIn('student_id', $allIds)->select(['student_id','status'])->get()->keyBy('student_id');
        $finAll  = \App\Models\StudentFinance::where('school_year', $schoolYear)->whereIn('student_id', $allIds)->select(['student_id','finance_clearance'])->get()->keyBy('student_id');
        $appAll  = Application::whereIn('reference_number', $allRefs)
            ->select(['id','reference_number','psa_status','report_card_status','good_moral_status','medical_status'])
            ->get()->keyBy('reference_number');

        // Helper to compute records_status from app
        $recordsStatus = function ($refNum) use ($appAll) {
            $app = $refNum ? $appAll->get($refNum) : null;
            if (!$app) return 'missing';
            $allApproved = $app->psa_status === 'approved' && $app->report_card_status === 'approved' && $app->good_moral_status === 'approved';
            $anyMissing  = in_array('not_uploaded', [$app->psa_status, $app->report_card_status, $app->good_moral_status]);
            return $allApproved ? 'cleared' : ($anyMissing ? 'missing' : 'pending');
        };

        // Helper: overall status from individual statuses
        $overallStatus = function ($fin, $lib, $prop, $beh, $rec) {
            $isCleared = fn($s) => $s === 'cleared';
            $isOverdue = fn($s) => in_array($s, ['overdue', 'missing']);
            if ($isCleared($fin) && $isCleared($lib) && $isCleared($prop) && $isCleared($beh) && $isCleared($rec)) return 'cleared';
            if ($isOverdue($fin) || $isOverdue($lib) || $isOverdue($prop) || $isOverdue($beh) || $isOverdue($rec)) return 'overdue';
            return 'pending';
        };

        // ── Stat card counts + progress bars ──────────────────────────────────
        $totalStudents = $allEnrolled->count();
        $clearedCount = $overdueCount = 0;
        $finCleared = $libCleared = $propCleared = $behCleared = $recCleared = 0;

        foreach ($allEnrolled as $s) {
            $fin = $finAll->get($s->id)?->finance_clearance ?? 'pending';
            $lib = $libAll->get($s->id)?->status            ?? 'no_record';
            $prop= $propAll->get($s->id)?->status           ?? 'for_issuance';
            $beh = $s->behavioral_clearance                 ?? 'pending';
            $rec = $recordsStatus($s->reference_number);
            $ov  = $overallStatus($fin, $lib, $prop, $beh, $rec);

            if ($ov === 'cleared') $clearedCount++;
            if ($ov === 'overdue') $overdueCount++;
            if ($fin  === 'cleared') $finCleared++;
            if ($lib  === 'cleared') $libCleared++;
            if ($prop === 'cleared') $propCleared++;
            if ($beh  === 'cleared') $behCleared++;
            if ($rec  === 'cleared') $recCleared++;
        }
        $pendingCount = $totalStudents - $clearedCount - $overdueCount;

        $pct = fn($n) => $totalStudents > 0 ? round($n / $totalStudents * 100) : 0;
        $progress = [
            'finance'   => $pct($finCleared),
            'library'   => $pct($libCleared),
            'records'   => $pct($recCleared),
            'behavioral'=> $pct($behCleared),
            'property'  => $pct($propCleared),
            'academic'  => 0,
        ];

        // ── Paginated query for the table ─────────────────────────────────────
        $query = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled');

        if ($request->filled('grade_section'))  $query->where('grade_level', $request->grade_section);
        if ($request->filled('program_level'))  $query->where('applied_level', $request->program_level);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('first_name','like',"%$s%")->orWhere('last_name','like',"%$s%")->orWhere('student_id','like',"%$s%"));
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')
            ->paginate($request->get('per_page', 10))->withQueryString();

        $pageIds  = $students->pluck('id');
        $pageRefs = $students->pluck('reference_number')->filter()->unique();

        $libPage  = StudentLibraryRecord::where('school_year', $schoolYear)->whereIn('student_id', $pageIds)->get()->keyBy('student_id');
        $propPage = StudentPropertyRecord::where('school_year', $schoolYear)->whereIn('student_id', $pageIds)->get()->keyBy('student_id');
        $finPage  = \App\Models\StudentFinance::where('school_year', $schoolYear)->whereIn('student_id', $pageIds)->get()->keyBy('student_id');
        $appPage  = Application::whereIn('reference_number', $pageRefs)
            ->select(['id','reference_number','psa_status','report_card_status','good_moral_status','medical_status'])
            ->get()->keyBy('reference_number');

        $students->each(function ($student) use ($libPage, $propPage, $finPage, $appPage, $recordsStatus, $overallStatus) {
            $student->finance_status    = $finPage->get($student->id)?->finance_clearance ?? 'pending';
            $student->library_status    = $libPage->get($student->id)?->status            ?? 'no_record';
            $student->property_status   = $propPage->get($student->id)?->status           ?? 'for_issuance';
            $student->behavioral_status = $student->behavioral_clearance                   ?? 'pending';
            $student->records_status    = $recordsStatus($student->reference_number);
            $student->academic_status   = null; // not configured yet

            $student->overall_status = $overallStatus(
                $student->finance_status, $student->library_status,
                $student->property_status, $student->behavioral_status,
                $student->records_status
            );
        });

        // Filter by individual status if requested (post-load)
        foreach (['finance','library','property','behavioral','records'] as $cat) {
            if ($request->filled($cat.'_status')) {
                $students->setCollection(
                    $students->getCollection()->filter(fn($s) => $s->{$cat.'_status'} === $request->get($cat.'_status'))->values()
                );
            }
        }
        if ($request->filled('overall_status')) {
            $students->setCollection(
                $students->getCollection()->filter(fn($s) => $s->overall_status === $request->get('overall_status'))->values()
            );
        }

        $sections = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->whereNotNull('grade_level')->distinct()->pluck('grade_level')->sort()->values();

        return view('admin.clearance.summary', compact(
            'students', 'schoolYear', 'allSchoolYears', 'sections',
            'totalStudents', 'clearedCount', 'pendingCount', 'overdueCount',
            'progress'
        ));
    }

    public function markAllCleared(Request $request, int $id)
    {
        $student = Student::findOrFail($id);

        // Update student table fields
        $student->update([
            'behavioral_clearance' => 'cleared',
            'property_clearance'   => 'cleared',
        ]);

        // Library record
        StudentLibraryRecord::where('student_id', $id)->where('school_year', $student->school_year)
            ->update(['status' => 'cleared', 'cleared_by' => auth()->user()?->name ?? 'Admin', 'cleared_at' => now()]);

        // Property record
        StudentPropertyRecord::where('student_id', $id)->where('school_year', $student->school_year)
            ->update(['status' => 'cleared']);

        // Finance
        \App\Models\StudentFinance::where('student_id', $id)->where('school_year', $student->school_year)
            ->update(['finance_clearance' => 'cleared']);

        return response()->json(['success' => true, 'message' => $student->full_name . ' has been marked fully cleared.']);
    }

    public function clearanceBehavioral(Request $request)
    {
        $schoolYear     = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get();

        $query = Student::with(['behavioralRecords' => fn($q) => $q->with('recorder')->where('school_year', $schoolYear)->orderByDesc('incident_date')])
            ->where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled');

        if ($request->filled('grade_section'))    $query->where('grade_level', $request->grade_section);
        if ($request->filled('clearance_status')) $query->where('behavioral_clearance', $request->clearance_status);
        if ($request->filled('program_level'))    $query->where('applied_level', $request->program_level);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('first_name','like',"%$s%")
                ->orWhere('last_name','like',"%$s%")
                ->orWhere('student_id','like',"%$s%"));
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')
            ->paginate($request->get('per_page', 10))->withQueryString();

        $totalStudents = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->count();
        $clearedCount  = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->where('behavioral_clearance', 'cleared')->count();
        $pendingCount  = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->where('behavioral_clearance', 'pending')->count();
        $overdueCount  = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->where('behavioral_clearance', 'overdue')->count();

        $sections = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->whereNotNull('grade_level')->distinct()->pluck('grade_level')->sort()->values();

        return view('admin.clearance.behavioral', compact(
            'students', 'schoolYear', 'allSchoolYears',
            'totalStudents', 'clearedCount', 'pendingCount', 'overdueCount', 'sections'
        ));
    }

    public function clearanceProperty(Request $request)
    {
        $schoolYear     = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get();

        $query = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled');

        if ($request->filled('grade_section'))    $query->where('grade_level', $request->grade_section);
        if ($request->filled('program_level'))    $query->where('applied_level', $request->program_level);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('first_name','like',"%$s%")
                ->orWhere('last_name','like',"%$s%")
                ->orWhere('student_id','like',"%$s%"));
        }

        // Filter by property record status (not students.property_clearance)
        if ($request->filled('clearance_status')) {
            $status = $request->clearance_status;
            // Map UI status to property record statuses
            $recordStatuses = match($status) {
                'cleared' => ['cleared'],
                'overdue' => ['overdue'],
                'pending' => ['for_issuance', 'issued'],
                default   => [],
            };
            if ($recordStatuses) {
                $query->whereHas('propertyRecord', fn($q) => $q->where('school_year', $schoolYear)->whereIn('status', $recordStatuses));
            }
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')
            ->paginate($request->get('per_page', 10))->withQueryString();

        // Auto-create property records for any enrolled student who doesn't have one
        $students->each(function ($student) use ($schoolYear) {
            StudentPropertyRecord::ensureForStudent($student->id, $schoolYear);
        });

        // Eager-load property records + items
        $studentIds = $students->pluck('id');
        $propertyRecords = StudentPropertyRecord::with('items', 'issuedByUser')
            ->where('school_year', $schoolYear)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy('student_id');

        $students->each(function ($student) use ($propertyRecords) {
            $student->propertyRecord = $propertyRecords->get($student->id);
        });

        $totalStudents = Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->count();

        // Count by property record status
        $clearedCount = StudentPropertyRecord::where('school_year', $schoolYear)
            ->whereIn('student_id', Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->pluck('id'))
            ->where('status', 'cleared')->count();
        $overdueCount = StudentPropertyRecord::where('school_year', $schoolYear)
            ->whereIn('student_id', Student::where('school_year', $schoolYear)->where('enrollment_status', 'enrolled')->pluck('id'))
            ->where('status', 'overdue')->count();
        $pendingCount = $totalStudents - $clearedCount - $overdueCount;

        $sections = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->whereNotNull('grade_level')->distinct()->pluck('grade_level')->sort()->values();

        return view('admin.clearance.property', compact(
            'students', 'schoolYear', 'allSchoolYears',
            'totalStudents', 'clearedCount', 'pendingCount', 'overdueCount', 'sections'
        ));
    }

    public function issuePropertyItems(Request $request, int $id)
    {
        $request->validate([
            'items'   => 'required|array',
            'items.*.item_name' => 'required|string|max:100',
            'items.*.issued'    => 'boolean',
            'items.*.returned'  => 'boolean',
            'items.*.damaged'   => 'boolean',
            'items.*.replacement_fee' => 'nullable|numeric|min:0',
        ]);

        $student = Student::findOrFail($id);
        $schoolYear = $student->school_year;

        $record = StudentPropertyRecord::firstOrCreate(
            ['student_id' => $student->id, 'school_year' => $schoolYear],
            ['status' => 'for_issuance']
        );

        $hasIssued = false;
        foreach ($request->items as $itemData) {
            $issued   = (bool)($itemData['issued']   ?? false);
            $returned = (bool)($itemData['returned'] ?? false);
            $damaged  = (bool)($itemData['damaged']  ?? false);
            $fee      = (float)($itemData['replacement_fee'] ?? 0);

            if ($issued) $hasIssued = true;

            $record->items()->updateOrCreate(
                ['item_name' => $itemData['item_name']],
                [
                    'issued'          => $issued,
                    'returned'        => $returned,
                    'damaged'         => $damaged,
                    'replacement_fee' => $fee,
                    'issued_at'       => $issued ? ($record->issued_at ?? now()) : null,
                    'returned_at'     => $returned ? now() : null,
                ]
            );
        }

        if ($hasIssued && !$record->issued_at) {
            $record->issued_at = now();
            $record->issued_by = auth()->id();
            $record->save();
        }

        $record->load('items');
        $record->recomputeStatus();

        return response()->json([
            'success' => true,
            'message' => 'Property items updated for ' . $student->full_name . '.',
            'status'  => $record->fresh()->status,
        ]);
    }

    public function updateBehavioralClearance(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,cleared,overdue']);
        Student::findOrFail($id)->update(['behavioral_clearance' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Behavioral clearance updated.']);
    }

    public function updatePropertyClearance(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,cleared,overdue']);
        Student::findOrFail($id)->update(['property_clearance' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Property clearance updated.']);
    }

    public function updateFinanceClearance(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,cleared,overdue']);
        $student = Student::findOrFail($id);
        // Update the finance record's clearance status
        $finance = \App\Models\StudentFinance::where('student_id', $id)
            ->orderByDesc('created_at')->first();
        if ($finance) {
            $finance->update(['finance_clearance' => $request->status]);
        }
        // Also keep clearance_status in sync for summary queries
        $student->update(['clearance_status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Finance clearance updated.']);
    }

    public function academicAttendance()        { return view('admin.academics.attendance'); }
    public function academicSubjects(Request $request) { return app(AcademicController::class)->index($request); }
    public function academicGrades()            { return view('admin.academics.grades'); }

    public function classesList(Request $request)
    {
        $schoolYear  = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $schoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get(['name']);

        $query = \App\Models\SubjectAllocation::with(['section', 'teacher', 'schedules'])
            ->where('school_year', $schoolYear)
            ->when($request->grade_section, fn($q) => $q->whereHas('section', fn($sq) =>
                $sq->where('grade_level', $request->grade_section)))
            ->when($request->subject_id,  fn($q) => $q->where('subject_id',  $request->subject_id))
            ->when($request->teacher_id,  fn($q) => $q->where('teacher_id',  $request->teacher_id))
            ->orderBy('subject_name');

        $classes  = $query->paginate(10)->withQueryString();
        $sections = \App\Models\Section::where('school_year', $schoolYear)
            ->orderBy('grade_level')->orderBy('section_name')->get();
        $subjects = \App\Models\Subject::where('is_active', true)->orderBy('subject_name')->get(['id','subject_name']);
        $teachers = \App\Models\User::role('teacher')->orderBy('name')->get(['id','name']);

        return view('admin.classes.list', compact('classes', 'sections', 'subjects', 'teachers', 'schoolYear', 'schoolYears'));
    }

    public function classShow(int $id)
    {
        $class = \App\Models\SubjectAllocation::with(['section', 'teacher', 'schedules'])
            ->findOrFail($id);

        return response()->json([
            'class' => [
                'id'            => $class->id,
                'subject_name'  => $class->subject_name,
                'subject_code'  => $class->subject_code,
                'grade_level'   => $class->section?->grade_level ?? '—',
                'section_name'  => $class->section?->section_name ?? '—',
                'display_name'  => $class->section?->display_name ?? '—',
                'section_id'    => $class->section_id,
                'school_year'   => $class->school_year,
                'program_level' => $class->section?->program_level ?? '—',
                'hours_per_week'=> $class->hours_per_week,
                'teacher'       => $class->teacher?->name ?? '—',
                'track'         => $class->section?->track,
                'strand'        => $class->section?->strand,
                'schedules'     => $class->schedules->map(fn($s) => [
                    'day'        => $s->day_of_week,
                    'time_start' => \Carbon\Carbon::parse($s->time_start)->format('g:i A'),
                    'time_end'   => \Carbon\Carbon::parse($s->time_end)->format('g:i A'),
                    'room'       => $s->room ?? '—',
                ]),
            ],
        ]);
    }

    public function classRosters(Request $request)
    {
        $schoolYear  = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $school_year = \App\Models\SchoolYear::orderByDesc('start_date')->pluck('name')->toArray();
        if (empty($school_year)) {
            $school_year = [$schoolYear];
        } elseif (!in_array($schoolYear, $school_year)) {
            array_unshift($school_year, $schoolYear);
        }
        $sections = \App\Models\Section::where('school_year', $schoolYear)
            ->orderBy('grade_level')->orderBy('section_name')->get();

        return view('admin.classes.rosters', compact('schoolYear', 'school_year', 'sections'));
    }
    public function classrooms()                { return view('admin.classes.classrooms'); }

    public function classRosterStudents(Request $request)
    {
        $section = \App\Models\Section::findOrFail($request->section_id);

        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $students = \App\Models\Student::whereHas('enrollments', fn($q) =>
                $q->where('section_id', $section->id)
                  ->where('school_year', $schoolYear)
                  ->where('assignment_status', 'assigned')
            )
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id','student_id','first_name','last_name','middle_name','suffix',
                   'gender','lrn','school_email','personal_email']);

        return response()->json([
            'success'  => true,
            'total'    => $students->count(),
            'section'  => [
                'id'           => $section->id,
                'grade_level'  => $section->grade_level,
                'section_name' => $section->section_name,
                'display_name' => $section->display_name,
                'school_year'  => $request->get('school_year', \App\Models\SchoolYear::activeName()),
                'adviser'      => $section->homeroom_adviser_name ?? 'TBA',
                'room'         => $section->room ?? '—',
            ],
            'students' => $students->map(fn($s) => [
                'id'         => $s->id,
                'student_id' => $s->student_id,
                'full_name'  => trim("{$s->last_name}, {$s->first_name}" . ($s->middle_name ? ' '.strtoupper(substr($s->middle_name,0,1)).'.' : '')),
                'gender'     => $s->gender ?? '—',
                'lrn'        => $s->lrn ?? '—',
                'email'      => $s->school_email ?? $s->personal_email ?? '—',
            ]),
        ]);
    }

    public function classRosterExportExcel(Request $request)
    {
        $section    = \App\Models\Section::findOrFail($request->section_id);
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $students = \App\Models\Student::whereHas('enrollments', fn($q) =>
                $q->where('section_id', $section->id)
                  ->where('school_year', $schoolYear)
                  ->where('assignment_status', 'assigned')
            )
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Class Roster');

        // ── School header rows ────────────────────────────────
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'MY MESSIAH SCHOOL OF CAVITE');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'CLASS ROSTER — SY ' . $schoolYear);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        // ── Section info ──────────────────────────────────────
        $sheet->setCellValue('A4', 'Grade Level:');
        $sheet->setCellValue('B4', $section->grade_level);
        $sheet->setCellValue('D4', 'Section:');
        $sheet->setCellValue('E4', $section->section_name);

        $sheet->setCellValue('A5', 'Homeroom Adviser:');
        $sheet->setCellValue('B5', $section->homeroom_adviser_name ?? 'TBA');
        $sheet->setCellValue('D5', 'Room:');
        $sheet->setCellValue('E5', $section->room ?? '—');

        $sheet->setCellValue('A6', 'Program Level:');
        $sheet->setCellValue('B6', $section->program_level ?? '—');
        $sheet->setCellValue('D6', 'Total Students:');
        $sheet->setCellValue('E6', $students->count());

        foreach (['A4','A5','A6','D4','D5','D6'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // ── Table header ──────────────────────────────────────
        $headers = ['#', 'Student ID', 'LRN', 'Last Name', 'First Name', 'Middle Name', 'Gender', 'Email'];
        $col = 'A'; $headerRow = 8;
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $headerRow, $h);
            $sheet->getStyle($col . $headerRow)->getFont()->setBold(true)->setColor(
                (new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE))
            );
            $sheet->getStyle($col . $headerRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('0D4C8F');
            $col++;
        }

        // ── Data rows ─────────────────────────────────────────
        foreach ($students as $i => $s) {
            $row = $headerRow + 1 + $i;
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $s->student_id);
            $sheet->setCellValue('C' . $row, $s->lrn ?? '');
            $sheet->setCellValue('D' . $row, $s->last_name);
            $sheet->setCellValue('E' . $row, $s->first_name);
            $sheet->setCellValue('F' . $row, $s->middle_name ?? '');
            $sheet->setCellValue('G' . $row, $s->gender ?? '');
            $sheet->setCellValue('H' . $row, $s->school_email ?? $s->personal_email ?? '');

            // Alternating row fill
            if ($i % 2 === 1) {
                $sheet->getStyle('A'.$row.':H'.$row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F7FAFF');
            }
        }

        // ── Column widths ─────────────────────────────────────
        foreach (['A'=>6,'B'=>14,'C'=>16,'D'=>20,'E'=>20,'F'=>18,'G'=>10,'H'=>28] as $c=>$w) {
            $sheet->getColumnDimension($c)->setWidth($w);
        }

        // ── Border on table ───────────────────────────────────
        $lastRow = $headerRow + $students->count();
        $sheet->getStyle('A'.$headerRow.':H'.$lastRow)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // ── Output ────────────────────────────────────────────
        $filename = 'ClassRoster_' . str_replace([' ','–','-'], '_', $section->grade_level . '_' . $section->section_name) . '_SY' . str_replace('-','_',$schoolYear) . '.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function classRosterExportPdf(Request $request)
    {
        $section    = \App\Models\Section::findOrFail($request->section_id);
        $pdfSchoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $students = \App\Models\Student::whereHas('enrollments', fn($q) =>
                $q->where('section_id', $section->id)
                  ->where('school_year', $pdfSchoolYear)
                  ->where('assignment_status', 'assigned')
            )
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        return view('admin.academics.pdf.class-roster-pdf', [
            'section'     => $section,
            'students'    => $students,
            'orientation' => $request->get('orientation', 'portrait'),
            'showLogo'    => filter_var($request->get('show_logo', true),   FILTER_VALIDATE_BOOLEAN),
            'showLrn'     => filter_var($request->get('show_lrn', true),    FILTER_VALIDATE_BOOLEAN),
            'showGender'  => filter_var($request->get('show_gender', true), FILTER_VALIDATE_BOOLEAN),
            'showEmail'   => filter_var($request->get('show_email', true),  FILTER_VALIDATE_BOOLEAN),
        ]);
    }
    public function sectionManagement()         { return view('admin.classes.sections'); }

    public function scheduleClass()             { return view('admin.schedule.class'); }
    public function scheduleTeacher()           { return view('admin.schedule.teacher'); }
    public function scheduleRoom()              { return view('admin.schedule.room'); }

    public function teachers()                  { return view('admin.teachers'); }
    public function announcements()             { return view('admin.announcements'); }

    public function reportStudentProfile()      { return view('admin.reports.student-profile'); }
    public function reportStudentList()         { return view('admin.reports.student-list'); }
    public function reportEnrollmentSummary()   { return view('admin.reports.enrollment-summary'); }
    public function reportGraduationList()      { return view('admin.reports.graduation-list'); }
    public function reportReportCard()          { return view('admin.reports.report-card'); }
    public function reportDailyAttendance()     { return view('admin.reports.daily-attendance'); }
    public function reportAttendanceRecord()    { return view('admin.reports.attendance-record'); }
    public function reportClassRecord()         { return view('admin.reports.class-record'); }
    public function reportClassRoster()         { return view('admin.reports.class-roster'); }
    public function reportHonorRoll()           { return view('admin.reports.honor-roll'); }
    public function reportClearanceSummary()    { return view('admin.reports.clearance-summary'); }
    public function reportRecordsClearance()    { return view('admin.reports.records-clearance'); }
    public function reportLibraryClearance()    { return view('admin.reports.library-clearance'); }
    public function reportFinancialClearance()  { return view('admin.reports.financial-clearance'); }
    public function reportClassSchedule()       { return view('admin.reports.class-schedule'); }
    public function reportTeacherSchedule()     { return view('admin.reports.teacher-schedule'); }
    public function reportRoomSchedule()        { return view('admin.reports.room-schedule'); }
    public function reportTeacherLoad()         { return view('admin.reports.teacher-load'); }
    public function reportTeacherList()         { return view('admin.reports.teacher-list'); }

    public function schoolCalendar()            { return view('admin.school-calendar'); }

    public function settingsAccount()
    {
        return view('admin.settings.account', ['user' => auth()->user()]);
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'username', 'email', 'phone'));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePhoto(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo' => $path]);

        return back()->with('success', 'Profile photo updated successfully.');
    }

    public function updatePassword(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function settingsUserManagement()    { return view('admin.settings.user-management'); }
    public function settingsGeneral()           { return view('admin.settings.general'); }
}
