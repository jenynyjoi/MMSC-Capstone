@extends('layouts.welcome')
@section('title', 'Application Submitted — MMSC')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family: 'Montserrat', sans-serif; }
</style>

{{-- ── Registration Header ── --}}
<div style="padding-top:64px; background:linear-gradient(135deg, #0c2340 0%, #0d4c8f 60%, #0891b2 100%);">

    <div class="max-w-3xl mx-auto px-4 pt-8 pb-6 text-center">
        <p style="font-size:0.65rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#7dd3fc; margin-bottom:4px;">My Messiah School of Cavite</p>
        <h1 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 2px;">Online Admission Application</h1>
        <p style="font-size:0.8rem; color:rgba(255,255,255,0.65);">Academic Year 2026–2027</p>
    </div>

    {{-- All steps complete --}}
    <div class="max-w-2xl mx-auto px-4 pb-8">
        @php $currentStep = 5; @endphp
        @include('online-registration._stepper', ['currentStep' => $currentStep])
    </div>
</div>

{{-- ── Confirmation ── --}}
<section style="background:#f1f5f9; padding:3rem 0; min-height:70vh;">
    <div style="max-width:600px; margin:0 auto; padding:0 1rem;">

        {{-- Success icon + heading --}}
        <div style="text-align:center; margin-bottom:1.75rem;">
            <div style="width:72px; height:72px; background:#dcfce7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="38" height="38" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2 style="font-size:1.5rem; font-weight:900; color:#0c2340; text-transform:uppercase; letter-spacing:0.04em; margin:0 0 6px;">Application Submitted!</h2>
            <p style="font-size:0.85rem; color:#64748b; margin:0;">
                Dear <strong style="color:#0c2340;">{{ $application->first_name }} {{ $application->last_name }}</strong>, your application has been received.
            </p>
        </div>

        {{-- Reference card --}}
        <div style="background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); overflow:hidden; margin-bottom:1.25rem;">
            <div style="background:linear-gradient(to right, #0c2340, #0d4c8f); padding:1rem 1.5rem;">
                <p style="font-size:0.65rem; font-weight:800; letter-spacing:0.18em; text-transform:uppercase; color:rgba(255,255,255,0.65); margin:0 0 4px;">Reference Number</p>
                <p style="font-size:1.6rem; font-weight:900; color:#fff; letter-spacing:0.06em; margin:0;">{{ $application->reference_number }}</p>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                @foreach([
                    ['Applicant Name',     $application->first_name . ' ' . $application->last_name],
                    ['Date Submitted',     $application->submitted_at?->format('F d, Y · g:i A') ?? '—'],
                    ['Applied Level',      $application->applied_level],
                    ['Grade Level',        $application->incoming_grade_level],
                    ['Student Category',   $application->student_category],
                    ['School Year',        $application->school_year],
                    ['Status',             'PENDING REVIEW'],
                ] as [$key, $val])
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0; border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.72rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em;">{{ $key }}</span>
                    @if($key === 'Status')
                        <span style="font-size:0.78rem; font-weight:800; color:#d97706; background:#fef3c7; padding:3px 10px; border-radius:20px; letter-spacing:0.05em;">{{ $val }}</span>
                    @else
                        <span style="font-size:0.82rem; font-weight:600; color:#0c2340;">{{ $val }}</span>
                    @endif
                </div>
                @endforeach

                {{-- ESC / Voucher info if applicable --}}
                @if($application->student_category !== 'Regular Payee' && $application->subsidy_prev_school_type)
                @php
                    $subsidyMap = [
                        'public'             => 'Public Elementary School',
                        'private'            => 'Private Elementary School',
                        'public_jhs'         => 'Public JHS Graduate (FREE tuition)',
                        'private_jhs_esc'    => 'Private JHS with ESC (₱14,000)',
                        'private_jhs_no_esc' => 'Private JHS without ESC (Regular rate)',
                    ];
                @endphp
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0; border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.72rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em;">Previous School Type</span>
                    <span style="font-size:0.82rem; font-weight:600; color:#0c2340;">{{ $subsidyMap[$application->subsidy_prev_school_type] ?? $application->subsidy_prev_school_type }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Email notice --}}
        <div style="display:flex; align-items:flex-start; gap:10px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-bottom:1.25rem;">
            <svg width="16" height="16" fill="none" stroke="#0d4c8f" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p style="font-size:0.78rem; color:#1e40af; margin:0; line-height:1.6;">
                A confirmation email with your <strong>application form (PDF)</strong> has been sent to <strong>{{ $application->personal_email }}</strong>. Please check your inbox.
            </p>
        </div>

        {{-- What's next --}}
        <div style="background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:1.5rem; margin-bottom:1.25rem;">
            <p style="font-size:0.65rem; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#0d4c8f; margin:0 0 1rem;">What's Next?</p>

            <div style="display:grid; gap:0.75rem;">

                {{-- Option 1: Visit --}}
                <div style="padding:1rem 1.25rem; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:0.6rem;">
                        <div style="width:24px; height:24px; border-radius:50%; background:#0d4c8f; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <span style="font-size:0.65rem; font-weight:800; color:#fff;">1</span>
                        </div>
                        <p style="font-size:0.82rem; font-weight:800; color:#0c2340; margin:0;">Visit the Registrar's Office <span style="font-weight:500; color:#64748b; font-size:0.75rem;">(recommended)</span></p>
                    </div>
                    <div style="padding-left:32px;">
                        <p style="font-size:0.75rem; color:#475569; margin:0 0 4px;"><strong>Location:</strong> Registrar's Office, Ground Floor, MMSC</p>
                        <p style="font-size:0.75rem; color:#475569; margin:0 0 4px;"><strong>Hours:</strong> Monday – Friday, 7:30 AM – 5:00 PM</p>
                        <p style="font-size:0.72rem; color:#0d4c8f; font-weight:700; margin:6px 0 4px;">Bring these original documents:</p>
                        <ul style="font-size:0.72rem; color:#475569; list-style:disc; padding-left:1.25rem; margin:0; line-height:1.9;">
                            <li>Printed copy of this confirmation</li>
                            <li>PSA Birth Certificate</li>
                            <li>Report Card (Form 138) — for transferees</li>
                            <li>Good Moral Certificate</li>
                            <li>2 pcs 2×2 ID picture</li>
                        </ul>
                        <p style="font-size:0.72rem; color:#16a34a; font-weight:700; margin:6px 0 0;">No appointment needed. Walk-in welcome!</p>
                    </div>
                </div>

                {{-- Option 2: Wait --}}
                <div style="padding:1rem 1.25rem; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:0.6rem;">
                        <div style="width:24px; height:24px; border-radius:50%; background:#64748b; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <span style="font-size:0.65rem; font-weight:800; color:#fff;">2</span>
                        </div>
                        <p style="font-size:0.82rem; font-weight:800; color:#0c2340; margin:0;">Wait for Online Assessment</p>
                    </div>
                    <div style="padding-left:32px;">
                        <ol style="font-size:0.75rem; color:#475569; list-style:decimal; padding-left:1.25rem; margin:0; line-height:2;">
                            <li>Check your email for a confirmation message</li>
                            <li>Wait 2–3 business days for initial assessment</li>
                            <li>Submit original documents when notified</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="{{ route('online.registration.download-pdf', $application->reference_number) }}"
               style="flex:1; min-width:180px; display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#0d4c8f; color:#fff; font-weight:700; font-size:0.78rem; letter-spacing:0.08em; text-transform:uppercase; padding:13px 20px; border-radius:8px; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#093462'" onmouseout="this.style.background='#0d4c8f'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Download Application Form
            </a>
            <a href="{{ route('welcome') }}"
               style="flex:1; min-width:140px; display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#fff; border:1.5px solid #cbd5e1; color:#64748b; font-weight:700; font-size:0.78rem; letter-spacing:0.08em; text-transform:uppercase; padding:11px 20px; border-radius:8px; text-decoration:none;"
               onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Back to Home
            </a>
        </div>

        <p style="font-size:0.72rem; color:#94a3b8; text-align:center; margin-top:1.5rem; padding-bottom:1rem;">
            Questions? Contact us at
            <a href="mailto:registrar@mmsc.edu.ph" style="color:#0d4c8f; font-weight:600;">registrar@mmsc.edu.ph</a>
            or call <strong>(046) 123-4567</strong>
        </p>

    </div>
</section>

@endsection
