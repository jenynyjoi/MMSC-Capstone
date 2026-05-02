@extends('layouts.teacher_layout')
@section('title', 'Class Roster')
@section('page_title', 'Class Roster')
@section('page_subtitle', 'Student list for your assigned sections')

@section('content')
<div class="p-6">
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20">
                    <iconify-icon icon="solar:users-group-rounded-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $section ? $section->section_name : 'All Students' }}
                    </h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                        {{ $students->total() }} student(s) · S.Y. {{ \App\Models\SchoolYear::activeName() }}
                    </p>
                </div>
            </div>
            <a href="{{ route('teacher.classes.list') }}"
                class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon>Back to Classes
            </a>
        </div>

        @if($students->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5 mb-4">
                    <iconify-icon icon="solar:users-group-rounded-linear" width="28" class="text-slate-400 dark:text-slate-500"></iconify-icon>
                </div>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No students found</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">No enrolled students in this section for the current school year.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.02] text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <th class="px-5 py-3.5 text-left">#</th>
                            <th class="px-5 py-3.5 text-left">Student ID</th>
                            <th class="px-5 py-3.5 text-left">Name</th>
                            <th class="px-5 py-3.5 text-center">Gender</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @foreach($students as $i => $student)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-3 text-xs text-slate-400 dark:text-slate-500">{{ $students->firstItem() + $i }}</td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $student->student_id ?? '—' }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800 dark:text-white">{{ $student->last_name }}, {{ $student->first_name }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                    {{ $student->gender === 'Male' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300' }}">
                                    {{ $student->gender }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                    Active
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                {{ $students->withQueryString()->links() }}
            </div>
        @endif

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection
