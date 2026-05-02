@extends('layouts.teacher_layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back, ' . auth()->user()->name . '. Here\'s today\'s overview.')

@section('content')
<main class="p-6 space-y-5">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 mb-3">
                        <iconify-icon icon="solar:book-2-bold" width="22" class="text-blue-500 dark:text-blue-300"></iconify-icon>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">My Classes</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Active this term</p>
                </div>
                <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalSections }}</h3>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 mb-3">
                        <iconify-icon icon="solar:users-group-rounded-linear" width="22" class="text-green-600 dark:text-green-300"></iconify-icon>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">My Students</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Across all sections</p>
                </div>
                <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalStudents }}</h3>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-50 dark:bg-yellow-900/20 mb-3">
                        <iconify-icon icon="solar:pen-new-square-linear" width="22" class="text-yellow-500 dark:text-yellow-300"></iconify-icon>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Subjects Taught</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">This school year</p>
                </div>
                <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalSubjects }}</h3>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-50 dark:bg-teal-900/20 mb-3">
                        <iconify-icon icon="solar:clock-circle-linear" width="22" class="text-teal-500 dark:text-teal-300"></iconify-icon>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Hrs / Week</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Total teaching load</p>
                </div>
                <h3 class="text-3xl font-bold tracking-tight text-teal-500 dark:text-teal-400">{{ number_format($totalHours, 0) }}</h3>
            </div>
        </div>

    </div>

    {{-- Row 2: Quick Actions · Today's Schedule · Reminders --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- Quick Actions --}}
        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 dark:bg-white/10">
                    <iconify-icon icon="solar:bolt-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Quick Actions</h3>
            </div>
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('teacher.attendance') }}"
                    class="w-full rounded-lg border border-blue-400 py-2.5 text-sm font-medium text-blue-500 transition hover:bg-blue-50 dark:border-blue-500 dark:text-blue-400 dark:hover:bg-blue-900/20 flex items-center justify-center gap-2">
                    <iconify-icon icon="solar:calendar-check-linear" width="16"></iconify-icon>Take Attendance
                </a>
                <a href="{{ route('teacher.grades') }}"
                    class="w-full rounded-lg border border-yellow-400 py-2.5 text-sm font-medium text-yellow-500 transition hover:bg-yellow-50 dark:border-yellow-500 dark:text-yellow-400 dark:hover:bg-yellow-900/20 flex items-center justify-center gap-2">
                    <iconify-icon icon="solar:pen-2-linear" width="16"></iconify-icon>Enter Grades
                </a>
                <a href="{{ route('teacher.schedule') }}"
                    class="w-full rounded-lg border border-green-400 py-2.5 text-sm font-medium text-green-600 transition hover:bg-green-50 dark:border-green-500 dark:text-green-400 dark:hover:bg-green-900/20 flex items-center justify-center gap-2">
                    <iconify-icon icon="uis:schedule" width="16"></iconify-icon>View My Schedule
                </a>
                <a href="{{ route('teacher.classes.list') }}"
                    class="w-full rounded-lg border border-red-400 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:border-red-500 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center justify-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-linear" width="16"></iconify-icon>View Class List
                </a>
            </div>
        </div>

        {{-- My Assigned Subjects --}}
        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 dark:bg-white/10">
                        <iconify-icon icon="solar:notebook-bold" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">My Subjects</h3>
                </div>
                <a href="{{ route('teacher.my-subjects') }}"
                    class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 dark:border-dark-border text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                    View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
                </a>
            </div>
            <div class="flex flex-col gap-2.5">
                @forelse($recentSubjects as $alloc)
                <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
                    <div>
                        <p class="text-xs font-semibold text-slate-800 dark:text-white truncate max-w-[140px]">{{ $alloc->subject_name }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $alloc->section?->section_name ?? '—' }}</p>
                    </div>
                    <span class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-300">{{ number_format($alloc->hours_per_week, 1) }}h</span>
                </div>
                @empty
                <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">No subjects assigned for this year.</p>
                @endforelse
            </div>
        </div>

        {{-- Announcements --}}
        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 dark:bg-white/10">
                        <iconify-icon icon="mdi:announcement" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Announcements</h3>
                </div>
                <a href="{{ route('teacher.announcements') }}"
                    class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 dark:border-dark-border text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                    View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
                </a>
            </div>
            <div class="flex flex-col gap-2">
                @forelse($announcements as $ann)
                <div class="flex items-start gap-2 py-2 border-b border-slate-100 dark:border-dark-border last:border-0">
                    <span class="mt-1.5 h-2 w-2 rounded-full bg-blue-500 shrink-0"></span>
                    <div>
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $ann->title }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $ann->created_at->format('M d') }} · Admin</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">No announcements at this time.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Row 3: Attendance Chart · Class Overview --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:chart-2-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Weekly Attendance Trend</h3>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#0d4c8f]"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Present</span></div>
                    <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-400"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Absent</span></div>
                </div>
            </div>
            <div class="relative h-52">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-2">
                <iconify-icon icon="solar:book-2-bold" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">My Classes</h3>
            </div>
            <div class="flex flex-col gap-2.5">
                @forelse($recentSubjects->take(6) as $alloc)
                <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
                    <div>
                        <p class="text-xs font-semibold text-slate-800 dark:text-white truncate max-w-[130px]">{{ $alloc->subject_name }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $alloc->section?->section_name ?? '—' }}</p>
                    </div>
                    <span class="text-xs font-bold text-green-500">—%</span>
                </div>
                @empty
                <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">No classes yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <p class="text-center text-xs text-slate-400 py-4">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>

</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark = () => document.documentElement.classList.contains('dark');
const gridColor = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
const labelColor = () => isDark() ? '#94a3b8' : '#64748b';

const attCtx = document.getElementById('attendanceChart')?.getContext('2d');
if (attCtx) {
    const attChart = new Chart(attCtx, {
        type: 'bar',
        data: {
            labels: ['Mon','Tue','Wed','Thu','Fri','Mon','Tue','Wed','Thu','Fri'],
            datasets: [
                { label:'Present', data:[138,141,135,143,140,139,144,137,145,142], backgroundColor:'rgba(13,76,143,0.75)', borderRadius:5, borderSkipped:false },
                { label:'Absent',  data:[10,7,13,5,8,9,4,11,3,6], backgroundColor:'rgba(248,113,113,0.7)', borderRadius:5, borderSkipped:false }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:false} },
            scales:{
                x:{ stacked:true, grid:{color:gridColor()}, ticks:{color:labelColor(), font:{size:11, family:'Poppins'}} },
                y:{ stacked:true, grid:{color:gridColor()}, ticks:{color:labelColor(), font:{size:11, family:'Poppins'}} }
            }
        }
    });
}
</script>
@endpush
