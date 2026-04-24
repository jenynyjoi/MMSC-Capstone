<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentRecordController extends Controller
{
    // ══════════════════════════════════════════════════════
    // STUDENT LIST — index
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear     = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $gradeSection   = $request->get('grade_section');
        $studentStatus  = $request->get('student_status', 'active');
        $academicStatus = $request->get('academic_status');
        $clearanceStatus= $request->get('clearance_status');

        $query = Student::query()
            ->where('school_year', $schoolYear)
            ->whereHas('enrollments', fn($q) => $q->where('school_year', $schoolYear)->whereNotNull('section_id'))
            ->when($studentStatus,   fn($q) => $q->where('student_status',   $studentStatus))
            ->when($academicStatus,  fn($q) => $q->where('academic_status',  $academicStatus))
            ->when($clearanceStatus, fn($q) => $q->where('clearance_status', $clearanceStatus))
            ->when($gradeSection,    fn($q) => $q->where('grade_level',      $gradeSection))
            ->with('latestEnrollment')
            ->orderBy('last_name')
            ->orderBy('first_name');

        $students = $query->paginate(10)->withQueryString();

        $students->through(function ($student) {
            $enrollment = StudentEnrollment::where('student_id', $student->id)
                ->where('school_year', $student->school_year)
                ->first();
            $student->section_name         = $enrollment?->section_name ?? '—';
            $student->section_display_name = \App\Models\Section::formatName(
                $student->grade_level ?? '—',
                $enrollment?->section_name ?? '—',
                $enrollment?->strand
            );
            $student->clearance_status = $student->clearance_status ?? 'pending';
            $student->academic_status  = $student->academic_status  ?? 'in_progress';
            return $student;
        });

        $stats = [
            'total'   => Student::where('school_year', $schoolYear)->count(),
            'active'  => Student::where(['school_year' => $schoolYear, 'student_status' => 'active'])->count(),
            'cleared' => Student::where(['school_year' => $schoolYear, 'clearance_status' => 'cleared'])->count(),
        ];

        return view('admin.student-records.list', compact('students', 'stats', 'schoolYear'));
    }

    // ══════════════════════════════════════════════════════
    // STUDENT PROFILE — show
    // ══════════════════════════════════════════════════════
    public function show(int $id)
    {
        $student     = Student::with(['enrollments', 'latestEnrollment'])->findOrFail($id);
        $enrollment  = $student->latestEnrollment;
        $application = $student->reference_number
            ? \App\Models\Application::where('reference_number', $student->reference_number)->first()
            : null;
        return view('admin.student-records.profile', compact('student', 'enrollment', 'application'));
    }

    // ══════════════════════════════════════════════════════
    // UPDATE PROFILE
    // ══════════════════════════════════════════════════════
    public function updateProfile(Request $request, int $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'first_name'              => 'sometimes|required|string|max:100',
            'last_name'               => 'sometimes|required|string|max:100',
            'middle_name'             => 'nullable|string|max:100',
            'suffix'                  => 'nullable|string|max:20',
            'gender'                  => 'nullable|string|max:20',
            'date_of_birth'           => 'nullable|date',
            'place_of_birth'          => 'nullable|string|max:255',
            'nationality'             => 'nullable|string|max:100',
            'mother_tongue'           => 'nullable|string|max:100',
            'religion'                => 'nullable|string|max:100',
            'civil_status'            => 'nullable|string|max:50',
            'lrn'                     => 'nullable|string|max:50',
            'home_address'            => 'nullable|string|max:255',
            'city'                    => 'nullable|string|max:100',
            'province'                => 'nullable|string|max:100',
            'zip_code'                => 'nullable|string|max:10',
            'mobile_number'           => 'nullable|string|max:20',
            'personal_email'          => 'nullable|email|max:255',
            'emergency_contact_number'=> 'nullable|string|max:20',
            'father_name'             => 'nullable|string|max:255',
            'father_contact'          => 'nullable|string|max:20',
            'mother_name'             => 'nullable|string|max:255',
            'mother_maiden_name'      => 'nullable|string|max:255',
            'mother_contact'          => 'nullable|string|max:20',
            'guardian_name'           => 'nullable|string|max:255',
            'guardian_relationship'   => 'nullable|string|max:100',
            'guardian_contact'        => 'nullable|string|max:20',
            'guardian_address'        => 'nullable|string|max:255',
            'guardian_occupation'     => 'nullable|string|max:255',
            'guardian_email'          => 'nullable|email|max:255',
        ]);

        $student->update($validated);

        DB::table('audit_log')->insert([
            'student_id'      => $student->id,
            'action'          => 'profile_updated',
            'action_type'     => 'update',
            'action_category' => 'student_management',
            'new_value'       => json_encode($validated),
            'performed_by'    => auth()->id(),
            'performed_at'    => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    // ══════════════════════════════════════════════════════
    // WITHDRAWN STUDENTS LIST
    // ══════════════════════════════════════════════════════
    public function withdrawn(Request $request)
    {
        $schoolYear   = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $gradeSection = $request->get('grade_section');

        $query = Student::query()
            ->where('student_status', 'withdrawn')
            ->where('school_year', $schoolYear)
            ->when($gradeSection, fn($q) => $q->where('grade_level', $gradeSection))
            ->orderByDesc('updated_at');

        $students = $query->paginate(10)->withQueryString();

        $students->through(function ($student) {
            $enrollment = StudentEnrollment::where('student_id', $student->id)
                ->where('school_year', $student->school_year)
                ->first();

            $student->section_name         = $enrollment?->section_name ?? '—';
            $student->section_display_name = \App\Models\Section::formatName(
                $student->grade_level ?? '—',
                $enrollment?->section_name ?? '—',
                $enrollment?->strand
            );
            $student->withdrawn_at       = $student->updated_at;
            $student->withdrawal_reason  = $student->withdrawal_reason ?? '—';
            $student->withdrawal_details = $student->withdrawal_details ?? '—';

            // Clearance components — pull from clearance_table when available
            $clearance = DB::table('clearance_table')
                ->where('student_id', $student->id)
                ->where('school_year', $student->school_year)
                ->first();

            $student->finance_status    = $clearance?->finance_status    ?? 'pending';
            $student->library_status    = $clearance?->library_status    ?? 'pending';
            $student->records_status    = $clearance?->records_status    ?? 'pending';
            $student->academic_standing = $clearance?->academic_standing ?? 'pending';

            // Financial — placeholder until finance module is built
            $student->total_assessment    = 0;
            $student->amount_paid         = 0;
            $student->outstanding_balance = 0;
            $student->refund_processed    = false;
            $student->refund_amount       = 0;

            // Audit
            $auditLog = DB::table('audit_log')
                ->where('student_id', $student->id)
                ->where('action', 'student_withdrawn')
                ->orderByDesc('performed_at')
                ->first();

            $performer = $auditLog?->performed_by
                ? DB::table('users')->where('id', $auditLog->performed_by)->value('name')
                : null;

            $student->withdrawn_by_name = $performer ?? 'Admin';
            $student->notification_sent = true;

            return $student;
        });

        return view('admin.student-records.withdrawn', compact('students'));
    }

    // ══════════════════════════════════════════════════════
    // WITHDRAW STUDENT
    // ══════════════════════════════════════════════════════
    public function withdraw(Request $request)
    {
        $request->validate([
            'student_id'     => 'required|exists:students,id',
            'reason'         => 'required|string',
            'effective_date' => 'required|date',
        ]);

        $student = Student::findOrFail($request->student_id);

        DB::transaction(function () use ($student, $request) {
            $student->update([
                'student_status'     => 'withdrawn',
                'withdrawal_reason'  => $request->reason === 'other'
                    ? $request->other_reason
                    : $request->reason,
                'withdrawal_date'    => $request->effective_date,
                'withdrawal_details' => $request->details,
            ]);

            StudentEnrollment::where('student_id', $student->id)
                ->where('school_year', $student->school_year)
                ->update(['enrollment_status' => 'withdrawn']);

            DB::table('audit_log')->insert([
                'student_id'      => $student->id,
                'action'          => 'student_withdrawn',
                'action_type'     => 'withdrawal',
                'action_category' => 'student_management',
                'new_value'       => json_encode([
                    'reason'         => $request->reason,
                    'other_reason'   => $request->other_reason,
                    'effective_date' => $request->effective_date,
                    'details'        => $request->details,
                    'notify_guardian'=> $request->notify_guardian,
                ]),
                'performed_by' => auth()->id(),
                'performed_at' => now(),
            ]);

            // TODO: dispatch notification job if $request->notify_guardian
        });

        return response()->json([
            'success' => true,
            'message' => $student->full_name . ' has been withdrawn successfully.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // SEND NOTICE (bulk)
    // ══════════════════════════════════════════════════════
    public function sendNotice(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'notice_type' => 'required|string',
            'subject'     => 'required|string|max:255',
            'message'     => 'required|string',
            'send_to'     => 'required|array',
        ]);

        $students = Student::whereIn('id', $request->student_ids)->get();
        $sent     = 0;

        foreach ($students as $student) {
            if (in_array('student', $request->send_to) && $student->personal_email) {
                DB::table('assignment_notifications')->insert([
                    'enrollment_id'     => null,
                    'student_id'        => $student->id,
                    'notification_type' => $request->notice_type,
                    'recipient_email'   => $student->personal_email,
                    'recipient_type'    => 'student',
                    'email_subject'     => $request->subject,
                    'email_body'        => $request->message,
                    'status'            => 'pending',
                    'queued_at'         => now(),
                ]);
                $sent++;
            }

            if (in_array('parent', $request->send_to) && $student->guardian_email) {
                DB::table('assignment_notifications')->insert([
                    'enrollment_id'     => null,
                    'student_id'        => $student->id,
                    'notification_type' => $request->notice_type,
                    'recipient_email'   => $student->guardian_email,
                    'recipient_type'    => 'parent',
                    'email_subject'     => $request->subject,
                    'email_body'        => $request->message,
                    'status'            => 'pending',
                    'queued_at'         => now(),
                ]);
                $sent++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notice queued for ' . $students->count() . ' student(s). ' . $sent . ' email(s) will be sent.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // EXPORT TO EXCEL / CSV
    // ══════════════════════════════════════════════════════
    public function export(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'filename'    => 'nullable|string|max:200',
        ]);

        $fields   = $request->fields ?? [];
        $students = Student::whereIn('id', $request->student_ids)
            ->orderBy('last_name')->get();

        $filename = ($request->filename ?: 'Student_List') . '.xlsx';

        $headers = [];
        if (in_array('include_id',       $fields)) $headers[] = 'Student ID';
        if (in_array('include_name',     $fields)) $headers[] = 'Student Name';
        if (in_array('include_grade',    $fields)) $headers[] = 'Grade and Section';
        if (in_array('include_sy',       $fields)) $headers[] = 'School Year';
        if (in_array('include_enroll',   $fields)) $headers[] = 'Enrollment Date';
        if (in_array('include_clearance',$fields)) $headers[] = 'Clearance Status';
        if (in_array('include_status',   $fields)) $headers[] = 'Student Status';
        if (in_array('include_academic', $fields)) $headers[] = 'Academic Status';
        if (in_array('include_parent',   $fields)) $headers[] = 'Parent/Guardian Contact';
        if (in_array('include_address',  $fields)) $headers[] = 'Address';

        $rows = [$headers];
        foreach ($students as $s) {
            $enrollment = StudentEnrollment::where('student_id', $s->id)
                ->where('school_year', $s->school_year)->first();
            $row = [];
            if (in_array('include_id',       $fields)) $row[] = $s->student_id;
            if (in_array('include_name',     $fields)) $row[] = $s->full_name;
            if (in_array('include_grade',    $fields)) $row[] = $s->grade_level . ' - ' . ($enrollment?->section_name ?? '—');
            if (in_array('include_sy',       $fields)) $row[] = $s->school_year;
            if (in_array('include_enroll',   $fields)) $row[] = $s->enrollment_date?->format('Y-m-d') ?? '—';
            if (in_array('include_clearance',$fields)) $row[] = ucfirst($s->clearance_status ?? '—');
            if (in_array('include_status',   $fields)) $row[] = ucfirst($s->student_status ?? '—');
            if (in_array('include_academic', $fields)) $row[] = ucfirst(str_replace('_', ' ', $s->academic_status ?? '—'));
            if (in_array('include_parent',   $fields)) $row[] = $s->guardian_contact ?? '—';
            if (in_array('include_address',  $fields)) $row[] = $s->home_address ?? '—';
            $rows[] = $row;
        }

        try {
            $csv = implode("\n", array_map(
                fn($r) => implode(',', array_map(fn($c) => '"' . str_replace('"', '""', $c) . '"', $r)),
                $rows
            ));
            return response($csv, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . str_replace('.xlsx', '.csv', $filename) . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Export failed.'], 500);
        }
    }
}