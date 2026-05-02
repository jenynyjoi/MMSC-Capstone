@extends('layouts.teacher_layout')
@section('title', 'Announcements')
@section('page_title', 'Announcements')
@section('page_subtitle', 'School-wide and admin announcements')

@section('content')
<div class="p-6">
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20">
                <iconify-icon icon="mdi:announcement" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">All Announcements</h2>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $announcements->total() }} total</p>
            </div>
        </div>

        @if($announcements->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5 mb-4">
                    <iconify-icon icon="mdi:announcement" width="28" class="text-slate-400 dark:text-slate-500"></iconify-icon>
                </div>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No announcements yet</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Check back later for updates from the admin.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-dark-border">
                @foreach($announcements as $ann)
                <div class="flex items-start gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 shrink-0 mt-0.5">
                        <iconify-icon icon="mdi:announcement" width="16" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $ann->title }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $ann->content }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5">{{ $ann->created_at->format('F d, Y · g:i A') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection
