@extends('layouts.welcome')
@section('title', 'Review Your Application')
@section('content')

<section class="relative h-40 flex items-center justify-center text-center"
    style="background-image: linear-gradient(to bottom, rgba(13,76,143,0.85), rgba(13,76,143,0.7)), url('{{ asset('images/landing bg.png') }}'); background-size:cover; background-position:center;">
    <div>
        <h1 class="text-3xl font-extrabold text-white drop-shadow">Online Registration</h1>
        <p class="text-blue-200 text-sm mt-1">Apply online</p>
    </div>
</section>

<div class="bg-[#0d4c8f] py-5">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-blue-300/40 z-0"></div>
            @foreach([[1,'Apply to proposed grade level'],[2,'Personal Information'],[3,'Validate Details'],[4,'Finish']] as [$n,$l])
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold shadow
                    {{ $n <= 3 ? 'bg-orange-400 text-white' : 'bg-white/20 text-white border-2 border-white/40' }}">
                    {{ $n <= 2 ? '✓' : $n }}
                </div>
                <span class="text-xs text-white/80 text-center max-w-[80px] leading-tight hidden sm:block">{{ $l }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4">

        <h2 class="text-xl font-bold text-blue-700 mb-1">Review Your Application</h2>
        <div class="border-b border-blue-200 mb-6"></div>

        @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- GRADE LEVEL & PROGRAM --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">GRADE LEVEL & PROGRAM</p>
            <div class="grid grid-cols-2 gap-y-2 text-sm">
                <span class="text-slate-500">Level:</span><span class="text-slate-700 font-medium">{{ $step1['applied_level'] }}</span>
                <span class="text-slate-500">Grade Level:</span><span class="text-slate-700 font-medium">{{ $step1['incoming_grade_level'] }}</span>
                <span class="text-slate-500">Transferee:</span><span class="text-slate-700 font-medium">{{ ($step1['is_transferee'] ?? false) ? 'Yes - ' . ($step1['previous_school'] ?? '') : 'No' }}</span>
            </div>
            <a href="{{ route('online.registration.step1') }}" class="inline-flex items-center gap-1 mt-3 text-xs text-blue-600 hover:underline">
                <iconify-icon icon="solar:pen-linear" width="13"></iconify-icon> Edit
            </a>
        </div>

        {{-- PERSONAL INFORMATION --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">PERSONAL INFORMATION</p>
            <div class="grid grid-cols-2 gap-y-2 text-sm">
                <span class="text-slate-500">Full Name:</span>
                <span class="text-slate-700 font-medium">{{ $step2['first_name'] }} {{ $step2['middle_name'] ?? '' }} {{ $step2['last_name'] }} {{ $step2['suffix'] ?? '' }}</span>
                <span class="text-slate-500">Birthdate:</span><span class="text-slate-700 font-medium">{{ $step2['date_of_birth'] }}</span>
                <span class="text-slate-500">Gender:</span><span class="text-slate-700 font-medium">{{ $step2['gender'] }}</span>
                <span class="text-slate-500">Nationality:</span><span class="text-slate-700 font-medium">{{ $step2['nationality'] ?? 'Filipino' }}</span>
                <span class="text-slate-500">LRN:</span><span class="text-slate-700 font-medium">{{ $step2['lrn'] ?? 'N/A' }}</span>
                <span class="text-slate-500">Contact:</span><span class="text-slate-700 font-medium">{{ $step2['mobile_number'] }}</span>
                <span class="text-slate-500">Email:</span><span class="text-slate-700 font-medium">{{ $step2['personal_email'] }}</span>
                <span class="text-slate-500">Address:</span><span class="text-slate-700 font-medium">{{ $step2['home_address'] }}, {{ $step2['city'] ?? '' }}, Cavite</span>
            </div>
            <a href="{{ route('online.registration.step2') }}" class="inline-flex items-center gap-1 mt-3 text-xs text-blue-600 hover:underline">
                <iconify-icon icon="solar:pen-linear" width="13"></iconify-icon> Edit
            </a>
        </div>

        {{-- PARENT/GUARDIAN --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">PARENT/GUARDIAN INFORMATION</p>
            <div class="grid grid-cols-2 gap-y-2 text-sm">
                <span class="text-slate-500">Father:</span><span class="text-slate-700 font-medium">{{ $step2['father_name'] ?? 'N/A' }} | Contact Number: {{ $step2['father_contact'] ?? 'N/A' }}</span>
                <span class="text-slate-500">Mother:</span><span class="text-slate-700 font-medium">{{ $step2['mother_maiden_name'] ?? 'N/A' }} | Contact Number: {{ $step2['mother_contact'] ?? 'N/A' }}</span>
                <span class="text-slate-500">Guardian Name:</span><span class="text-slate-700 font-medium">{{ $step2['guardian_name'] }}</span>
                <span class="text-slate-500">Contact Number:</span><span class="text-slate-700 font-medium">{{ $step2['guardian_contact'] }}</span>
                <span class="text-slate-500">Email:</span><span class="text-slate-700 font-medium">{{ $step2['guardian_email'] ?? 'N/A' }}</span>
            </div>
            <a href="{{ route('online.registration.step2') }}" class="inline-flex items-center gap-1 mt-3 text-xs text-blue-600 hover:underline">
                <iconify-icon icon="solar:pen-linear" width="13"></iconify-icon> Edit
            </a>
        </div>

        {{-- DOCUMENTS --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">DOCUMENTS UPLOADED</p>
            <div class="grid grid-cols-2 gap-y-2 text-sm">
                <span class="text-slate-500">PSA:</span>
                <span class="text-slate-700 font-medium">
                    {{ ($step3['psa_uploaded'] ?? false) ? $step3['psa_filename'] . ' (Uploaded)' : 'Not uploaded' }}
                </span>
                <span class="text-slate-500">Report Card:</span>
                <span class="text-slate-700 font-medium">
                    {{ ($step3['report_card_uploaded'] ?? false) ? $step3['report_card_filename'] . ' (Uploaded)' : 'Not uploaded' }}
                </span>
                <span class="text-slate-500">Good Moral:</span>
                <span class="text-slate-700 font-medium">
                    {{ ($step3['good_moral_uploaded'] ?? false) ? $step3['good_moral_filename'] . ' (Uploaded)' : 'Not uploaded' }}
                </span>
            </div>
            <a href="{{ route('online.registration.step3') }}" class="inline-flex items-center gap-1 mt-3 text-xs text-blue-600 hover:underline">
                <iconify-icon icon="solar:pen-linear" width="13"></iconify-icon> Edit
            </a>
        </div>

        {{-- CONSENT & SUBMIT --}}
        <form method="POST" action="{{ route('online.registration.submit') }}">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
                <p class="text-xs text-slate-600 mb-4">
                    In compliance with the Data Privacy Act of 2012 (Republic Act No. 10173), I hereby give my consent to
                    MY MESSIAH SCHOOL OF CAVITE to collect, process, store, and use the personal information I have
                    provided in this application.
                </p>
                <p class="text-xs text-slate-600 mb-4">
                    I certify that all information provided is true and correct. I understand that any false information may result in
                    disqualification.
                </p>
                <label class="flex items-start gap-2 mb-3 cursor-pointer">
                    <input type="checkbox" name="consent_read" value="1"
                        class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                    <span class="text-xs text-slate-600">I have read and understood the terms above.</span>
                </label>
                @error('consent_read')<p class="text-xs text-red-500 -mt-2 mb-2">{{ $message }}</p>@enderror
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="consent_privacy" value="1"
                        class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                    <span class="text-xs text-slate-600">I consent to the collection and processing of my personal information.</span>
                </label>
                @error('consent_privacy')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('online.registration.step3') }}"
                   class="px-6 py-3 rounded-xl border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    BACK
                </a>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 text-white text-sm font-bold transition-colors shadow">
                    SUBMIT
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
