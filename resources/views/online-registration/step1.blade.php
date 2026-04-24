@extends('layouts.welcome')
@section('title', 'Online Registration — Step 1')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family: 'Montserrat', sans-serif; }
</style>

{{-- ── Registration Header ── --}}
<div style="padding-top:64px; background:linear-gradient(135deg, #0c2340 0%, #0d4c8f 60%, #0891b2 100%);">

    {{-- Title bar --}}
    <div class="max-w-3xl mx-auto px-4 pt-8 pb-6 text-center">
        <p style="font-size:0.65rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#7dd3fc; margin-bottom:4px;">My Messiah School of Cavite</p>
        <h1 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 2px;">Online Admission Application</h1>
        <p style="font-size:0.8rem; color:rgba(255,255,255,0.65);">Academic Year 2026–2027</p>
    </div>

    {{-- Step Indicator --}}
    <div class="max-w-2xl mx-auto px-4 pb-8">
        @php $currentStep = 1; @endphp
        @include('online-registration._stepper', ['currentStep' => $currentStep])
    </div>
</div>

{{-- ── Form ── --}}
<section style="background:#f1f5f9; padding:2.5rem 0; min-height:70vh;">
    <div class="max-w-2xl mx-auto px-4">

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:1.5rem;">
            <div style="width:36px; height:36px; background:#0d4c8f; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <h2 style="font-size:1rem; font-weight:800; color:#0c2340; margin:0;">Program Selection</h2>
                <p style="font-size:0.75rem; color:#64748b; margin:0;">Select the grade level you are applying for</p>
            </div>
        </div>

        @if ($errors->any())
        <div style="margin-bottom:1rem; border-radius:10px; border:1px solid #fecaca; background:#fef2f2; padding:12px 16px;">
            <ul style="list-style:disc; padding-left:1.25rem; margin:0; font-size:0.82rem; color:#dc2626; line-height:1.8;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('online.registration.save-step1') }}" onsubmit="return validateStep1(event)">
            @csrf

            <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:1.75rem; margin-bottom:1.5rem;">

                {{-- Grade Level --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;">
                        Grade Level / Program <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="applied_level" id="applied_level"
                        style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.88rem; font-family:inherit; background:#fff; outline:none; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">— Select Program —</option>
                        <option value="Elementary"         {{ old('applied_level', $data['applied_level'] ?? '') === 'Elementary'         ? 'selected' : '' }}>Elementary (Grades 1–6)</option>
                        <option value="Junior High School" {{ old('applied_level', $data['applied_level'] ?? '') === 'Junior High School' ? 'selected' : '' }}>Junior High School (Grades 7–10)</option>
                        <option value="Senior High School" {{ old('applied_level', $data['applied_level'] ?? '') === 'Senior High School' ? 'selected' : '' }}>Senior High School (Grades 11–12)</option>
                    </select>
                    @error('applied_level')<p style="font-size:0.75rem; color:#ef4444; margin:4px 0 0;">{{ $message }}</p>@enderror
                </div>

                {{-- Incoming Grade --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;">
                        Incoming Grade Level <span style="color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0;">(for Academic Year 2026–2027)</span> <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="incoming_grade_level" id="incoming_grade_level"
                        style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.88rem; font-family:inherit; background:#fff; outline:none; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">— Select Grade Level —</option>
                        <option value="Grade 1"   data-level="Elementary"         {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 1'   ? 'selected' : '' }}>Grade 1</option>
                        <option value="Grade 2"   data-level="Elementary"         {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 2'   ? 'selected' : '' }}>Grade 2</option>
                        <option value="Grade 3"   data-level="Elementary"         {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 3'   ? 'selected' : '' }}>Grade 3</option>
                        <option value="Grade 4"   data-level="Elementary"         {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 4'   ? 'selected' : '' }}>Grade 4</option>
                        <option value="Grade 5"   data-level="Elementary"         {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 5'   ? 'selected' : '' }}>Grade 5</option>
                        <option value="Grade 6"   data-level="Elementary"         {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 6'   ? 'selected' : '' }}>Grade 6</option>
                        <option value="Grade 7"   data-level="Junior High School" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 7'   ? 'selected' : '' }}>Grade 7</option>
                        <option value="Grade 8"   data-level="Junior High School" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 8'   ? 'selected' : '' }}>Grade 8</option>
                        <option value="Grade 9"   data-level="Junior High School" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 9'   ? 'selected' : '' }}>Grade 9</option>
                        <option value="Grade 10"  data-level="Junior High School" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 10'  ? 'selected' : '' }}>Grade 10</option>
                        <option value="Grade 11"  data-level="Senior High School" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 11'  ? 'selected' : '' }}>Grade 11</option>
                        <option value="Grade 12"  data-level="Senior High School" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === 'Grade 12'  ? 'selected' : '' }}>Grade 12</option>
                    </select>
                    @error('incoming_grade_level')<p style="font-size:0.75rem; color:#ef4444; margin:4px 0 0;">{{ $message }}</p>@enderror
                </div>

                {{-- SHS Fields --}}
                <div id="shs-fields" class="{{ old('applied_level', $data['applied_level'] ?? '') === 'Senior High School' ? '' : 'hidden' }}">
                    <div style="background:#f0f7ff; border-radius:10px; border:1px solid #bfdbfe; padding:1.25rem; margin-bottom:1.25rem;">
                        <p style="font-size:0.7rem; font-weight:800; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 1rem;">Senior High School Details</p>
                        <div style="margin-bottom:1rem;">
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;">Academic Track <span style="color:#ef4444;">*</span></label>
                            <select name="track" id="track"
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.88rem; font-family:inherit; background:#fff; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">— Select Academic Track —</option>
                                <option value="Academic"      {{ old('track', $data['track'] ?? '') === 'Academic'      ? 'selected' : '' }}>Academic</option>
                                <option value="TVL"           {{ old('track', $data['track'] ?? '') === 'TVL'           ? 'selected' : '' }}>Technical-Vocational-Livelihood (TVL)</option>
                            </select>
                            @error('track')<p style="font-size:0.75rem; color:#ef4444; margin:4px 0 0;">{{ $message }}</p>@enderror
                        </div>
                        <div style="margin-bottom:1rem;">
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;">Strand / Path <span style="color:#ef4444;">*</span></label>
                            <select name="strand" id="strand"
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.88rem; font-family:inherit; background:#fff; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">— Select Strand —</option>
                                <option value="STEM"   data-track="Academic"      {{ old('strand', $data['strand'] ?? '') === 'STEM'   ? 'selected' : '' }}>STEM — Science, Technology, Engineering & Mathematics</option>
                                <option value="ABM"    data-track="Academic"      {{ old('strand', $data['strand'] ?? '') === 'ABM'    ? 'selected' : '' }}>ABM — Accountancy, Business & Management</option>
                                <option value="HUMSS"  data-track="Academic"      {{ old('strand', $data['strand'] ?? '') === 'HUMSS'  ? 'selected' : '' }}>HUMSS — Humanities & Social Sciences</option>
                                <option value="GAS"    data-track="Academic"      {{ old('strand', $data['strand'] ?? '') === 'GAS'    ? 'selected' : '' }}>GAS — General Academic Strand</option>
                                <option value="HE"     data-track="TVL"           {{ old('strand', $data['strand'] ?? '') === 'HE'     ? 'selected' : '' }}>HE — Home Economics (Cookery, Tourism)</option>
                                <option value="ICT"    data-track="TVL"           {{ old('strand', $data['strand'] ?? '') === 'ICT'    ? 'selected' : '' }}>ICT — Information & Communications Technology</option>
                                <option value="IA"     data-track="TVL"           {{ old('strand', $data['strand'] ?? '') === 'IA'     ? 'selected' : '' }}>IA — Industrial Arts</option>
                                <option value="AFA"    data-track="TVL"           {{ old('strand', $data['strand'] ?? '') === 'AFA'    ? 'selected' : '' }}>AFA — Agri-Fishery Arts</option>
                                <option value="AD"     data-track="Arts &amp; Design" {{ old('strand', $data['strand'] ?? '') === 'AD'  ? 'selected' : '' }}>Arts & Design</option>
                                <option value="Sports" data-track="Sports"        {{ old('strand', $data['strand'] ?? '') === 'Sports' ? 'selected' : '' }}>Sports</option>
                            </select>
                            @error('strand')<p style="font-size:0.75rem; color:#ef4444; margin:4px 0 0;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">Student Type</label>
                            <div style="display:flex; gap:1.5rem;">
                                <label style="display:flex; align-items:center; gap:6px; font-size:0.875rem; color:#374151; cursor:pointer;">
                                    <input type="radio" name="shs_student_type" value="Regular" {{ old('shs_student_type', $data['shs_student_type'] ?? 'Regular') === 'Regular' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    Regular
                                </label>
                                <label style="display:flex; align-items:center; gap:6px; font-size:0.875rem; color:#374151; cursor:pointer;">
                                    <input type="radio" name="shs_student_type" value="Irregular" {{ old('shs_student_type', $data['shs_student_type'] ?? '') === 'Irregular' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    Irregular
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                
                {{-- Transferee --}}
                <div style="border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc; padding:1rem;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-bottom:0;">
                        <input type="checkbox" name="is_transferee" value="1" id="is_transferee"
                            {{ old('is_transferee', $data['is_transferee'] ?? false) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500">
                        <span style="font-size:0.875rem; font-weight:600; color:#374151;">I am a transferee from another school</span>
                    </label>
                    <div id="transferee_fields" class="{{ old('is_transferee', $data['is_transferee'] ?? false) ? '' : 'hidden' }}" style="margin-top:1rem; display:flex; flex-direction:column; gap:0.75rem;">
                        <div>
                            <label style="font-size:0.75rem; color:#64748b; font-weight:600; display:block; margin-bottom:4px;">Previous School</label>
                            <input type="text" name="previous_school" placeholder="Name of previous school"
                                value="{{ old('previous_school', $data['previous_school'] ?? '') }}"
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.875rem; font-family:inherit; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                        <div>
                            <label style="font-size:0.75rem; color:#64748b; font-weight:600; display:block; margin-bottom:4px;">Previous School Address</label>
                            <input type="text" name="previous_school_address" placeholder="Address of previous school"
                                value="{{ old('previous_school_address', $data['previous_school_address'] ?? '') }}"
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.875rem; font-family:inherit; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                    </div>
                </div><br>

                {{-- Student Status --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">Student Status <span style="color:#ef4444;">*</span></label>
                    <div style="display:flex; flex-direction:column; gap:0.5rem;">
                        <label id="status-old-label" style="display:flex; align-items:flex-start; gap:8px; font-size:0.875rem; color:#374151; cursor:pointer; padding:10px 12px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff;">
                            <input type="radio" name="student_status" value="Old" id="status_old"
                                {{ old('student_status', $data['student_status'] ?? '') === 'Old' ? 'checked' : '' }}
                                onchange="updateStatusUI()" style="margin-top:3px; flex-shrink:0;">
                            <span>
                                <strong>Old Student</strong>
                                <span style="display:block; font-size:0.75rem; color:#64748b; margin-top:1px;">Previously enrolled at My Messiah School of Cavite (MMSC). SHS Voucher may apply if you graduated from MMSC JHS.</span>
                            </span>
                        </label>
                        <label id="status-new-label" style="display:flex; align-items:flex-start; gap:8px; font-size:0.875rem; color:#374151; cursor:pointer; padding:10px 12px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff;">
                            <input type="radio" name="student_status" value="New" id="status_new"
                                {{ old('student_status', $data['student_status'] ?? 'New') === 'New' ? 'checked' : '' }}
                                onchange="updateStatusUI()" style="margin-top:3px; flex-shrink:0;">
                            <span>
                                <strong>New Student</strong>
                                <span style="display:block; font-size:0.75rem; color:#64748b; margin-top:1px;">First time enrolling at MMSC or coming from a different school.</span>
                            </span>
                        </label>
                    </div>
                    {{-- Old student SHS voucher note --}}
                    <div id="old-student-shs-note" style="display:none; margin-top:8px; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:10px 12px;">
                        <p style="font-size:0.75rem; color:#92400e; margin:0; line-height:1.5;">
                            <strong>SHS Voucher Note:</strong> As an MMSC graduate applying for SHS, you may qualify for the reduced SHS tuition rate of <strong>₱3,500</strong> under the ESC-applied voucher. Select <em>SHS Voucher Recipient</em> below and choose the appropriate option.
                        </p>
                    </div>
                </div>

                {{-- Student Category --}}
                <div style="margin-bottom:0.75rem;">
                    <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;">Student Category / Financial Assistance</label>
                    <select name="student_category" id="student_category"
                        style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.88rem; font-family:inherit; background:#fff; outline:none; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="Regular Payee"         {{ old('student_category', $data['student_category'] ?? 'Regular Payee') === 'Regular Payee'         ? 'selected' : '' }}>Regular Payee</option>
                        <option value="SHS Voucher Recipient" data-level="Senior High School" {{ old('student_category', $data['student_category'] ?? '') === 'SHS Voucher Recipient' ? 'selected' : '' }}>SHS Voucher Recipient (SHS only)</option>
                        <option value="ESC Grantee"           data-level="Junior High School" {{ old('student_category', $data['student_category'] ?? '') === 'ESC Grantee'           ? 'selected' : '' }}>ESC Grantee (JHS only)</option>
                    </select>
                    <p id="category-hint" style="font-size:0.72rem; color:#64748b; margin:4px 0 0; display:none;"></p>
                </div>

                {{-- ESC Grantee eligibility fields --}}
                @php $initCat = old('student_category', $data['student_category'] ?? 'Regular Payee'); @endphp
                <div id="esc-fields" style="margin-bottom:1.25rem; {{ $initCat !== 'ESC Grantee' ? 'display:none;' : '' }}">

                    {{-- Grade 7 only warning --}}
                    <div id="esc-grade-warning" style="display:none; margin-bottom:8px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 12px;">
                        <p style="font-size:0.75rem; color:#dc2626; margin:0; line-height:1.5;">
                            <strong>⚠ ESC is for incoming Grade 7 only.</strong> ESC Grantee subsidy is not available for Grades 8–10. Please select <em>Regular Payee</em> as your category, or go back and correct your grade level.
                        </p>
                    </div>

                    <div style="background:#f0fdf4; border-radius:10px; border:1px solid #bbf7d0; padding:1.25rem;">
                        <p style="font-size:0.7rem; font-weight:800; color:#15803d; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 0.875rem;">ESC Grantee Information</p>
                        <p style="font-size:0.78rem; color:#166534; margin:0 0 1rem; line-height:1.5;">
                            The <strong>Education Service Contracting (ESC)</strong> program provides government subsidy to eligible <strong>incoming Grade 7</strong> students transferring from public elementary schools to private JHSs.
                        </p>

                        {{-- Previous school type — public only --}}
                        <div style="margin-bottom:0.875rem;">
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">
                                Previous Elementary School Type <span style="color:#ef4444;">*</span>
                            </label>
                            <label style="display:flex; align-items:flex-start; gap:8px; font-size:0.875rem; color:#374151; cursor:pointer; padding:10px 12px; border-radius:8px; border:1.5px solid #bbf7d0; background:#f0fdf4;">
                                <input type="radio" name="subsidy_prev_school_type" value="public"
                                    {{ old('subsidy_prev_school_type', $data['subsidy_prev_school_type'] ?? 'public') === 'public' ? 'checked' : '' }}
                                    style="margin-top:2px; flex-shrink:0;">
                                <span>
                                    <strong>Public Elementary School</strong>
                                    <span style="display:block; font-size:0.75rem; color:#15803d; font-weight:600;">Qualifies for ESC subsidy — reduced tuition rate (₱7,500) applies</span>
                                </span>
                            </label>
                            @error('subsidy_prev_school_type')<p style="font-size:0.75rem; color:#ef4444; margin:4px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- ESC Certificate --}}
                        <div style="margin-bottom:0.875rem;">
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;">
                                ESC Certificate Number <span style="font-weight:400; text-transform:none; color:#94a3b8;">(optional, if already issued)</span>
                            </label>
                            <input type="text" name="subsidy_certificate_no" placeholder="e.g. ESC-2026-XXXXXXXX"
                                value="{{ old('subsidy_certificate_no', $data['subsidy_certificate_no'] ?? '') }}"
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.875rem; font-family:inherit; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                        {{-- Required documents info --}}
                        <div style="background:#ecfdf5; border-radius:8px; border:1px solid #6ee7b7; padding:10px 12px;">
                            <p style="font-size:0.7rem; font-weight:800; color:#065f46; text-transform:uppercase; letter-spacing:0.08em; margin:0 0 6px;">Required for ESC Application</p>
                            <ul style="font-size:0.75rem; color:#166534; margin:0; padding-left:1.1rem; line-height:1.8;">
                                <li><strong>Accomplished ESC Application Form</strong> or existing ESC Certificate (if already issued)</li>
                                <li><strong>Medical Clearance</strong> — required; must be approved by the school before final enrollment</li>
                            </ul>
                            <p style="font-size:0.72rem; color:#047857; margin:8px 0 0; line-height:1.5;">
                                For further details and assistance with your ESC application, please visit or contact the <strong>Registrar's Office</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- SHS Voucher Recipient eligibility fields --}}
                <div id="voucher-fields" style="margin-bottom:1.25rem; {{ $initCat !== 'SHS Voucher Recipient' ? 'display:none;' : '' }}">
                    <div style="background:#fffbeb; border-radius:10px; border:1px solid #fde68a; padding:1.25rem;">
                        <p style="font-size:0.7rem; font-weight:800; color:#b45309; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 0.875rem;">SHS Voucher Information</p>
                        <p style="font-size:0.78rem; color:#92400e; margin:0 0 1rem; line-height:1.5;">
                            Your tuition rate depends on what type of Junior High School you completed. MMSC graduates and ESC-subsidized students qualify for a reduced SHS fee of <strong>₱3,500</strong>.
                        </p>
                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">
                                Previous JHS Type <span style="color:#ef4444;">*</span>
                            </label>
                            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                                <label id="voucher-opt-public" style="display:flex; align-items:flex-start; gap:8px; font-size:0.875rem; color:#374151; cursor:pointer; padding:10px 12px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff;">
                                    <input type="radio" name="subsidy_prev_school_type" value="public_jhs"
                                        {{ old('subsidy_prev_school_type', $data['subsidy_prev_school_type'] ?? '') === 'public_jhs' ? 'checked' : '' }}
                                        style="margin-top:2px; flex-shrink:0;">
                                    <span>
                                        <strong>Public JHS Graduate</strong>
                                        <span style="display:block; font-size:0.75rem; color:#15803d; font-weight:600;">FREE tuition — fully covered by SHS voucher</span>
                                    </span>
                                </label>
                                <label id="voucher-opt-esc" style="display:flex; align-items:flex-start; gap:8px; font-size:0.875rem; color:#374151; cursor:pointer; padding:10px 12px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff;">
                                    <input type="radio" name="subsidy_prev_school_type" value="private_jhs_esc"
                                        {{ old('subsidy_prev_school_type', $data['subsidy_prev_school_type'] ?? '') === 'private_jhs_esc' ? 'checked' : '' }}
                                        style="margin-top:2px; flex-shrink:0;">
                                    <span>
                                        <strong>Private JHS with ESC Subsidy</strong>
                                        <span style="display:block; font-size:0.75rem; color:#0369a1; font-weight:600;">₱3,500 — partial voucher coverage applies</span>
                                    </span>
                                </label>
                                <label id="voucher-opt-mmsc" style="display:flex; align-items:flex-start; gap:8px; font-size:0.875rem; color:#374151; cursor:pointer; padding:10px 12px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff;">
                                    <input type="radio" name="subsidy_prev_school_type" value="private_jhs_no_esc"
                                        {{ old('subsidy_prev_school_type', $data['subsidy_prev_school_type'] ?? '') === 'private_jhs_no_esc' ? 'checked' : '' }}
                                        style="margin-top:2px; flex-shrink:0;">
                                    <span>
                                        <strong>MMSC Graduate (My Messiah School of Cavite)</strong>
                                        <span style="display:block; font-size:0.75rem; color:#0369a1; font-weight:600;">₱3,500 — ESC-applied voucher for MMSC JHS graduates</span>
                                        <span style="display:block; font-size:0.72rem; color:#64748b; margin-top:1px;">Applies to students who completed Grade 10 at MMSC</span>
                                    </span>
                                </label>
                            </div>
                            @error('subsidy_prev_school_type')<p style="font-size:0.75rem; color:#ef4444; margin:4px 0 0;">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>


            </div>

            {{-- Next Button --}}
            <div style="display:flex; justify-content:flex-end;">
                <button type="submit"
                    style="background:#0d4c8f; color:#fff; font-family:inherit; font-weight:700; font-size:0.82rem; letter-spacing:0.1em; text-transform:uppercase; padding:12px 32px; border-radius:10px; border:none; cursor:pointer; transition:background 0.2s; box-shadow:0 2px 8px rgba(13,76,143,0.3);"
                    onmouseover="this.style.background='#093462'" onmouseout="this.style.background='#0d4c8f'">
                    Next Step →
                </button>
            </div>

        </form>
    </div>
</section>

<script>
const levelEl         = document.getElementById('applied_level');
const gradeEl         = document.getElementById('incoming_grade_level');
const shsFields       = document.getElementById('shs-fields');
const trackEl         = document.getElementById('track');
const strandEl        = document.getElementById('strand');
const transfereeCheck = document.getElementById('is_transferee');
const transfereeBox   = document.getElementById('transferee_fields');

function filterGradesByLevel(level) {
    gradeEl.querySelectorAll('option').forEach(opt => {
        if (!opt.value) return;
        const match = opt.dataset.level === level;
        opt.hidden = opt.disabled = !match;
    });
    const cur = gradeEl.querySelector(`option[value="${gradeEl.value}"]`);
    if (!cur || cur.dataset.level !== level) gradeEl.value = '';
    shsFields.classList.toggle('hidden', level !== 'Senior High School');
    if (level !== 'Senior High School') { if (trackEl) trackEl.value = ''; if (strandEl) strandEl.value = ''; }
}

function filterStrandsByTrack(track) {
    if (!strandEl) return;
    strandEl.querySelectorAll('option').forEach(opt => {
        if (!opt.value) return;
        const match = opt.dataset.track === track || !opt.dataset.track;
        opt.hidden = opt.disabled = !!(track && !match);
    });
    const cur = strandEl.querySelector(`option[value="${strandEl.value}"]`);
    if (cur && cur.dataset.track && cur.dataset.track !== track) strandEl.value = '';
}

const categoryEl   = document.getElementById('student_category');
const escPanel     = document.getElementById('esc-fields');
const voucherPanel = document.getElementById('voucher-fields');
const categoryHint = document.getElementById('category-hint');

// ── Sync category options with the selected level ────────────
function syncCategoryOptions(level) {
    const optEsc     = categoryEl.querySelector('option[value="ESC Grantee"]');
    const optVoucher = categoryEl.querySelector('option[value="SHS Voucher Recipient"]');

    const escOk     = level === 'Junior High School';
    const voucherOk = level === 'Senior High School';

    optEsc.disabled     = !escOk;
    optVoucher.disabled = !voucherOk;

    // If current selection is now invalid, reset to Regular Payee
    const cur = categoryEl.value;
    if ((cur === 'ESC Grantee' && !escOk) || (cur === 'SHS Voucher Recipient' && !voucherOk)) {
        categoryEl.value = 'Regular Payee';
    }

    // Show hint text
    if (!level) {
        categoryHint.style.display = 'none';
    } else if (level === 'Elementary') {
        categoryHint.textContent   = 'ESC Grantee and SHS Voucher are not available for Elementary.';
        categoryHint.style.display = '';
        categoryHint.style.color   = '#94a3b8';
    } else if (level === 'Junior High School') {
        categoryHint.textContent   = 'ESC Grantee is available for JHS. SHS Voucher is for SHS only.';
        categoryHint.style.display = '';
        categoryHint.style.color   = '#64748b';
    } else {
        categoryHint.textContent   = 'SHS Voucher Recipient is available for SHS. ESC Grantee is for JHS only.';
        categoryHint.style.display = '';
        categoryHint.style.color   = '#64748b';
    }

    updateCategoryPanels(categoryEl.value);
}

function updateCategoryPanels(cat) {
    escPanel.style.display     = cat === 'ESC Grantee'           ? '' : 'none';
    voucherPanel.style.display = cat === 'SHS Voucher Recipient' ? '' : 'none';
    if (cat !== 'ESC Grantee' && cat !== 'SHS Voucher Recipient') {
        document.querySelectorAll('input[name="subsidy_prev_school_type"]').forEach(r => r.checked = false);
        const certInput = document.querySelector('input[name="subsidy_certificate_no"]');
        if (certInput) certInput.value = '';
    }
}

levelEl.addEventListener('change', () => {
    filterGradesByLevel(levelEl.value);
    syncCategoryOptions(levelEl.value);
});
if (trackEl) trackEl.addEventListener('change', () => filterStrandsByTrack(trackEl.value));
transfereeCheck.addEventListener('change', function() {
    transfereeBox.classList.toggle('hidden', !this.checked);

    const statusOld = document.getElementById('status_old');
    const statusNew = document.getElementById('status_new');
    const oldLbl    = document.getElementById('status-old-label');

    if (this.checked) {
        // Force New Student, disable Old Student
        statusNew.checked = true;
        statusOld.checked = false;
        statusOld.disabled = true;
        if (oldLbl) {
            oldLbl.style.opacity = '0.4';
            oldLbl.style.cursor  = 'not-allowed';
            oldLbl.style.borderColor = '#e2e8f0';
            oldLbl.style.background  = '#f8fafc';
        }
    } else {
        statusOld.disabled = false;
        if (oldLbl) {
            oldLbl.style.opacity = '1';
            oldLbl.style.cursor  = 'pointer';
        }
    }
    updateStatusUI();
});
if (categoryEl) categoryEl.addEventListener('change', () => {
    updateCategoryPanels(categoryEl.value);
    checkEscGradeWarning();
});

gradeEl.addEventListener('change', () => { applyGradeRules(); checkEscGradeWarning(); });

function checkEscGradeWarning() {
    const cat      = categoryEl.value;
    const grade    = gradeEl.value;
    const warn     = document.getElementById('esc-grade-warning');
    const escInfo  = document.querySelector('#esc-fields > div:not(#esc-grade-warning)');
    if (!warn) return;
    const invalidGrade = ['Grade 8','Grade 9','Grade 10'].includes(grade);
    const isEsc        = cat === 'ESC Grantee';
    warn.style.display    = (isEsc && invalidGrade) ? '' : 'none';
    if (escInfo) escInfo.style.display = (isEsc && invalidGrade) ? 'none' : '';
}

function updateStatusUI() {
    const isOld   = document.getElementById('status_old')?.checked;
    const isShs   = levelEl.value === 'Senior High School';
    const oldNote = document.getElementById('old-student-shs-note');
    const oldLbl  = document.getElementById('status-old-label');
    const newLbl  = document.getElementById('status-new-label');

    if (oldNote) oldNote.style.display = (isOld && isShs) ? '' : 'none';

    if (oldLbl) oldLbl.style.borderColor = isOld ? '#0d4c8f' : '#e2e8f0';
    if (oldLbl) oldLbl.style.background  = isOld ? '#f0f5ff' : '#fff';
    if (newLbl) newLbl.style.borderColor = !isOld ? '#0d4c8f' : '#e2e8f0';
    if (newLbl) newLbl.style.background  = !isOld ? '#f0f5ff' : '#fff';

    // When Old Student + SHS: only show MMSC Graduate voucher option
    const optPublic = document.getElementById('voucher-opt-public');
    const optEsc    = document.getElementById('voucher-opt-esc');
    const optMmsc   = document.getElementById('voucher-opt-mmsc');
    if (optPublic && optEsc && optMmsc) {
        const oldShs = isOld && isShs;
        optPublic.style.display = oldShs ? 'none' : '';
        optEsc.style.display    = oldShs ? 'none' : '';
        optMmsc.style.display   = '';
        // Auto-select MMSC Graduate and clear others when filtering kicks in
        if (oldShs) {
            optPublic.querySelector('input').checked = false;
            optEsc.querySelector('input').checked    = false;
            optMmsc.querySelector('input').checked   = true;
        }
    }
}

levelEl.addEventListener('change', updateStatusUI);

// ── Grade-based student status / transferee rules ──────────
const FIRST_YEAR_GRADES     = ['Grade 1', 'Grade 7', 'Grade 11'];
const TRANSFEREE_ONLY_GRADES = ['Grade 2','Grade 3','Grade 4','Grade 5','Grade 6',
                                'Grade 8','Grade 9','Grade 10','Grade 12'];

function lockOldStudent(locked) {
    const statusOld = document.getElementById('status_old');
    const oldLbl    = document.getElementById('status-old-label');
    statusOld.disabled = locked;
    if (oldLbl) {
        oldLbl.style.opacity     = locked ? '0.4' : '1';
        oldLbl.style.cursor      = locked ? 'not-allowed' : 'pointer';
        oldLbl.style.borderColor = locked ? '#e2e8f0' : '';
        oldLbl.style.background  = locked ? '#f8fafc' : '';
    }
}

function lockTransferee(locked, checked) {
    const transfereeRow = transfereeCheck.closest('div[style*="border-radius"]');
    transfereeCheck.checked  = checked;
    transfereeCheck.disabled = locked;
    if (locked && !checked) {
        transfereeBox.classList.add('hidden');
        if (transfereeRow) transfereeRow.style.opacity = '0.5';
    } else {
        transfereeBox.classList.toggle('hidden', !checked);
        if (transfereeRow) transfereeRow.style.opacity = '1';
    }
}

function applyGradeRules() {
    const grade   = gradeEl.value;
    const statusNew = document.getElementById('status_new');
    const statusOld = document.getElementById('status_old');

    if (FIRST_YEAR_GRADES.includes(grade)) {
        // First year of level → always New, no Old, no Transferee
        statusNew.checked = true;
        statusOld.checked = false;
        lockOldStudent(true);
        lockTransferee(true, false);

    } else if (TRANSFEREE_ONLY_GRADES.includes(grade)) {
        // Mid-level → always New Transferee, locked
        statusNew.checked = true;
        statusOld.checked = false;
        lockOldStudent(true);
        lockTransferee(true, true);

    } else {
        // No grade selected yet — reset
        lockOldStudent(false);
        lockTransferee(false, transfereeCheck.checked);
    }
    updateStatusUI();
}

function validateStep1(e) {
    const warn = document.getElementById('esc-grade-warning');
    if (warn && warn.style.display !== 'none') {
        e.preventDefault();
        warn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    return true;
}

(function init() {
    if (levelEl.value) {
        filterGradesByLevel(levelEl.value);
        syncCategoryOptions(levelEl.value);
    }
    if (trackEl && trackEl.value) filterStrandsByTrack(trackEl.value);
    applyGradeRules();
    updateStatusUI();
    checkEscGradeWarning();
})();
</script>

@endsection
