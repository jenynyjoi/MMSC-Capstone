<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmissionReviewController extends Controller
{
    // ── List all applications ──────────────────────────────
    public function index(Request $request)
    {
        $query = Application::query();

        if ($request->filled('status')) {
            $query->where('application_status', $request->status);
        }
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }
        if ($request->filled('level')) {
            $query->where('applied_level', $request->level);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%$s%")
                  ->orWhere('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('personal_email', 'like', "%$s%");
            });
        }

        $applications = $query->latest('submitted_at')->paginate(10)->withQueryString();

        $stats = [
            'approved'   => Application::where('application_status', 'approved')->count(),
            'pending'    => Application::where('application_status', 'pending')->count(),
            'incomplete' => Application::where('application_status', 'incomplete')->count(),
            'rejected'   => Application::where('application_status', 'rejected')->count(),
        ];

        return view('admin.admission', compact('applications', 'stats'));
    }

    // ── Show single application (view modal) ───────────────
    public function show(int $id)
    {
        $application = Application::findOrFail($id);
        return view('admin.admission-show', compact('application'));
    }

    // ── Download application as PDF ────────────────────────
    public function downloadPdf(int $id)
    {
        $application = Application::findOrFail($id);
        $pdf = Pdf::loadView('online-registration.pdf', compact('application'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('application-' . $application->reference_number . '.pdf');
    }

    // ── Update single application status ──────────────────
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,pre_approved,approved,rejected,incomplete',
            'reason' => 'nullable|string|max:1000',
        ]);

        $application = Application::findOrFail($id);
        $oldStatus   = $application->application_status;
        $newStatus   = $request->status;

        $application->update(['application_status' => $newStatus]);

        // Trigger auto-creation when approved
        if ($newStatus === 'approved' && $oldStatus !== 'approved') {
            $this->approveAndTransfer($application);
        }

        return back()->with('success', 'Application status updated to ' . ucfirst($newStatus) . '.');
    }

    // ── Bulk update status ─────────────────────────────────
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:applications,id',
            'status' => 'required|in:pending,pre_approved,approved,rejected,incomplete',
            'action' => 'required|in:approve,mark_incomplete,send_notice,reject',
        ]);

        $applications = Application::whereIn('id', $request->ids)->get();

        foreach ($applications as $app) {
            $oldStatus = $app->application_status;
            $app->update(['application_status' => $request->status]);

            if ($request->status === 'approved' && $oldStatus !== 'approved') {
                $this->approveAndTransfer($app);
            }
        }

        return back()->with('success', count($request->ids) . ' application(s) updated to ' . ucfirst($request->status) . '.');
    }

    // ── Send email notice ──────────────────────────────────
    public function sendNotice(Request $request)
    {
        $request->validate([
            'ids'          => 'required|array',
            'ids.*'        => 'integer|exists:applications,id',
            'subject'      => 'required|string|max:255',
            'message_type' => 'required|string',
            'details'      => 'nullable|string|max:2000',
            'send_copy'    => 'nullable|boolean',
        ]);

        $applications = Application::whereIn('id', $request->ids)->get();
        $adminEmail   = auth()->user()->email;

        foreach ($applications as $app) {
            $messageBody = $this->buildEmailBody($app, $request->message_type, $request->details);

            // Send to applicant
            Mail::raw($messageBody, function ($mail) use ($app, $request) {
                $mail->to($app->personal_email)
                     ->subject($request->subject);
                if ($app->guardian_email) {
                    $mail->cc($app->guardian_email);
                }
            });
        }

        // Send copy to admin
        if ($request->send_copy) {
            $summary = "Notice sent to " . count($applications) . " applicant(s).\nMessage type: {$request->message_type}\n\n{$request->details}";
            Mail::raw($summary, function ($mail) use ($adminEmail, $request) {
                $mail->to($adminEmail)->subject('[COPY] ' . $request->subject);
            });
        }

        return back()->with('success', 'Notice sent to ' . count($applications) . ' applicant(s).');
    }

    // ── Build email body based on type ─────────────────────
    private function buildEmailBody(Application $app, string $type, ?string $details): string
    {
        $name = $app->first_name . ' ' . $app->last_name;
        $ref  = $app->reference_number;
        $school = 'My Messiah School of Cavite';

        $intro = "Dear {$name},\n\nThank you for applying to {$school}. This is an update regarding your application (Reference: {$ref}).\n\n";
        $footer = "\n\nFor inquiries, please contact us at registrar@mmsc.edu.ph or call (046) 123-4567.\n\nThank you.\nRegistrar's Office\n{$school}";

        $body = match ($type) {
            'Application Approved'         => "We are pleased to inform you that your application has been APPROVED.\n\nYou will receive your student portal credentials shortly. Please visit our office to complete your enrollment.\n\n" . ($details ?? ''),
            'Application Rejected'         => "We regret to inform you that your application has been REJECTED.\n\nReason: " . ($details ?? 'Please contact our office for more information.'),
            'Missing Requirements'         => "Your application requires additional documents before it can be processed.\n\nMissing Requirements:\n" . ($details ?? 'Please contact our office for the complete list.'),
            'Pending Payment'              => "Your application is pending payment confirmation.\n\nDetails:\n" . ($details ?? 'Please proceed to the cashier or contact our finance office.'),
            'Document Verification Needed' => "Your submitted documents require further verification.\n\nDetails:\n" . ($details ?? 'Please visit our registrar\'s office with your original documents.'),
            'Interview Schedule'           => "You are scheduled for an admission interview.\n\nDetails:\n" . ($details ?? 'Please contact our office for your interview schedule.'),
            'Entrance Exam Schedule'       => "You are scheduled for the entrance examination.\n\nDetails:\n" . ($details ?? 'Please contact our office for your exam schedule.'),
            'Custom Message'               => ($details ?? ''),
            default                        => ($details ?? 'Please contact our office for more information.'),
        };

        return $intro . $body . $footer;
    }

    // ── Approve and transfer to students table ─────────────
    private function approveAndTransfer(Application $application): void
    {
        // Avoid duplicate transfer
        if (Student::where('reference_number', $application->reference_number)->exists()) {
            return;
        }

        // 1. Create student record
        $student = Student::createFromApplication($application);

        // 2. Create student portal account
        $this->createStudentAccount($student, $application);

        // 3. Create or link parent account
        $this->createOrLinkParentAccount($student, $application);
    }

    // ── Create student portal account ─────────────────────
    private function createStudentAccount(Student $student, Application $application): void
    {
        $user = User::create([
            'name'     => $student->full_name,
            'email'    => $student->school_email,
            'password' => Hash::make($student->student_id),
        ]);

        $user->assignRole('student');

        $student->update([
            'user_id'                => $user->id,
            'portal_account_created' => true,
            'account_created_at'     => now(),
            'password_changed'       => false,
        ]);

        // TODO: Mail::to($application->personal_email)->send(new StudentCredentialsMail($student));
    }

    // ── Create or link parent account ─────────────────────
    private function createOrLinkParentAccount(Student $student, Application $application): void
    {
        $parentEmail = $application->guardian_email ?? $application->personal_email;

        if (!$parentEmail) return;

        $existingParent = User::where('email', $parentEmail)->first();

        if (!$existingParent) {
            // Generate random password for parent
            $tempPassword = 'MMSC-' . strtoupper(Str::random(6));

            $parent = User::create([
                'name'     => $application->guardian_name ?? ($application->father_name ?? 'Parent'),
                'email'    => $parentEmail,
                'password' => Hash::make($tempPassword),
            ]);

            $parent->assignRole('parent');

            // TODO: Mail::to($parentEmail)->send(new ParentCredentialsMail($parent, $tempPassword));
        }
        // If parent exists, just link (no new account created)
    }
}