@extends('layouts.welcome')
@section('title', 'Online Registration - Step 3')
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
                    {{ $n <= 2 ? 'bg-orange-400 text-white' : ($n === 3 ? 'bg-orange-400 text-white' : 'bg-white/20 text-white border-2 border-white/40') }}">
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
        <h2 class="text-xl font-bold text-blue-700 mb-1">Student Personal Details</h2>
        <div class="border-b border-blue-200 mb-6"></div>

        <form method="POST" action="{{ route('online.registration.save-step3') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">DOCUMENT UPLOAD (Optional)</p>
                    <p class="text-xs text-slate-400 mt-1">For faster processing, you may upload scanned copies.</p>
                    <p class="text-xs text-slate-400">Hard copies will still be required upon enrollment. These are for initial screening only.</p>
                </div>

                <div class="rounded-xl border border-slate-200 p-5 space-y-5">

                    @foreach([
                        ['psa',         'PSA Birth Certificate'],
                        ['report_card', 'Report Card (Form 138)'],
                        ['good_moral',  'Good Moral Certificate'],
                    ] as [$name, $label])
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-2">{{ $label }}</p>
                        <div class="flex items-center gap-3">
                            <label class="cursor-pointer">
                                <span class="px-4 py-2 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Upload File</span>
                                <input type="file" name="{{ $name }}" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                    onchange="updateFilename(this, '{{ $name }}_label')">
                            </label>
                            <span id="{{ $name }}_label" class="text-xs text-slate-400">No file chosen</span>
                        </div>
                    </div>
                    @endforeach

                    <div class="pt-2">
                        <p class="text-xs text-green-600 flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-green-500 inline-block"></span>
                            Accepted formats: PDF, JPG, PNG (Max 5MB each)
                        </p>
                    </div>

                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('online.registration.step2') }}"
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

<script>
function updateFilename(input, labelId) {
    const label = document.getElementById(labelId);
    label.textContent = input.files.length > 0 ? input.files[0].name : 'No file chosen';
}
</script>

@endsection

@push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
@endpush
