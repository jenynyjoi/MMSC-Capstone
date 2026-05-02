@extends('layouts.teacher_layout')
@section('title', 'Class List')
@section('page_title', 'My Classes')
@section('page_subtitle', 'All sections you are teaching this school year')

@section('content')
<div class="p-6">
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20">
                <iconify-icon icon="solar:book-2-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Class List</h2>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">S.Y. {{ \App\Models\SchoolYear::activeName() }} · {{ $sections->count() }} section(s)</p>
            </div>
        </div>

        @if($sections->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5 mb-4">
                    <iconify-icon icon="solar:book-2-linear" width="28" class="text-slate-400 dark:text-slate-500"></iconify-icon>
                </div>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No classes assigned</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">You have no sections assigned for this school year.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.02] text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <th class="px-5 py-3.5 text-left">#</th>
                            <th class="px-5 py-3.5 text-left">Section</th>
                            <th class="px-5 py-3.5 text-left">Grade Level</th>
                            <th class="px-5 py-3.5 text-left">Program</th>
                            <th class="px-5 py-3.5 text-center">Subjects</th>
                            <th class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @foreach($sections as $i => $sec)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-4 text-xs text-slate-400 dark:text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-800 dark:text-white">{{ $sec['section_name'] }}</td>
                            <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $sec['grade_level'] }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                    {{ ($sec['program_level'] ?? '') === 'SHS' ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300'
                                     : (($sec['program_level'] ?? '') === 'JHS' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'
                                     : 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300') }}">
                                    {{ $sec['program_level'] ?? 'Elem' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center h-6 min-w-[24px] rounded-full bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20 text-xs font-bold text-[#0d4c8f] dark:text-blue-300 px-2">
                                    {{ $sec['subject_count'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('teacher.classes.roster', ['section' => $sec['section_id']]) }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 dark:border-dark-border px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:users-group-rounded-linear" width="13"></iconify-icon>Roster
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection
