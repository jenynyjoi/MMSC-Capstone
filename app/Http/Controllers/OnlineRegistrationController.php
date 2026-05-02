<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationSubmittedMail;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OnlineRegistrationController extends Controller
{
    // ── STEP 1 ──────────────────────────────────────────────
    public function step1()
    {
        return view('online-registration.step1', [
            'data' => session('reg_step1', []),
        ]);
    }

    public function saveStep1(Request $request)
    {
        $level     = $request->input('applied_level');
        $isSHS     = $level === 'Senior High School';
        $isJHS     = $level === 'Junior High School';

        $category  = $request->input('student_category');
        // Guard: ESC is JHS-only, Voucher is SHS-only — silently reset if level mismatch
        if ($category === 'ESC Grantee' && !$isJHS) $category = 'Regular Payee';
        if ($category === 'SHS Voucher Recipient' && !$isSHS) $category = 'Regular Payee';

        $isEsc     = $category === 'ESC Grantee';
        $isVoucher = $category === 'SHS Voucher Recipient';

        $rules = [
            'applied_level'           => 'required|in:Elementary,Junior High School,Senior High School',
            'incoming_grade_level'    => 'required|string|max:50',
            'student_status'          => 'required|in:Old,New',
            'student_category'        => 'required|in:Regular Payee,SHS Voucher Recipient,ESC Grantee',
            'is_transferee'           => 'nullable|boolean',
            'previous_school'         => in_array($request->incoming_grade_level, ['Grade 7', 'Grade 11'])
                                         ? 'required|string|max:255' : 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string',
            'subsidy_certificate_no'  => 'nullable|string|max:100',
        ];

        if ($isEsc) {
            $rules['subsidy_prev_school_type'] = 'required|in:public,private';
        } elseif ($isVoucher) {
            $rules['subsidy_prev_school_type'] = 'required|in:public_jhs,private_jhs_esc,private_jhs_no_esc';
        } else {
            $rules['subsidy_prev_school_type'] = 'nullable|string|max:50';
        }

        if ($isSHS) {
            $rules['track']            = 'required|string|max:100';
            $rules['strand']           = 'required|string|max:100';
            $rules['shs_student_type'] = 'required|in:Regular,Irregular';
        }

        $request->validate($rules, [
            'applied_level.required'              => 'Please select a grade level.',
            'incoming_grade_level.required'       => 'Please select an incoming grade level.',
            'student_status.required'             => 'Please select student status.',
            'track.required'                      => 'Please select an academic track for SHS.',
            'strand.required'                     => 'Please select a strand for SHS.',
            'shs_student_type.required'           => 'Please select student type (Regular/Irregular).',
            'previous_school.required'            => 'Previous school is required for Grade 7 and Grade 11 applicants.',
            'subsidy_prev_school_type.required'   => 'Please select your previous school type to verify eligibility.',
            'subsidy_prev_school_type.in'         => 'Invalid school type selected.',
        ]);

        $data = [
            'applied_level'           => $request->applied_level,
            'incoming_grade_level'    => $request->incoming_grade_level,
            'student_status'          => $request->student_status,
            'student_category'         => $category,
            // ESC / Voucher eligibility (null if Regular Payee)
            'subsidy_prev_school_type' => ($isEsc || $isVoucher) ? $request->subsidy_prev_school_type : null,
            'subsidy_certificate_no'   => $isEsc ? $request->subsidy_certificate_no : null,
            'is_transferee'           => $request->boolean('is_transferee'),
            'previous_school'         => $request->previous_school,
            'previous_school_address' => $request->previous_school_address,
            // SHS fields (null if not SHS)
            'track'                   => $isSHS ? $request->track            : null,
            'strand'                  => $isSHS ? $request->strand           : null,
            'shs_student_type'        => $isSHS ? $request->shs_student_type : null,
        ];

        session(['reg_step1' => $data]);

        return redirect()->route('online.registration.step2');
    }

    // ── STEP 2 ──────────────────────────────────────────────
    public function step2()
    {
        if (!session('reg_step1')) {
            return redirect()->route('online.registration.step1');
        }

        return view('online-registration.step2', [
            'data' => session('reg_step2', []),
        ]);
    }

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
            'father_name'              => 'nullable|string|max:255',
            'father_contact'           => 'nullable|string|max:20',
            'mother_name'              => 'nullable|string|max:255',
            'mother_maiden_name'       => 'nullable|string|max:255',
            'mother_contact'           => 'nullable|string|max:20',
            'guardian_name'            => 'required|string|max:255',
            'guardian_relationship'    => 'required|string|max:50',
            'guardian_contact'         => 'required|string|max:50',
            'guardian_address'         => 'required|string',
            'guardian_occupation'      => 'nullable|string|max:100',
            'guardian_email'           => 'nullable|email|max:255',
        ]);

        session(['reg_step2' => $validated]);

        return redirect()->route('online.registration.step3');
    }

    // ── STEP 3 ──────────────────────────────────────────────
    public function step3()
    {
        if (!session('reg_step1') || !session('reg_step2')) {
            return redirect()->route('online.registration.step1');
        }

        return view('online-registration.step3', [
            'data' => session('reg_step3', []),
        ]);
    }

    public function saveStep3(Request $request)
    {
        $request->validate([
            'psa'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'good_moral'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $ref     = 'temp-' . session()->getId();
        $docData = [];

        foreach (['psa', 'report_card', 'good_moral'] as $doc) {
            if ($request->hasFile($doc)) {
                $file     = $request->file($doc);
                $filename = time() . '_' . $doc . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('applications/' . $ref . '/documents', $filename, 'public');

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

    // ── REVIEW ──────────────────────────────────────────────
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

    // ── SUBMIT ──────────────────────────────────────────────
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
                'school_year'         => \App\Models\SchoolYear::activeName(),
            ]
        ));

        // Move temp docs to final reference folder
        $this->moveDocuments($application, $step3);

        // Send confirmation email with PDF attachment
        try {
            Mail::to($application->personal_email)->send(new ApplicationSubmittedMail($application));
        } catch (\Throwable $e) {
            Log::error('ApplicationSubmittedMail failed', [
                'ref'   => $application->reference_number,
                'email' => $application->personal_email,
                'error' => $e->getMessage(),
            ]);
        }

        session()->forget(['reg_step1', 'reg_step2', 'reg_step3']);

        return redirect()->route('online.registration.confirmation', $application->reference_number);
    }

    // ── CONFIRMATION ─────────────────────────────────────────
    public function confirmation(string $ref)
    {
        $application = Application::where('reference_number', $ref)->firstOrFail();
        return view('online-registration.confirmation', compact('application'));
    }

    // ── DOWNLOAD PDF ─────────────────────────────────────────
    public function downloadPdf(string $ref)
    {
        $application = Application::where('reference_number', $ref)->firstOrFail();
        $pdf = Pdf::loadView('online-registration.pdf', compact('application'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('application-' . $ref . '.pdf');
    }

    // ── Move temp documents to final path ────────────────────
    private function moveDocuments(Application $application, array $step3): void
    {
        $ref       = $application->reference_number;
        $tempBase  = 'applications/temp-' . session()->getId() . '/documents';
        $finalBase = 'applications/' . $ref;

        foreach (['psa', 'report_card', 'good_moral'] as $doc) {
            $key = $doc . '_path';
            if (!empty($step3[$key])) {
                $oldPath  = $step3[$key];
                $filename = basename($oldPath);
                $newPath  = $finalBase . '/documents/' . $filename;

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $newPath);
                    $application->update([$key => $newPath]);
                }
            }
        }

        // Clean up temp dir
        if (Storage::disk('public')->exists($tempBase)) {
            Storage::disk('public')->deleteDirectory($tempBase);
        }
    }
}