@extends('layouts.welcome')
@section('title', 'Application Submitted')
@section('content')

<section class="relative h-40 flex items-center justify-center text-center"
    style="background-image: linear-gradient(to bottom, rgba(13,76,143,0.85), rgba(13,76,143,0.7)), url('{{ asset('images/landing bg.png') }}'); background-size:cover; background-position:center;">
    <div>
        <h1 class="text-3xl font-extrabold text-white drop-shadow">Online Registration</h1>
        <p class="text-blue-200 text-sm mt-1">Apply online</p>
    </div>
</section>

{{-- All steps complete progress bar --}}
<div class="bg-[#0d4c8f] py-5">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-orange-400 z-0"></div>
            @foreach([[1,'Apply to proposed grade level'],[2,'Personal Information'],[3,'Validate Details'],[4,'Finish']] as [$n,$l])
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold shadow bg-orange-400 text-white">✓</div>
                <span class="text-xs text-white/80 text-center max-w-[80px] leading-tight hidden sm:block">{{ $l }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<section class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-xl mx-auto px-4">

        <div class="text-center mb-8">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mx-auto mb-4">
                <iconify-icon icon="solar:check-circle-bold" width="40" class="text-green-600"></iconify-icon>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 uppercase tracking-wide">APPLICATION SUBMITTED!</h2>
            <div class="w-24 h-0.5 bg-slate-300 mx-auto mt-2"></div>
        </div>

        <p class="text-sm text-slate-600 mb-1">Dear <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>,</p>
        <p class="text-sm text-slate-600 mb-6">Your application has been received.</p>

        {{-- Reference Info --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 mb-6 text-sm space-y-2">
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">REFERENCE NUMBER:</span>
                <span class="font-bold text-slate-800">{{ $application->reference_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">DATE SUBMITTED:</span>
                <span class="text-slate-700">{{ $application->submitted_at->format('F d, Y · g:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">STATUS:</span>
                <span class="font-semibold text-amber-600 uppercase">PENDING</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">APPLIED GRADE:</span>
                <span class="text-slate-700">{{ $application->incoming_grade_level }}</span>
            </div>
        </div>

        {{-- What's Next --}}
        <div class="mb-6">
            <p class="text-sm font-bold text-slate-700 mb-2">WHAT'S NEXT?</p>
            <p class="text-xs text-slate-500 mb-3">Complete your enrollment in TWO ways:</p>

            <div class="rounded-xl border border-slate-200 bg-white p-4 mb-3">
                <p class="text-xs font-bold text-slate-700 mb-2">VISIT US <span class="font-normal text-slate-500">(Recommended for faster processing)</span></p>
                <p class="text-xs text-slate-600"><strong>LOCATION:</strong> Registrar's Office, Ground Floor</p>
                <p class="text-xs text-slate-600"><strong>HOURS:</strong> Monday - Friday, 7:00 AM - 5:00 PM</p>
                <p class="text-xs text-slate-600"><strong>CONTACT:</strong> (046) 123-4567 / registrar@mmsc.edu.ph</p>
                <p class="text-xs text-slate-500 mt-2 font-medium">Bring the following original documents:</p>
                <ul class="text-xs text-slate-600 ml-3 mt-1 space-y-0.5 list-disc list-inside">
                    <li>Printed copy of this application form</li>
                    <li>PSA Birth Certificate</li>
                    <li>Report Card (Form 138) - for transferees</li>
                    <li>Good Moral Certificate</li>
                    <li>2 pcs 2x2 ID picture</li>
                </ul>
                <p class="text-xs text-blue-600 mt-2 font-medium">No appointment needed. Just walk in!</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-700 mb-2">WAIT FOR ONLINE ASSESSMENT</p>
                <ol class="text-xs text-slate-600 ml-3 space-y-0.5 list-decimal list-inside">
                    <li>Check email for confirmation</li>
                    <li>Wait 2-3 business days for assessment</li>
                    <li>Submit documents when called</li>
                </ol>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <a href="{{ route('welcome') }}"
               class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors text-center">
                BACK TO HOME
            </a>
            <a href="{{ route('online.registration.download-pdf', $application->reference_number) }}"
               class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 text-white text-sm font-bold transition-colors shadow text-center">
                DOWNLOAD APPLICATION FORM
            </a>
        </div>

    </div>
</section>

@endsection

@push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
@endpush
