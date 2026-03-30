@extends('layouts.welcome')

@section('title', 'Online Registration - Step 1')

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
            {{-- connector line --}}
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-blue-300/40 z-0"></div>
            @foreach([
                [1, 'Apply to proposed grade level'],
                [2, 'Personal Information'],
                [3, 'Validate Details'],
                [4, 'Finish'],
            ] as [$num, $label])
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold shadow
                    {{ $num === 1 ? 'bg-orange-400 text-white' : 'bg-white/20 text-white border-2 border-white/40' }}">
                    {{ $num }}
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

        <h2 class="text-xl font-bold text-blue-700 mb-1">Select Grade Level</h2>
        <div class="border-b border-blue-200 mb-6"></div>

        <form method="POST" action="{{ route('online.registration.save-step1') }}">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">

                {{-- Applied Level --}}
                <div>
                    <p class="text-sm font-semibold text-slate-700 mb-2">Which grade level are you applying for?</p>
                    @foreach(['Elementary (Grades 1-6)', 'Junior High School (Grades 7-10)', 'Senior High School (Grades 11-12)'] as $opt)
                    @php $val = explode(' (', $opt)[0]; @endphp
                    <label class="flex items-center gap-2 mb-1.5 cursor-pointer">
                        <input type="radio" name="applied_level" value="{{ $val }}"
                            {{ old('applied_level', $data['applied_level'] ?? '') === $val ? 'checked' : '' }}
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-slate-600">{{ $opt }}</span>
                    </label>
                    @endforeach
                    @error('applied_level')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Grade Level dropdown --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                        INCOMING GRADE LEVEL <span class="text-slate-400">(for Academic Year 2026-2027)</span>
                    </label>
                    <select name="incoming_grade_level"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Select Grade Level</option>
                        @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $g)
                        <option value="{{ $g }}" {{ old('incoming_grade_level', $data['incoming_grade_level'] ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                    @error('incoming_grade_level')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Student Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Student Status:</label>
                    <label class="flex items-center gap-2 mb-1 cursor-pointer">
                        <input type="radio" name="student_status" value="Old"
                            {{ old('student_status', $data['student_status'] ?? '') === 'Old' ? 'checked' : '' }}
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-slate-600">Old</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="student_status" value="New"
                            {{ old('student_status', $data['student_status'] ?? 'New') === 'New' ? 'checked' : '' }}
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-slate-600">New</span>
                    </label>
                </div>

                {{-- Student Category --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Student Category:</label>
                    <select name="student_category"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="Regular Payee" {{ old('student_category', $data['student_category'] ?? 'Regular Payee') === 'Regular Payee' ? 'selected' : '' }}>Regular Payee</option>
                        <option value="SHS Voucher Recipient" {{ old('student_category', $data['student_category'] ?? '') === 'SHS Voucher Recipient' ? 'selected' : '' }}>SHS Voucher Recipient</option>
                        <option value="ESC Grantee" {{ old('student_category', $data['student_category'] ?? '') === 'ESC Grantee' ? 'selected' : '' }}>ESC Grantee</option>
                    </select>
                </div>

                {{-- Transferee --}}
                <div class="rounded-xl border border-slate-200 p-4 bg-slate-50">
                    <label class="flex items-center gap-2 cursor-pointer mb-3">
                        <input type="checkbox" name="is_transferee" value="1" id="is_transferee"
                            {{ old('is_transferee', $data['is_transferee'] ?? false) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">For Transferees: I am a transferee from another school</span>
                    </label>
                    <div id="transferee_fields" class="{{ old('is_transferee', $data['is_transferee'] ?? false) ? '' : 'hidden' }} space-y-3">
                        <div>
                            <label class="text-xs text-slate-500 font-medium mb-1 block">Previous School:</label>
                            <input type="text" name="previous_school" placeholder="Previous School"
                                value="{{ old('previous_school', $data['previous_school'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-medium mb-1 block">Previous School Address:</label>
                            <input type="text" name="previous_school_address" placeholder="Previous School Address"
                                value="{{ old('previous_school_address', $data['previous_school_address'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Next Button --}}
            <div class="mt-6 flex justify-start">
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 text-white text-sm font-bold transition-colors shadow">
                    NEXT
                </button>
            </div>

        </form>
    </div>
</section>

<script>
    document.getElementById('is_transferee').addEventListener('change', function () {
        document.getElementById('transferee_fields').classList.toggle('hidden', !this.checked);
    });
</script>

@endsection

  @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
    @endpush
