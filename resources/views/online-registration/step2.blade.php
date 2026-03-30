@extends('layouts.welcome')

@section('title', 'Online Registration - Step 2')

@section('content')

{{-- ── Hero ── --}}
<section class="relative h-40 flex items-center justify-center text-center"
    style="background-image: linear-gradient(to bottom, rgba(13,76,143,0.85), rgba(13,76,143,0.7)), url('{{ asset('images/landing bg.png') }}'); background-size:cover; background-position:center;">
    <div>
        <h1 class="text-3xl font-extrabold text-white drop-shadow">Online Registration</h1>
        <p class="text-blue-200 text-sm mt-1">Apply online</p>
    </div>
</section>

{{-- ── Progress Bar ── --}}
<div class="bg-[#0d4c8f] py-5">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-blue-300/40 z-0"></div>
            @foreach([
                [1, 'Apply to proposed grade level'],
                [2, 'Personal Information'],
                [3, 'Validate Details'],
                [4, 'Finish'],
            ] as [$num, $label])
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold shadow
                    {{ $num <= 2 ? 'bg-orange-400 text-white' : 'bg-white/20 text-white border-2 border-white/40' }}">
                    {{ $num <= 1 ? '✓' : $num }}
                </div>
                <span class="text-xs text-white/80 text-center max-w-[80px] leading-tight hidden sm:block">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Form ── --}}
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4">

        <h2 class="text-xl font-bold text-blue-700 mb-1">Student Personal Details</h2>
        <div class="border-b border-blue-200 mb-6"></div>

        <form method="POST" action="{{ route('online.registration.save-step2') }}">
            @csrf

            {{-- ── STUDENT DETAILS ── --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-5">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">STUDENT DETAILS</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">First Name: <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" placeholder="First Name"
                            value="{{ old('first_name', $data['first_name'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('first_name')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Last Name: <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" placeholder="Last Name"
                            value="{{ old('last_name', $data['last_name'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('last_name')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" placeholder="Middle Name"
                            value="{{ old('middle_name', $data['middle_name'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Suffix</label>
                        <select name="suffix" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Jr./Sr./III/None</option>
                            @foreach(['Jr.','Sr.','II','III','IV'] as $s)
                            <option value="{{ $s }}" {{ old('suffix', $data['suffix'] ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Gender: <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-4 mt-1">
                            <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                                <input type="radio" name="gender" value="Male" {{ old('gender', $data['gender'] ?? 'Male') === 'Male' ? 'checked' : '' }} class="text-blue-600"> Male
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                                <input type="radio" name="gender" value="Female" {{ old('gender', $data['gender'] ?? '') === 'Female' ? 'checked' : '' }} class="text-blue-600"> Female
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nationality:</label>
                        <select name="nationality" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="Filipino" selected>Filipino</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Date of Birth: <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth"
                            value="{{ old('date_of_birth', $data['date_of_birth'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date_of_birth')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Mother Tongue:</label>
                        <select name="mother_tongue" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Mother Tongue</option>
                            @foreach(['Filipino','Tagalog','Bisaya','Ilocano','Hiligaynon','Bikolano','Waray','Other'] as $mt)
                            <option value="{{ $mt }}" {{ old('mother_tongue', $data['mother_tongue'] ?? '') === $mt ? 'selected' : '' }}>{{ $mt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">LRN (Learner Ref No) <span class="text-red-500">*</span></label>
                        <input type="text" name="lrn" placeholder="LRN"
                            value="{{ old('lrn', $data['lrn'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Religion:</label>
                        <select name="religion" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select Religion</option>
                            @foreach(['Roman Catholic','Born Again Christian','Iglesia ni Cristo','Islam','Seventh-Day Adventist','Other'] as $r)
                            <option value="{{ $r }}" {{ old('religion', $data['religion'] ?? '') === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Email: <span class="text-red-500">*</span></label>
                        <input type="email" name="personal_email" placeholder="Email"
                            value="{{ old('personal_email', $data['personal_email'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('personal_email')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Home Address/House Number <span class="text-red-500">*</span></label>
                        <input type="text" name="home_address" placeholder="House Number"
                            value="{{ old('home_address', $data['home_address'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Confirm Email <span class="text-red-500">*</span></label>
                        <input type="email" name="confirm_email" placeholder="Confirm Email"
                            value="{{ old('confirm_email') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('confirm_email')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">City/Municipality <span class="text-red-500">*</span></label>
                        <input type="text" name="city" placeholder="City/Municipality"
                            value="{{ old('city', $data['city'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="mobile_number" placeholder="Mobile Number"
                            value="{{ old('mobile_number', $data['mobile_number'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('mobile_number')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">ZIP Code</label>
                        <input type="text" name="zip_code" placeholder="ZIP Code"
                            value="{{ old('zip_code', $data['zip_code'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>
            </div>

            {{-- ── PARENT/GUARDIAN ── --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-5">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">PARENT/GUARDIAN INFORMATION</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Father Name: <span class="text-red-500">*</span></label>
                        <input type="text" name="father_name" placeholder="Father Name"
                            value="{{ old('father_name', $data['father_name'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Father Contact Number</label>
                        <input type="text" name="father_contact" placeholder="Contact Number"
                            value="{{ old('father_contact', $data['father_contact'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Mother Maiden Name <span class="text-red-500">*</span></label>
                        <input type="text" name="mother_maiden_name" placeholder="Mothers Maiden Name"
                            value="{{ old('mother_maiden_name', $data['mother_maiden_name'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Mother Contact Number</label>
                        <input type="text" name="mother_contact" placeholder="Contact Number"
                            value="{{ old('mother_contact', $data['mother_contact'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Guardian box --}}
                    <div class="sm:col-span-2">
                        <div class="rounded-xl border border-slate-200 p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Guardian Name <span class="text-red-500">*</span></label>
                                <input type="text" name="guardian_name" placeholder="Guardian Name"
                                    value="{{ old('guardian_name', $data['guardian_name'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('guardian_name')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Home Address <span class="text-red-500">*</span></label>
                                <input type="text" name="guardian_address" placeholder="Current Address"
                                    value="{{ old('guardian_address', $data['guardian_address'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Relationship</label>
                                <input type="text" name="guardian_relationship" placeholder="Relationship"
                                    value="{{ old('guardian_relationship', $data['guardian_relationship'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="guardian_email" placeholder="Email"
                                    value="{{ old('guardian_email', $data['guardian_email'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Occupation</label>
                                <input type="text" name="guardian_occupation" placeholder="Occupation"
                                    value="{{ old('guardian_occupation', $data['guardian_occupation'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Emergency Contact Number <span class="text-red-500">*</span></label>
                                <input type="text" name="emergency_contact_number" placeholder="Emergency Contact Number"
                                    value="{{ old('emergency_contact_number', $data['emergency_contact_number'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('emergency_contact_number')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Guardian Contact <span class="text-red-500">*</span></label>
                                <input type="text" name="guardian_contact" placeholder="Guardian Contact"
                                    value="{{ old('guardian_contact', $data['guardian_contact'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('online.registration.step1') }}"
                   class="px-6 py-3 rounded-xl border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    BACK
                </a>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 text-white text-sm font-bold transition-colors shadow">
                    NEXT
                </button>
            </div>

        </form>
    </div>
</section>

@endsection

@push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
@endpush
