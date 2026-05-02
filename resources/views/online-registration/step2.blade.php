@extends('layouts.welcome')
@section('title', 'Online Registration — Step 2')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family: 'Montserrat', sans-serif; }
    .field-input {
        width:100%; border:1.5px solid #e2e8f0; border-radius:8px;
        padding:10px 14px; font-size:0.85rem; font-family:inherit;
        background:#fff; outline:none; box-sizing:border-box; transition:border-color 0.2s;
    }
    .field-input:focus { border-color:#0d4c8f; }
</style>

{{-- ── Registration Header ── --}}
<div style="padding-top:64px; background:linear-gradient(135deg, #0c2340 0%, #0d4c8f 60%, #0891b2 100%);">

    <div class="max-w-3xl mx-auto px-4 pt-8 pb-6 text-center">
        <p style="font-size:0.65rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#7dd3fc; margin-bottom:4px;">My Messiah School of Cavite</p>
        <h1 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 2px;">Online Admission Application</h1>
        <p style="font-size:0.8rem; color:rgba(255,255,255,0.65);">Academic Year 2026–2027</p>
    </div>

    <div class="max-w-2xl mx-auto px-4 pb-8">
        @include('online-registration._stepper', ['currentStep' => 2])
    </div>
</div>

{{-- ── Form ── --}}
<section style="background:#f1f5f9; padding:2.5rem 0; min-height:70vh;">
    <div class="max-w-2xl mx-auto px-4">

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:1.5rem;">
            <div style="width:36px; height:36px; background:#0d4c8f; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
                <h2 style="font-size:1rem; font-weight:800; color:#0c2340; margin:0;">Personal Information</h2>
                <p style="font-size:0.75rem; color:#64748b; margin:0;">Student details and parent/guardian information</p>
            </div>
        </div>

        @if ($errors->any())
        <div style="margin-bottom:1rem; border-radius:10px; border:1px solid #fecaca; background:#fef2f2; padding:12px 16px;">
            <ul style="list-style:disc; padding-left:1.25rem; margin:0; font-size:0.82rem; color:#dc2626; line-height:1.8;">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('online.registration.save-step2') }}">
            @csrf

            {{-- ── STUDENT DETAILS ── --}}
            <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:1.75rem; margin-bottom:1.25rem;">

                <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.65rem; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#0d4c8f; background:#eff6ff; padding:3px 10px; border-radius:20px;">Student Details</span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">First Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="first_name" placeholder="First Name" class="field-input"
                            value="{{ old('first_name', $data['first_name'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('first_name')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Last Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="last_name" placeholder="Last Name" class="field-input"
                            value="{{ old('last_name', $data['last_name'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('last_name')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Middle Name</label>
                        <input type="text" name="middle_name" placeholder="Middle Name (optional)" class="field-input"
                            value="{{ old('middle_name', $data['middle_name'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Suffix</label>
                        <select name="suffix" class="field-input"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">— None —</option>
                            @foreach(['Jr.','Sr.','II','III','IV'] as $s)
                            <option value="{{ $s }}" {{ old('suffix', $data['suffix'] ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Gender <span style="color:#ef4444;">*</span></label>
                        <div style="display:flex; gap:1.5rem; margin-top:8px;">
                            <label style="display:flex; align-items:center; gap:6px; font-size:0.85rem; color:#374151; cursor:pointer;">
                                <input type="radio" name="gender" value="Male" {{ old('gender', $data['gender'] ?? 'Male') === 'Male' ? 'checked' : '' }} style="accent-color:#0d4c8f;">
                                Male
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; font-size:0.85rem; color:#374151; cursor:pointer;">
                                <input type="radio" name="gender" value="Female" {{ old('gender', $data['gender'] ?? '') === 'Female' ? 'checked' : '' }} style="accent-color:#0d4c8f;">
                                Female
                            </label>
                        </div>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Nationality</label>
                        <select name="nationality" class="field-input"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="Filipino" {{ old('nationality', $data['nationality'] ?? 'Filipino') === 'Filipino' ? 'selected' : '' }}>Filipino</option>
                            <option value="Other" {{ old('nationality', $data['nationality'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Date of Birth <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="date_of_birth" class="field-input"
                            value="{{ old('date_of_birth', $data['date_of_birth'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('date_of_birth')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Mother Tongue</label>
                        <select name="mother_tongue" class="field-input"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">— Select —</option>
                            @foreach(['Filipino','Bisaya','Ilocano','Hiligaynon','Bikolano','Waray','Other'] as $mt)
                            <option value="{{ $mt }}" {{ old('mother_tongue', $data['mother_tongue'] ?? '') === $mt ? 'selected' : '' }}>{{ $mt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">LRN (Learner Reference No.) <span style="color:#ef4444;">*</span></label>
                       <input type="text" name="lrn" placeholder="12-digit LRN" 
                            class="field-input"
                            value="{{ old('lrn', $data['lrn'] ?? '') }}"
                            pattern="\d{12}" 
                            maxlength="12"
                            onfocus="this.style.borderColor='#0d4c8f'" 
                            onblur="this.style.borderColor='#e2e8f0'" 
                            required>
                        @error('lrn')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror

                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Religion</label>
                        <select name="religion" class="field-input"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">— Select —</option>
                            @foreach(['Roman Catholic','Born Again Christian','Iglesia ni Cristo','Islam','Seventh-Day Adventist','Other'] as $r)
                            <option value="{{ $r }}" {{ old('religion', $data['religion'] ?? '') === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Email Address <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="personal_email" placeholder="you@email.com" class="field-input"
                            value="{{ old('personal_email', $data['personal_email'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('personal_email')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Confirm Email <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="confirm_email" placeholder="Retype email" class="field-input"
                            value="{{ old('confirm_email') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('confirm_email')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Mobile Number <span style="color:#ef4444;">*</span></label>
                        <input type="tel" name="mobile_number" placeholder="09XX XXX XXXX" class="field-input"
                            value="{{ old('mobile_number', $data['mobile_number'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('mobile_number')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">House / Street No. <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="home_address" placeholder="House number & street" class="field-input"
                            value="{{ old('home_address', $data['home_address'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">City / Municipality <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="city" placeholder="City / Municipality" class="field-input"
                            value="{{ old('city', $data['city'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">ZIP Code</label>
                        <input type="text" name="zip_code" placeholder="ZIP Code" class="field-input"
                            value="{{ old('zip_code', $data['zip_code'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                </div>
            </div>

            {{-- ── PARENT / GUARDIAN ── --}}
            <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:1.75rem; margin-bottom:1.25rem;">

                <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.65rem; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#0d4c8f; background:#eff6ff; padding:3px 10px; border-radius:20px;">Parent / Guardian Information</span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Father's Full Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="father_name" placeholder="Father's complete name" class="field-input"
                            value="{{ old('father_name', $data['father_name'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Father's Contact No.</label>
                        <input type="text" name="father_contact" placeholder="09XX XXX XXXX" class="field-input"
                            value="{{ old('father_contact', $data['father_contact'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Mother's Maiden Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="mother_maiden_name" placeholder="Mother's maiden name" class="field-input"
                            value="{{ old('mother_maiden_name', $data['mother_maiden_name'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Mother's Contact No.</label>
                        <input type="text" name="mother_contact" placeholder="Contact number" class="field-input"
                            value="{{ old('mother_contact', $data['mother_contact'] ?? '') }}"
                            onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                </div>

                {{-- Guardian sub-section --}}
                <div style="margin-top:1.25rem; padding:1.25rem; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc;">
                    <p style="font-size:0.65rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 1rem;">Guardian / Emergency Contact</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Guardian Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="guardian_name" placeholder="Full name" class="field-input"
                                value="{{ old('guardian_name', $data['guardian_name'] ?? '') }}"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            @error('guardian_name')<p style="font-size:0.72rem; color:#ef4444; margin:3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Guardian Contact <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="guardian_contact" placeholder="Contact number" class="field-input"
                                value="{{ old('guardian_contact', $data['guardian_contact'] ?? '') }}"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Relationship <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="guardian_relationship" placeholder="e.g. Aunt, Uncle" class="field-input"
                                value="{{ old('guardian_relationship', $data['guardian_relationship'] ?? '') }}"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Occupation</label>
                            <input type="text" name="guardian_occupation" placeholder="Occupation" class="field-input"
                                value="{{ old('guardian_occupation', $data['guardian_occupation'] ?? '') }}"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Guardian Email <span style="color:#ef4444;">*</span></label>
                            <input type="email" name="guardian_email" placeholder="Guardian email" class="field-input"
                                value="{{ old('guardian_email', $data['guardian_email'] ?? '') }}"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                        <div>
                            <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:5px;">Home Address <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="guardian_address" placeholder="Current address" class="field-input"
                                value="{{ old('guardian_address', $data['guardian_address'] ?? '') }}"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.5rem; padding-bottom:2rem;">
                <a href="{{ route('online.registration.step1') }}"
                   style="display:inline-flex; align-items:center; gap:6px; padding:11px 24px; border-radius:8px; border:1.5px solid #cbd5e1; background:#fff; font-size:0.82rem; font-weight:700; color:#64748b; text-decoration:none; letter-spacing:0.05em; transition:background 0.2s;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back
                </a>
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:6px; padding:11px 28px; border-radius:8px; background:#0d4c8f; color:#fff; font-size:0.82rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border:none; cursor:pointer; transition:background 0.2s;"
                    onmouseover="this.style.background='#093462'" onmouseout="this.style.background='#0d4c8f'">
                    Next Step
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

        </form>
    </div>
</section>

@endsection
