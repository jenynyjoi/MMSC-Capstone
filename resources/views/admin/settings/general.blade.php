@extends('layouts.admin_layout')
@section('title', 'General Settings')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Settings</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Manage Account &amp; System</p>
        </div>
    </div>

    {{-- Settings Nav Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-slate-200 dark:border-dark-border">
        <a href="{{ route('admin.settings.account') }}"
           class="px-4 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors border-b-2 border-transparent">
            Account
        </a>
        <a href="{{ route('admin.settings.general') }}"
           class="px-4 py-2 text-sm font-medium text-[#0d4c8f] dark:text-blue-400 border-b-2 border-[#0d4c8f] dark:border-blue-400">
            General
        </a>
    </div>

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="px-7 py-4 border-b border-slate-100 dark:border-dark-border flex items-center gap-2">
            <iconify-icon icon="solar:buildings-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">School Information</h2>
        </div>

        <form method="POST" action="{{ route('admin.settings.general.save') }}" enctype="multipart/form-data"
              class="px-7 py-6 space-y-6">
            @csrf

            @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li class="text-xs text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- School Logo --}}
            <div class="flex items-start gap-6">
                <div class="shrink-0">
                    <div class="h-24 w-24 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-dark-border" id="logo-preview-wrap">
                        @if(!empty($settings['school_logo']))
                            <img id="logo-preview" src="{{ asset('storage/' . $settings['school_logo']) }}"
                                 alt="School Logo" class="w-full h-full object-contain">
                        @else
                            <iconify-icon id="logo-placeholder" icon="solar:buildings-bold" width="36" class="text-slate-300"></iconify-icon>
                            <img id="logo-preview" src="" alt="Logo preview" class="w-full h-full object-contain hidden">
                        @endif
                    </div>
                    <button type="button" onclick="document.getElementById('logo-input').click()"
                        class="mt-2 w-full text-center text-xs text-[#0d4c8f] dark:text-blue-400 hover:underline font-medium">
                        Change Logo
                    </button>
                    <input type="file" id="logo-input" name="school_logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                </div>

                <div class="flex-1 space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">School Name <span class="text-red-500">*</span></label>
                        <input type="text" name="school_name" value="{{ old('school_name', $settings['school_name'] ?? '') }}" required
                            placeholder="e.g. My Messiah School of Cavite"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Motto / Tagline</label>
                        <input type="text" name="school_motto" value="{{ old('school_motto', $settings['school_motto'] ?? '') }}"
                            placeholder="e.g. Excellence in Education"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 dark:border-dark-border">

            {{-- Contact Details --}}
            <div>
                <h3 class="text-sm font-bold text-slate-700 dark:text-white mb-4 flex items-center gap-2">
                    <iconify-icon icon="solar:phone-bold" width="16" class="text-slate-400"></iconify-icon>
                    Contact Details
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Phone Number</label>
                        <input type="text" name="school_phone" value="{{ old('school_phone', $settings['school_phone'] ?? '') }}"
                            placeholder="e.g. (046) 123-4567"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Email Address</label>
                        <input type="email" name="school_email" value="{{ old('school_email', $settings['school_email'] ?? '') }}"
                            placeholder="e.g. info@school.edu.ph"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1.5 sm:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Address</label>
                        <textarea name="school_address" rows="2"
                            placeholder="e.g. Brgy. Sample, Cavite City, Cavite"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('school_address', $settings['school_address'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                    SAVE SETTINGS
                </button>
            </div>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

<script>
function previewLogo(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) {
        alert('Image must be 2 MB or smaller.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        const preview = document.getElementById('logo-preview');
        const placeholder = document.getElementById('logo-placeholder');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
