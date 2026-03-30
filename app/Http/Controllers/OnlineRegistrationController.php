<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class OnlineRegistrationController extends Controller
{
    // ── Show step 1 ──
    public function step1()
    {
        return view('online-registration.step1', [
            'data' => session('reg_step1', []),
        ]);
    }

    // ── Save step 1 ──
    public function saveStep1(Request $request)
    {
        $validated = $request->validate([
            'applied_level'         => 'required|in:Elementary,Junior High School,Senior High School',
            'incoming_grade_level'  => 'required|string|max:50',
            'student_status'        => 'required|in:Old,New',
            'student_category'      => 'required|in:Regular Payee,SHS Voucher Recipient,ESC Grantee',
            'is_transferee'         => 'nullable|boolean',
            'previous_school'       => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string',
        ]);

        session(['reg_step1' => $validated]);

        return redirect()->route('online.registration.step2');
    }

    // ── Show step 2 ──
    public function step2()
    {
        if (!session('reg_step1')) {
            return redirect()->route('online.registration.step1');
        }

        return view('online-registration.step2', [
            'data' => session('reg_step2', []),
        ]);
    }

    // ── Save step 2 ──
    public function saveStep2(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'middle_name'   => 'nullable|string|max:100',
            'last_name'     => 'required|string|max:100',
            'suffix'        => 'nullable|string|max:20',
            'gender'        => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before:today',
            'lrn'           => 'nullable|string|max:50',
            'nationality'   => 'nullable|string|max:100',
            'mother_tongue' => 'nullable|string|max:100',
            'religion'      => 'nullable|string|max:100',
            'personal_email'=> 'required|email|max:255',
            'confirm_email' => 'required|same:personal_email',
            'mobile_number' => 'required|string|max:20',
            'home_address'  => 'required|string',
            'city'          => 'nullable|string|max:100',
            'zip_code'      => 'nullable|string|max:10',
            // Parent/Guardian
            'father_name'           => 'nullable|string|max:255',
            'father_contact'        => 'nullable|string|max:20',
            'mother_name'           => 'nullable|string|max:255',
            'mother_maiden_name'    => 'nullable|string|max:255',
            'mother_contact'        => 'nullable|string|max:20',
            'guardian_name'         => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:50',
            'guardian_contact'      => 'required|string|max:50',
            'guardian_address'      => 'required|string',
            'guardian_occupation'   => 'nullable|string|max:100',
            'guardian_email'        => 'nullable|email|max:255',
            'emergency_contact_number' => 'required|string|max:20',
        ]);

        session(['reg_step2' => $validated]);

        return redirect()->route('online.registration.step3');
    }

    // ── Show step 3 (documents) ──
    public function step3()
    {
        if (!session('reg_step1') || !session('reg_step2')) {
            return redirect()->route('online.registration.step1');
        }

        return view('online-registration.step3', [
            'data' => session('reg_step3', []),
        ]);
    }

    // ── Save step 3 ──
    public function saveStep3(Request $request)
    {
        $request->validate([
            'psa'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'good_moral'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $docData = [];

        foreach (['psa', 'report_card', 'good_moral'] as $doc) {
            if ($request->hasFile($doc)) {
                $file = $request->file($doc);
                $filename = time() . '_' . $doc . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('applications/documents', $filename, 'public');
                $docData[$doc . '_uploaded'] = true;
                $docData[$doc . '_filename'] = $file->getClientOriginalName();
                $docData[$doc . '_path']     = $path;
            } else {
                $docData[$doc . '_uploaded'] = false;
                $docData[$doc . '_filename'] = null;
                $docData[$doc . '_path']     = null;
            }
        }

        session(['reg_step3' => $docData]);

        return redirect()->route('online.registration.review');
    }

    // ── Show review (step 4) ──
    public function review()
    {
        if (!session('reg_step1') || !session('reg_step2')) {
            return redirect()->route('online.registration.step1');
        }

        return view('online-registration.review', [
            'step1' => session('reg_step1'),
            'step2' => session('reg_step2'),
            'step3' => session('reg_step3', []),
        ]);
    }

    // ── Submit application ──
    public function submit(Request $request)
    {
        $request->validate([
            'consent_read'    => 'required|accepted',
            'consent_privacy' => 'required|accepted',
        ], [
            'consent_read.required'    => 'Please read and confirm the terms.',
            'consent_privacy.required' => 'Please consent to data privacy policy.',
        ]);

        $step1 = session('reg_step1');
        $step2 = session('reg_step2');
        $step3 = session('reg_step3', []);

        if (!$step1 || !$step2) {
            return redirect()->route('online.registration.step1')
                ->with('error', 'Session expired. Please start again.');
        }

        // Create application record
        $application = Application::create(array_merge(
            $step1,
            $step2,
            $step3,
            [
                'reference_number'    => Application::generateReferenceNumber(),
                'application_status'  => 'pending',
                'consent_given'       => true,
                'consent_date'        => now(),
                'parent_name_consent' => $step2['guardian_name'] ?? null,
                'submitted_at'        => now(),
                'school_year'         => '2026-2027',
            ]
        ));

        // Clear session
        session()->forget(['reg_step1', 'reg_step2', 'reg_step3']);

        // Store reference in session for confirmation page
        session(['last_application_ref' => $application->reference_number]);

        return redirect()->route('online.registration.confirmation', $application->reference_number);
    }

    // ── Confirmation page ──
    public function confirmation(string $ref)
    {
        $application = Application::where('reference_number', $ref)->firstOrFail();

        return view('online-registration.confirmation', compact('application'));
    }

    // ── Download PDF ──
    public function downloadPdf(string $ref)
    {
        $application = Application::where('reference_number', $ref)->firstOrFail();

        $pdf = Pdf::loadView('online-registration.pdf', compact('application'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('application-' . $ref . '.pdf');
    }
}