@extends('layout.admin_layout')
@section('title', 'Documents')
@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 bg-slate-50/50 p-4">
    <div class="mb-8"><h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Documents</h1><p class="mt-1 text-sm text-slate-500">Student submitted documents.</p></div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach(['Birth Certificate', 'Report Card', 'Good Moral', 'Form 137', 'Medical Certificate', 'ID Picture'] as $doc)
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50"><iconify-icon icon="solar:file-text-linear" width="22" class="text-blue-500"></iconify-icon></div>
            <div><p class="text-sm font-medium text-slate-700">{{ $doc }}</p><p class="text-xs text-slate-400">Required document</p></div>
        </div>
        @endforeach
    </div>
    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection