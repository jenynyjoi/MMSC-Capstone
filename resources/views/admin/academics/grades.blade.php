@extends('layouts.admin_layout')
@section('title', 'Grades')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    <x-admin.page-header title="Academics" subtitle="Grades Management" />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:diploma-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Grades</h2>
        </div>
        <div class="px-6 py-16 flex flex-col items-center justify-center text-center gap-3">
            <iconify-icon icon="solar:diploma-bold" width="48" class="text-slate-200 dark:text-slate-700"></iconify-icon>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Grades module coming soon</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">This section is under development.</p>
        </div>
    </div>

</div>
@endsection
