@extends('layouts.welcome')
@section('title', 'Review Your Application')

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

    <div class="max-w-2xl mx-auto px-4 pb-8">
        @include('online-registration._stepper', ['currentStep' => 4])
    </div>
</div>

{{-- ── Review ── --}}
<section style="background:#f1f5f9; padding:2.5rem 0; min-height:70vh;">
    <div class="max-w-2xl mx-auto px-4">

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:1.5rem;">
            <div style="width:36px; height:36px; background:#0d4c8f; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
                <h2 style="font-size:1rem; font-weight:800; color:#0c2340; margin:0;">Review & Submit</h2>
                <p style="font-size:0.75rem; color:#64748b; margin:0;">Please verify all details before submitting your application</p>
            </div>
        </div>

        @if ($errors->any())
        <div style="margin-bottom:1rem; border-radius:10px; border:1px solid #fecaca; background:#fef2f2; padding:12px 16px;">
            <p style="font-size:0.82rem; font-weight:700; color:#dc2626; margin:0 0 4px;">Please fix the following:</p>
            <ul style="list-style:disc; padding-left:1.25rem; margin:0; font-size:0.8rem; color:#dc2626; line-height:1.8;">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        @php
        $sectionStyle = 'background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 10px rgba(0,0,0,0.04); margin-bottom:1rem; overflow:hidden;';
        $headerStyle  = 'display:flex; align-items:center; justify-content:space-between; padding:0.9rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafafa;';
        $labelStyle   = 'font-size:0.65rem; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#0d4c8f;';
        $bodyStyle    = 'padding:1.25rem;';
        $rowStyle     = 'display:grid; grid-template-columns:1fr 1.5fr; gap:4px 1rem; margin-bottom:0.6rem;';
        $keyStyle     = 'font-size:0.75rem; color:#94a3b8; font-weight:600;';
        $valStyle     = 'font-size:0.82rem; color:#0c2340; font-weight:600;';
        $editStyle    = 'display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; font-weight:700; color:#0d4c8f; text-decoration:none;';
        @endphp

        {{-- Grade Level & Program --}}
        <div style="{{ $sectionStyle }}">
            <div style="{{ $headerStyle }}">
                <span style="{{ $labelStyle }}">Program Selection</span>
                <a href="{{ route('online.registration.step1') }}" style="{{ $editStyle }}">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Edit
                </a>
            </div>
            <div style="{{ $bodyStyle }}">
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Level</span>
                    <span style="{{ $valStyle }}">{{ $step1['applied_level'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Incoming Grade</span>
                    <span style="{{ $valStyle }}">{{ $step1['incoming_grade_level'] }}</span>
                </div>
                @if(!empty($step1['shs_track']))
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">SHS Track / Strand</span>
                    <span style="{{ $valStyle }}">{{ $step1['shs_track'] }}{{ !empty($step1['shs_strand']) ? ' — ' . $step1['shs_strand'] : '' }}</span>
                </div>
                @endif
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Transferee</span>
                    <span style="{{ $valStyle }}">{{ ($step1['is_transferee'] ?? false) ? 'Yes — ' . ($step1['previous_school'] ?? '') : 'No' }}</span>
                </div>
            </div>
        </div>

        {{-- Personal Information --}}
        <div style="{{ $sectionStyle }}">
            <div style="{{ $headerStyle }}">
                <span style="{{ $labelStyle }}">Personal Information</span>
                <a href="{{ route('online.registration.step2') }}" style="{{ $editStyle }}">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Edit
                </a>
            </div>
            <div style="{{ $bodyStyle }}">
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Full Name</span>
                    <span style="{{ $valStyle }}">{{ $step2['first_name'] }} {{ $step2['middle_name'] ?? '' }} {{ $step2['last_name'] }} {{ $step2['suffix'] ?? '' }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Date of Birth</span>
                    <span style="{{ $valStyle }}">{{ $step2['date_of_birth'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Gender</span>
                    <span style="{{ $valStyle }}">{{ $step2['gender'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Nationality</span>
                    <span style="{{ $valStyle }}">{{ $step2['nationality'] ?? 'Filipino' }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">LRN</span>
                    <span style="{{ $valStyle }}">{{ $step2['lrn'] ?? 'N/A' }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Mobile</span>
                    <span style="{{ $valStyle }}">{{ $step2['mobile_number'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Email</span>
                    <span style="{{ $valStyle }}">{{ $step2['personal_email'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Address</span>
                    <span style="{{ $valStyle }}">{{ $step2['home_address'] }}, {{ $step2['city'] ?? '' }}, Cavite</span>
                </div>
            </div>
        </div>

        {{-- Parent / Guardian --}}
        <div style="{{ $sectionStyle }}">
            <div style="{{ $headerStyle }}">
                <span style="{{ $labelStyle }}">Parent / Guardian Information</span>
                <a href="{{ route('online.registration.step2') }}" style="{{ $editStyle }}">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Edit
                </a>
            </div>
            <div style="{{ $bodyStyle }}">
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Father</span>
                    <span style="{{ $valStyle }}">{{ $step2['father_name'] ?? 'N/A' }}  <span style="font-weight:400; color:#64748b;">· {{ $step2['father_contact'] ?? 'N/A' }}</span></span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Mother</span>
                    <span style="{{ $valStyle }}">{{ $step2['mother_maiden_name'] ?? 'N/A' }}  <span style="font-weight:400; color:#64748b;">· {{ $step2['mother_contact'] ?? 'N/A' }}</span></span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Guardian</span>
                    <span style="{{ $valStyle }}">{{ $step2['guardian_name'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Guardian Contact</span>
                    <span style="{{ $valStyle }}">{{ $step2['guardian_contact'] }}</span>
                </div>
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">Guardian Email</span>
                    <span style="{{ $valStyle }}">{{ $step2['guardian_email'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div style="{{ $sectionStyle }}">
            <div style="{{ $headerStyle }}">
                <span style="{{ $labelStyle }}">Documents Uploaded</span>
                <a href="{{ route('online.registration.step3') }}" style="{{ $editStyle }}">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Edit
                </a>
            </div>
            <div style="{{ $bodyStyle }}">
                @foreach([
                    ['PSA Birth Certificate', 'psa_uploaded', 'psa_filename'],
                    ['Report Card (Form 138)', 'report_card_uploaded', 'report_card_filename'],
                    ['Good Moral Certificate', 'good_moral_uploaded', 'good_moral_filename'],
                ] as [$docLabel, $uploadedKey, $filenameKey])
                <div style="{{ $rowStyle }}">
                    <span style="{{ $keyStyle }}">{{ $docLabel }}</span>
                    @if($step3[$uploadedKey] ?? false)
                        <span style="font-size:0.78rem; font-weight:600; color:#16a34a; display:flex; align-items:center; gap:4px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ $step3[$filenameKey] }}
                        </span>
                    @else
                        <span style="font-size:0.78rem; color:#94a3b8;">Not uploaded</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Consent & Submit --}}
        <form method="POST" action="{{ route('online.registration.submit') }}">
            @csrf

            <div style="background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 10px rgba(0,0,0,0.04); padding:1.5rem; margin-bottom:1.25rem;">
                <p style="font-size:0.65rem; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#0d4c8f; margin:0 0 1rem;">Data Privacy Consent</p>

                <p style="font-size:0.8rem; color:#475569; line-height:1.7; margin:0 0 0.75rem;">
                    In compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>, I hereby give my consent to
                    <strong>My Messiah School of Cavite</strong> to collect, process, store, and use the personal information I have
                    provided in this application for admission and enrollment purposes.
                </p>
                <p style="font-size:0.8rem; color:#475569; line-height:1.7; margin:0 0 1.25rem;">
                    I certify that all information provided is true and correct. I understand that any false information may result in disqualification from admission.
                </p>

                <label style="display:flex; align-items:flex-start; gap:10px; margin-bottom:0.75rem; cursor:pointer;">
                    <input type="checkbox" name="consent_read" value="1"
                        style="margin-top:2px; width:15px; height:15px; accent-color:#0d4c8f; flex-shrink:0;">
                    <span style="font-size:0.8rem; color:#374151; line-height:1.5;">I have read and understood the terms above.</span>
                </label>
                @error('consent_read')<p style="font-size:0.72rem; color:#ef4444; margin:-4px 0 8px 25px;">{{ $message }}</p>@enderror

                <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="consent_privacy" value="1"
                        style="margin-top:2px; width:15px; height:15px; accent-color:#0d4c8f; flex-shrink:0;">
                    <span style="font-size:0.8rem; color:#374151; line-height:1.5;">I consent to the collection and processing of my personal information.</span>
                </label>
                @error('consent_privacy')<p style="font-size:0.72rem; color:#ef4444; margin:4px 0 0 25px;">{{ $message }}</p>@enderror
            </div>

            {{-- Buttons --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:2rem;">
                <a href="{{ route('online.registration.step3') }}"
                   style="display:inline-flex; align-items:center; gap:6px; padding:11px 24px; border-radius:8px; border:1.5px solid #cbd5e1; background:#fff; font-size:0.82rem; font-weight:700; color:#64748b; text-decoration:none; letter-spacing:0.05em;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back
                </a>
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:6px; padding:11px 28px; border-radius:8px; background:#0d4c8f; color:#fff; font-size:0.82rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border:none; cursor:pointer; transition:background 0.2s;"
                    onmouseover="this.style.background='#093462'" onmouseout="this.style.background='#0d4c8f'">
                    Submit Application
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

        </form>
    </div>
</section>

@endsection
