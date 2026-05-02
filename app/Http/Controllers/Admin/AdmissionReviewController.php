<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AdmissionReviewController extends Controller
{
    // ══════════════════════════════════════════════════════
    // LIST
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Application::query();

        if ($request->filled('status'))      $query->where('application_status', $request->status);
        if ($request->filled('school_year')) $query->where('school_year', $request->school_year);
        if ($request->filled('level'))       $query->where('applied_level', $request->level);
        if ($request->filled('app_type')) {
            match($request->app_type) {
                'New'       => $query->where('is_transferee', false)->where('student_status', 'New'),
                'Return'    => $query->where('is_transferee', false)->where('student_status', 'Old'),
                'Transferee'=> $query->where('is_transferee', true),
                default     => null,
            };
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('reference_number', 'like', "%$s%")
                ->orWhere('first_name',     'like', "%$s%")
                ->orWhere('last_name',      'like', "%$s%")
                ->orWhere('personal_email', 'like', "%$s%")
            );
        }

        $applications = $query->latest('submitted_at')->paginate(10)->withQueryString();
        $stats        = $this->getStats();

        return view('admin.admission', compact('applications', 'stats'));
    }

    // ══════════════════════════════════════════════════════
    // SHOW single application
    // ══════════════════════════════════════════════════════
    public function show(int $id)
    {
        $application = Application::findOrFail($id);
        $stats       = $this->getStats();

        return view('admin.admission-show', compact('application', 'stats'));
    }

    // ══════════════════════════════════════════════════════
    // DOWNLOAD PDF
    // ══════════════════════════════════════════════════════
    public function downloadPdf(int $id)
    {
        $application = Application::findOrFail($id);
        $pdf = Pdf::loadView('online-registration.pdf', compact('application'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('application-' . $application->reference_number . '.pdf');
    }

    // ══════════════════════════════════════════════════════
    // FINANCE CLEARANCE (gate for enrollment)
    // ══════════════════════════════════════════════════════
    public function updateFinanceClearance(Request $request, int $id)
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'finance_clearance'        => 'required|in:not_set,cleared,pending,hold',
            'finance_clearance_notes'  => 'nullable|string|max:1000',
            'finance_total_assessment' => 'nullable|numeric|min:0',
            'finance_amount_paid'      => 'nullable|numeric|min:0',
            'finance_next_due_date'    => 'nullable|date',
            'finance_cleared_by'       => 'nullable|string|max:150',
        ]);

        $application->update([
            'finance_clearance'            => $request->finance_clearance,
            'finance_clearance_notes'      => $request->finance_clearance_notes,
            'finance_total_assessment'     => $request->finance_total_assessment,
            'finance_amount_paid'          => $request->finance_amount_paid,
            'finance_next_due_date'        => $request->finance_next_due_date ?: null,
            'finance_cleared_by'           => $request->finance_cleared_by,
            'finance_clearance_updated_at' => now(),
        ]);

        return response()->json([
            'success'    => true,
            'clearance'  => $application->finance_clearance,
            'notes'      => $application->finance_clearance_notes,
            'updated_at' => $application->finance_clearance_updated_at->format('M d, Y g:i A'),
        ]);
    }

    // ══════════════════════════════════════════════════════
    // UPDATE DOCUMENT STATUSES (records verification)
    // ══════════════════════════════════════════════════════
    public function updateDocuments(Request $request, int $id)
    {
        $application = Application::findOrFail($id);
        $dir = 'applications/' . $application->reference_number;

        $docs = ['psa', 'report_card', 'good_moral', 'medical'];
        $data = [];

        foreach ($docs as $key) {
            $status    = $request->input("{$key}_status", 'not_uploaded');
            $submitted = $request->boolean("{$key}_submitted");

            $data["{$key}_status"]    = in_array($status, ['not_uploaded','pending','approved']) ? $status : 'not_uploaded';
            $data["{$key}_submitted"] = $submitted;

            // Handle admin-side file upload
            if ($request->hasFile("{$key}_file")) {
                $file     = $request->file("{$key}_file");
                $filename = $key . '_admin_' . time() . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs($dir, $filename, 'public');

                $data["{$key}_uploaded"] = true;
                $data["{$key}_filename"] = $filename;
                $data["{$key}_path"]     = $path;

                // Auto-set to pending if admin uploaded but status is still not_uploaded
                if ($data["{$key}_status"] === 'not_uploaded') {
                    $data["{$key}_status"] = 'pending';
                }
            }
        }

        $application->update($data);

        return response()->json(['success' => true, 'message' => 'Document records saved.']);
    }

    // ══════════════════════════════════════════════════════
    // GET DOCUMENT DATA (JSON — for dynamic records modal in table view)
    // ══════════════════════════════════════════════════════
    public function getDocumentsData(int $id)
    {
        $application  = Application::findOrFail($id);
        $isEsc        = $application->student_category === 'ESC Grantee';
        $appApproved  = $application->application_status === 'approved';

        $autoStatus = fn($uploaded, $status, $approved) =>
            ($uploaded && ($status ?? 'not_uploaded') === 'not_uploaded')
                ? ($approved ? 'approved' : 'pending')
                : ($status ?? 'not_uploaded');

        $autoSubmitted = fn($uploaded, $submitted, $approved) =>
            ($approved && !$uploaded && !((bool)$submitted)) ? true : (bool)$submitted;

        $docs = [
            'psa' => [
                'label'     => 'PSA / Birth Certificate',
                'required'  => true,
                'uploaded'  => (bool) $application->psa_uploaded,
                'filename'  => $application->psa_filename,
                'status'    => $autoStatus($application->psa_uploaded, $application->psa_status, $appApproved),
                'submitted' => $autoSubmitted($application->psa_uploaded, $application->psa_submitted ?? false, $appApproved),
            ],
            'report_card' => [
                'label'     => 'Original Report Card / Form 137',
                'required'  => true,
                'uploaded'  => (bool) $application->report_card_uploaded,
                'filename'  => $application->report_card_filename,
                'status'    => $autoStatus($application->report_card_uploaded, $application->report_card_status, $appApproved),
                'submitted' => $autoSubmitted($application->report_card_uploaded, $application->report_card_submitted ?? false, $appApproved),
            ],
            'good_moral' => [
                'label'     => 'Good Moral Character',
                'required'  => true,
                'uploaded'  => (bool) $application->good_moral_uploaded,
                'filename'  => $application->good_moral_filename,
                'status'    => $autoStatus($application->good_moral_uploaded, $application->good_moral_status, $appApproved),
                'submitted' => $autoSubmitted($application->good_moral_uploaded, $application->good_moral_submitted ?? false, $appApproved),
            ],
            'medical' => [
                'label'     => 'Medical Clearance',
                'required'  => $isEsc,
                'uploaded'  => (bool) ($application->medical_uploaded ?? false),
                'filename'  => $application->medical_filename ?? null,
                'status'    => $autoStatus($application->medical_uploaded ?? false, $application->medical_status, $appApproved),
                'submitted' => $autoSubmitted($application->medical_uploaded ?? false, $application->medical_submitted ?? false, $appApproved),
            ],
        ];

        $requiredKeys = collect($docs)->filter(fn($d) => $d['required'])->keys()->values();

        return response()->json([
            'docs'          => $docs,
            'required_keys' => $requiredKeys,
            'save_url'      => route('admin.admission.documents', $id),
            'approve_url'   => route('admin.admission.status', $id),
        ]);
    }

    // DOWNLOAD uploaded document
    // ══════════════════════════════════════════════════════
    public function downloadDocument(int $id, string $type)
    {
        $application = Application::findOrFail($id);
        $map = [
            'psa'         => [$application->psa_uploaded,       $application->psa_filename,         $application->psa_path],
            'report_card' => [$application->report_card_uploaded, $application->report_card_filename, $application->report_card_path],
            'good_moral'  => [$application->good_moral_uploaded,  $application->good_moral_filename,  $application->good_moral_path],
        ];
        abort_if(!isset($map[$type]), 404);
        [$uploaded, $filename, $path] = $map[$type];
        abort_if(!$uploaded || !$path, 404, 'Document not uploaded.');
        abort_if(!Storage::disk('public')->exists($path), 404, 'File not found.');
        return Storage::disk('public')->download($path, $filename);
    }

    // ══════════════════════════════════════════════════════
    // EXPORT EXCEL
    // ══════════════════════════════════════════════════════
    public function exportExcel(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:applications,id',
        ]);

        $applications = Application::whereIn('id', $request->ids)->get();
        $spreadsheet  = new Spreadsheet();
        $sheet        = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Applications');

        $headers = [
            'A'=>'Reference No.','B'=>'First Name','C'=>'Middle Name','D'=>'Last Name',
            'E'=>'Gender','F'=>'Date of Birth','G'=>'LRN','H'=>'Mobile Number','I'=>'Personal Email',
            'J'=>'Applied Level','K'=>'Grade Level','L'=>'Student Status','M'=>'Student Category',
            'N'=>'Transferee','O'=>'Previous School','P'=>'Father Name','Q'=>'Father Contact',
            'R'=>'Mother Maiden Name','S'=>'Mother Contact','T'=>'Guardian Name','U'=>'Relationship',
            'V'=>'Guardian Contact','W'=>'Guardian Email',
            'X'=>'Home Address','Y'=>'City','Z'=>'ZIP Code',
            'AA'=>'Application Status','AB'=>'School Year','AC'=>'Submitted At',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '1', $label);
        }

        $sheet->getStyle('A1:AD1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D4C8F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'B8C8F0']]],
        ]);

        $row = 2;
        foreach ($applications as $app) {
            $sheet->fromArray([
                $app->reference_number, $app->first_name, $app->middle_name, $app->last_name,
                $app->gender, $app->date_of_birth?->format('Y-m-d'), $app->lrn, $app->mobile_number,
                $app->personal_email, $app->applied_level, $app->incoming_grade_level,
                $app->student_status, $app->student_category, $app->is_transferee ? 'Yes' : 'No',
                $app->previous_school, $app->father_name, $app->father_contact,
                $app->mother_maiden_name, $app->mother_contact, $app->guardian_name,
                $app->guardian_relationship, $app->guardian_contact, $app->guardian_email,
                $app->home_address, $app->city, $app->zip_code,
                $app->application_status, $app->school_year, $app->submitted_at?->format('Y-m-d H:i:s'),
            ], null, 'A' . $row);

            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:AD{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0F4FF');
            }
            $row++;
        }

        foreach (range('A', 'Z') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        foreach (['AA','AB','AC','AD'] as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->freezePane('A2');

        $filename = 'MMSC_Applications_' . date('Ymd_His') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // UPDATE SINGLE STATUS
    // ══════════════════════════════════════════════════════
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status'         => 'required|in:pending,pre_approved,approved,rejected,incomplete',
            'reason'         => 'nullable|string|max:1000',
            'lock_confirmed' => 'nullable|boolean',
        ]);

        $application = Application::findOrFail($id);
        $oldStatus   = $application->application_status;
        $newStatus   = $request->status;

        // Approval lock: warn if downgrading approved application
        $isDowngrade = ($oldStatus === 'approved') && in_array($newStatus, ['rejected', 'incomplete']);
        if ($isDowngrade && !$request->boolean('lock_confirmed')) {
            $msg = 'This application has already been approved and a student record has been created. The student enrollment record will remain active.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'lock_warning' => true, 'message' => $msg], 422);
            }
            return back()->with('warning', $msg);
        }

        $application->update(['application_status' => $newStatus]);

        Log::channel('daily')->info('Admission status changed', [
            'admin'      => auth()->user()->name ?? 'Admin',
            'app_id'     => $application->id,
            'reference'  => $application->reference_number,
            'from'       => $oldStatus,
            'to'         => $newStatus,
            'reason'     => $request->reason,
            'timestamp'  => now()->toDateTimeString(),
        ]);

        // ── TRIGGER PIPELINE on approval ──────────────
        // Guard is inside approveAndTransfer (idempotent via firstOrCreate).
        // We check student+enrollment existence so a failed pipeline can be
        // re-triggered by approving again (e.g., after fixing a DB issue).
        $pipelineError = null;
        if ($newStatus === 'approved') {
            $student = Student::where('reference_number', $application->reference_number)->first();
            $enrollmentMissing = !$student || !StudentEnrollment::where('student_id', $student->id)
                ->where('school_year', $application->school_year)->exists();

            if ($enrollmentMissing) {
                try {
                    $this->approveAndTransfer($application);
                } catch (\Exception $e) {
                    $pipelineError = $e->getMessage();
                    Log::error('approveAndTransfer failed', [
                        'application_id' => $application->id,
                        'reference'      => $application->reference_number,
                        'error'          => $e->getMessage(),
                    ]);
                }
            }
        }

        $msg = 'Application status updated to ' . ucfirst(str_replace('_', ' ', $newStatus)) . '.';
        if ($newStatus === 'approved' && !$pipelineError) {
            $msg .= ' Student record and portal accounts have been created. Student is now in the Enrollment queue.';
        } elseif ($pipelineError) {
            $msg = 'Application approved but student record creation failed. Please retry or contact IT. (' . $pipelineError . ')';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'        => true,
                'pipeline_error' => (bool) $pipelineError,
                'message'        => $msg,
                'new_status'     => $newStatus,
            ]);
        }

        $flashType = $pipelineError ? 'warning' : 'success';
        return back()->with($flashType, $msg);
    }

    // ══════════════════════════════════════════════════════
    // BULK UPDATE STATUS
    // NOTE: Route is POST only — no @method('PUT')
    // ══════════════════════════════════════════════════════
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:applications,id',
            'status' => 'required|in:pending,pre_approved,approved,rejected,incomplete',
            'action' => 'required|in:approve,mark_incomplete,send_notice,reject',
        ]);

        $applications = Application::whereIn('id', $request->ids)->get();
        $transferred  = 0;
        $locked       = 0;

        foreach ($applications as $app) {
            $oldStatus   = $app->application_status;
            $isDowngrade = ($oldStatus === 'approved') && in_array($request->status, ['rejected','incomplete']);
            if ($isDowngrade) $locked++;

            $app->update(['application_status' => $request->status]);

            Log::channel('daily')->info('Bulk admission status change', [
                'admin'     => auth()->user()->name ?? 'Admin',
                'app_id'    => $app->id,
                'reference' => $app->reference_number,
                'from'      => $oldStatus,
                'to'        => $request->status,
                'timestamp' => now()->toDateTimeString(),
            ]);

            if ($request->status === 'approved') {
                $student = Student::where('reference_number', $app->reference_number)->first();
                $enrollmentMissing = !$student || !StudentEnrollment::where('student_id', $student->id)
                    ->where('school_year', $app->school_year)->exists();
                if ($enrollmentMissing) {
                    try {
                        $this->approveAndTransfer($app);
                        $transferred++;
                    } catch (\Exception $e) {
                        Log::error('Bulk approveAndTransfer failed', [
                            'app_id'    => $app->id,
                            'reference' => $app->reference_number,
                            'error'     => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        $msg = count($request->ids) . ' application(s) updated to ' . ucfirst($request->status) . '.';
        if ($transferred > 0) {
            $msg .= " {$transferred} student record(s) created and added to the Enrollment queue.";
        }
        if ($locked > 0) {
            $msg .= " ({$locked} were already approved — their enrollment records remain active.)";
        }

        return back()->with('success', $msg);
    }

    // ══════════════════════════════════════════════════════
    // SEND EMAIL NOTICE
    // ══════════════════════════════════════════════════════
    public function sendNotice(Request $request)
    {
        $request->validate([
            'ids'          => 'required|array',
            'ids.*'        => 'integer|exists:applications,id',
            'subject'      => 'required|string|max:255',
            'message_type' => 'required|string',
            'details'      => 'nullable|string|max:2000',
            'send_copy'    => 'nullable',
        ]);

        $applications = Application::whereIn('id', $request->ids)->get();
        $adminEmail   = auth()->user()->email;

        foreach ($applications as $app) {
            $body = $this->buildEmailBody($app, $request->message_type, $request->details);
            Mail::raw($body, function ($mail) use ($app, $request) {
                $mail->to($app->personal_email)->subject($request->subject);
                if ($app->guardian_email) $mail->cc($app->guardian_email);
            });
        }

        if ($request->send_copy) {
            $summary = "Notice sent to " . count($applications) . " applicant(s).\nType: {$request->message_type}\n\n{$request->details}";
            Mail::raw($summary, fn($m) => $m->to($adminEmail)->subject('[COPY] ' . $request->subject));
        }

        return back()->with('success', 'Notice sent to ' . count($applications) . ' applicant(s).');
    }

    // ══════════════════════════════════════════════════════
    // PRIVATE: APPROVAL PIPELINE
    //
    //   Application approved
    //       ↓
    //   Student record created  (students table)
    //       ↓
    //   StudentEnrollment created (student_enrollment table)
    //       — student now appears in Enrollment → Pending Section Assignment
    //       ↓
    //   Student portal account created  (users table)
    //       ↓
    //   Parent portal account created/linked  (users table)
    //       ↓
    //   Credentials email queued
    // ══════════════════════════════════════════════════════
    private function approveAndTransfer(Application $application): void
    {
        // Step 1 — Create Student record (idempotent: skip if already exists)
        $student = Student::where('reference_number', $application->reference_number)->first();
        if (!$student) {
            $student = Student::createFromApplication($application);
        }

        // Step 2 — Link any finance config saved during review to the new student record
        \App\Http\Controllers\Admin\FinanceController::linkToStudent(
            $application->reference_number,
            $student->id
        );

        // Step 3 — Create StudentEnrollment record (idempotent via firstOrCreate)
        //           → student appears in Enrollment > Pending Section Assignment
        StudentEnrollment::createFromApplication($application, $student);

        // Step 4 — Create student portal account (idempotent: skipped if already exists)
        $this->createStudentPortalAccount($student);

        // Step 5 — Create parent/guardian portal account
        $this->createParentPortalAccount($student);
    }

    // ── Portal: student account ────────────────────────────
    private function createStudentPortalAccount(Student $student): void
    {
        if ($student->portal_account_created) return;

        $schoolEmail  = $student->school_email;
        $tempPassword = $student->reference_number ?? Str::random(10);

        if (!$schoolEmail || User::where('email', $schoolEmail)->exists()) return;

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

        try {
            $this->sendStudentApprovalEmail($student, $schoolEmail, $tempPassword);
        } catch (\Exception $e) {
            Log::warning("Student credentials email failed [{$student->id}]: " . $e->getMessage());
        }
    }

    // ── Portal: parent account ─────────────────────────────
    private function createParentPortalAccount(Student $student): void
    {
        $guardianEmail = $student->guardian_email;
        if (!$guardianEmail) return;

        $tempPassword = $student->reference_number ?? Str::random(10);

        if (User::where('email', $guardianEmail)->exists()) return;

        $parent = User::create([
            'name'     => $student->guardian_name ?? 'Parent/Guardian',
            'email'    => $guardianEmail,
            'password' => Hash::make($tempPassword),
        ]);
        $parent->assignRole('parent');

        try {
            $this->sendParentApprovalEmail($student, $guardianEmail, $tempPassword);
        } catch (\Exception $e) {
            Log::warning("Parent credentials email failed [{$student->id}]: " . $e->getMessage());
        }
    }

    // ── Email: student portal credentials ─────────────────
    private function sendStudentApprovalEmail(Student $student, string $email, string $pass): void
    {
        $name = $student->full_name;
        $sid  = $student->student_id;
        $body = <<<TEXT
Dear {$name},

Congratulations! Your application to My Messiah School of Cavite (MMSC) has been APPROVED.

Your student portal account has been created. You may now log in to view your enrollment status and other details.

STUDENT ACCOUNT DETAILS
==================================================
Student ID:           {$sid}
School Email:         {$email}
Temporary Password:   {$pass}

LOGIN INSTRUCTIONS
==================================================
Portal URL: https://portal.mmsc.edu.ph
Username:   {$email}
Password:   {$pass}

Please change your password after your first login.
For support: it@mmsc.edu.ph

Registrar's Office — My Messiah School of Cavite
TEXT;

        Mail::raw($body, function ($mail) use ($student, $name) {
            $mail->from('registrar@mmsc.edu.ph', 'MMSC Registrar')
                 ->to($student->personal_email, $name)
                 ->subject('MMSC Application Approved — Your Student Portal Account');
            if ($student->guardian_email) {
                $mail->cc($student->guardian_email, $student->guardian_name ?? 'Guardian');
            }
        });
    }

    // ── Email: parent portal credentials ──────────────────
    private function sendParentApprovalEmail(Student $student, string $guardianEmail, string $pass): void
    {
        $parentName  = $student->guardian_name ?? 'Parent/Guardian';
        $studentName = $student->full_name;
        $grade       = $student->grade_level ?? '—';
        $body        = <<<TEXT
Dear {$parentName},

Your child {$studentName}'s application to My Messiah School of Cavite (MMSC) has been APPROVED.
A parent portal account has been created for you to monitor their enrollment and academic progress.

PARENT ACCOUNT DETAILS
==================================================
Email:              {$guardianEmail}
Temporary Password: {$pass}

LINKED STUDENT
==================================================
Student:     {$studentName}
Student ID:  {$student->student_id}
Grade Level: {$grade}

LOGIN
==================================================
Parent Portal: https://portal.mmsc.edu.ph/parent
Username:      {$guardianEmail}
Password:      {$pass}

Please change your password after your first login.
For support: it@mmsc.edu.ph

Registrar's Office — My Messiah School of Cavite
TEXT;

        Mail::raw($body, function ($mail) use ($guardianEmail, $parentName) {
            $mail->from('registrar@mmsc.edu.ph', 'MMSC Registrar')
                 ->to($guardianEmail, $parentName)
                 ->subject('MMSC — Parent Portal Account Created');
        });
    }

    // ── Email body builder (notices) ───────────────────────
    private function buildEmailBody(Application $app, string $type, ?string $details): string
    {
        $name   = $app->first_name . ' ' . $app->last_name;
        $ref    = $app->reference_number;
        $school = 'My Messiah School of Cavite';
        $intro  = "Dear {$name},\n\nThank you for applying to {$school}. This is an update regarding your application (Reference: {$ref}).\n\n";
        $footer = "\n\nFor inquiries, contact us at registrar@mmsc.edu.ph or (046) 123-4567.\n\nRegistrar's Office\n{$school}";

        $body = match ($type) {
            'Application Approved'         => "We are pleased to inform you that your application has been APPROVED.\n\n" . ($details ?? ''),
            'Application Rejected'         => "We regret to inform you that your application has been REJECTED.\n\nReason: " . ($details ?? 'Please contact our office.'),
            'Missing Requirements'         => "Your application requires additional documents.\n\n" . ($details ?? 'Please contact our office.'),
            'Pending Payment'              => "Your application is pending payment.\n\n" . ($details ?? 'Please contact our finance office.'),
            'Document Verification Needed' => "Your documents require further verification.\n\n" . ($details ?? 'Please visit our office with originals.'),
            'Interview Schedule'           => "You are scheduled for an admission interview.\n\n" . ($details ?? 'Please contact our office for the schedule.'),
            'Entrance Exam Schedule'       => "You are scheduled for the entrance examination.\n\n" . ($details ?? 'Please contact our office for the schedule.'),
            'Custom Message'               => ($details ?? ''),
            default                        => ($details ?? 'Please contact our office.'),
        };

        return $intro . $body . $footer;
    }

    // ── Stats helper ───────────────────────────────────────
    private function getStats(): array
    {
        return [
            'approved'   => Application::where('application_status', 'approved')->count(),
            'pending'    => Application::where('application_status', 'pending')->count(),
            'incomplete' => Application::where('application_status', 'incomplete')->count(),
            'rejected'   => Application::where('application_status', 'rejected')->count(),
        ];
    }
}