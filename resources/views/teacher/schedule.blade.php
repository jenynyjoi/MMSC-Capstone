@extends('layouts.teacher_layout')
@section('title', 'Schedule')
@section('page_title', 'My Schedule')
@section('page_subtitle', 'View your weekly class schedule')

@section('content')
<div class="p-6">
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20">
                <iconify-icon icon="uis:schedule" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Class Schedule</h2>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">S.Y. {{ \App\Models\SchoolYear::activeName() }}</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center py-20 text-center px-6">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5 mb-4">
                <iconify-icon icon="uis:schedule" width="28" class="text-slate-400 dark:text-slate-500"></iconify-icon>
            </div>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Schedule coming soon</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 max-w-xs">Your weekly schedule will appear here once class schedules are configured by the admin.</p>
        </div>
    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection
