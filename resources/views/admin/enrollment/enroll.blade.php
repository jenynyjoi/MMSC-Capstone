@extends('layouts.admin_layout')
@section('title', 'Enrollment')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4"
     x-data="{ tab: '{{ $tab }}' }">

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Page Header ── --}}
    <x-admin.page-header
        title="Enrollment"
        subtitle="Section Assignment and Student Promotion"
        school-year="{{ $activeSchoolYear }}"
        :show-menu="true"
    />

    {{-- ── Tabs ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="flex border-b border-slate-200 dark:border-dark-border">
            <button @click="tab = 'regular'"
                :class="tab === 'regular' ? 'border-b-2 border-[#0d4c8f] text-[#0d4c8f]' : 'text-slate-500 hover:text-slate-700'"
                class="px-6 py-3.5 text-xs font-semibold transition-all whitespace-nowrap bg-transparent">
                Regular Enrollee
            </button>
            <button @click="tab = 'irregular'"
                :class="tab === 'irregular' ? 'border-b-2 border-[#0d4c8f] text-[#0d4c8f]' : 'text-slate-500 hover:text-slate-700'"
                class="px-6 py-3.5 text-xs font-semibold transition-all whitespace-nowrap bg-transparent">
                Irregular Enrollee
            </button>
        </div>

        {{-- ═══════════════════════ REGULAR TAB ═══════════════════════ --}}
        <div x-show="tab === 'regular'" x-cloak>

            {{-- Header --}}
            <div class="px-6 pt-5 pb-3">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:clipboard-list-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                        <h2 class="text-base font-bold text-slate-800 dark:text-white">Pending Section Assignment</h2>
                    </div>
                    <button type="button" onclick="openEnrollModal('direct-enroll-modal')"
                        class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                        <iconify-icon icon="solar:user-plus-bold" width="14"></iconify-icon>
                        Enroll Student
                    </button>
                </div>
                <p class="text-xs text-slate-400 ml-6 mt-0.5">List of Regular Student Eligible for Enrollment</p>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 pb-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                        <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $regularStats['total'] }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 leading-tight">Total Regular Enrollees</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                        <iconify-icon icon="solar:user-id-bold" width="20" class="text-yellow-600 dark:text-yellow-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $regularStats['pending'] }}</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 leading-tight">Pending Section Assignment</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                        <iconify-icon icon="solar:check-circle-bold" width="20" class="text-green-600 dark:text-green-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $regularStats['assigned'] }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 leading-tight">Assigned Sections</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                        <iconify-icon icon="solar:door-open-bold" width="20" class="text-amber-600 dark:text-amber-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $regularStats['available_sections'] }}</p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 leading-tight">Available Sections</p>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-1 text-xs text-slate-500 font-medium mb-3">
                    <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
                </div>
                <form method="GET" action="{{ route('admin.enrollment.enroll') }}">
                    <input type="hidden" name="tab" value="regular">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-3">
                        {{-- School Year --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">School Year</label>
                            <div class="relative">
                                <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="2026-2027" {{ request('school_year','2026-2027')==='2026-2027'?'selected':'' }}>SY 2026-2027</option>
                                    <option value="2025-2026" {{ request('school_year')==='2025-2026'?'selected':'' }}>SY 2025-2026</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        {{-- Grade Level --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Grade Level Applied</label>
                            <div class="relative">
                                <select name="grade" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="Elementary"         {{ request('grade')==='Elementary'?'selected':'' }}>Elementary</option>
                                    <option value="Junior High School" {{ request('grade')==='Junior High School'?'selected':'' }}>Junior High School</option>
                                    <option value="Senior High School" {{ request('grade')==='Senior High School'?'selected':'' }}>Senior High School</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        {{-- Gender --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Gender</label>
                            <div class="relative">
                                <select name="gender" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="Male"   {{ request('gender')==='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ request('gender')==='Female'?'selected':'' }}>Female</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        {{-- Status --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Status</label>
                            <div class="relative">
                                <select name="status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="pending"  {{ request('status')==='pending'?'selected':'' }}>Pending</option>
                                    <option value="assigned" {{ request('status')==='assigned'?'selected':'' }}>Assigned</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ route('admin.enrollment.enroll', ['tab' => 'regular']) }}"
                           class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                           Clear All
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                        <option>10</option><option>25</option><option>50</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" placeholder="Search Student, Student no.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
                </div>
            </div>

            {{-- Regular Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:1100px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="reg-check-all" class="rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                            <th class="px-4 py-3">Student Name</th>
                            <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                            <th class="px-4 py-3 whitespace-nowrap">Program Level Applied</th>
                            <th class="px-4 py-3 whitespace-nowrap">Grade Level Applied</th>
                            <th class="px-4 py-3 whitespace-nowrap">Gender</th>
                            <th class="px-4 py-3 whitespace-nowrap">Enrolled Date</th>
                            <th class="px-4 py-3 whitespace-nowrap">Section Status</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @forelse ($regularStudents as $enrollment)
                        @php
                            $statusClass = $enrollment->assignment_status === 'assigned'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-amber-100 text-amber-700';
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3">
                                @if($enrollment->assignment_status === 'assigned')
                                <input type="checkbox" disabled title="Already assigned"
                                    class="rounded text-slate-300 cursor-not-allowed opacity-40">
                                @else
                                <input type="checkbox" name="enrollment_ids[]" value="{{ $enrollment->id }}"
                                    class="reg-check rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs font-mono text-slate-400 truncate">{{ $enrollment->student->student_id ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $enrollment->student->full_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $enrollment->school_year }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $enrollment->program_level ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                <span class="font-medium">{{ $enrollment->grade_level }}</span>
                                @if($enrollment->track || $enrollment->strand)
                                <span class="block mt-0.5 text-[10px] text-slate-400">
                                    {{ implode(' · ', array_filter([$enrollment->track, $enrollment->strand])) }}
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $enrollment->gender ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $enrollment->enrollment_date?->format('n/j/y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($enrollment->assignment_status === 'assigned' && $enrollment->section_name)
                                <div class="flex flex-col gap-0.5">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }} w-fit">Assigned</span>
                                    <span class="text-xs text-slate-400 pl-0.5">{{ $enrollment->section_name }}</span>
                                </div>
                                @else
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($enrollment->assignment_status === 'assigned')
                                    <button type="button" disabled title="Already assigned"
                                        class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-medium text-slate-300 cursor-not-allowed whitespace-nowrap">
                                        <iconify-icon icon="solar:check-circle-linear" width="13"></iconify-icon>
                                        Assign Section
                                    </button>
                                    @else
                                    <button type="button"
                                        onclick="openAssignModal({{ $enrollment->id }},'{{ addslashes($enrollment->student->full_name ?? '') }}','{{ addslashes($enrollment->grade_level) }}','{{ addslashes($enrollment->student->student_id ?? '') }}','Regular','{{ addslashes($enrollment->track ?? '') }}','{{ addslashes($enrollment->strand ?? '') }}')"
                                        class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:add-circle-linear" width="13"></iconify-icon>
                                        Assign Section
                                    </button>
                                    @endif
                                    @if($enrollment->assignment_status === 'assigned')
                                    <button type="button"
                                        onclick="openEditModal({{ $enrollment->id }},'{{ addslashes($enrollment->student->full_name ?? '') }}','{{ addslashes(\App\Models\Section::formatName($enrollment->grade_level, $enrollment->section_name ?? '—', $enrollment->strand)) }}','{{ addslashes($enrollment->grade_level) }}')"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition-colors"
                                        title="Edit Section">
                                        <iconify-icon icon="solar:pen-bold" width="13"></iconify-icon>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-14 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <iconify-icon icon="solar:inbox-linear" width="32" class="text-slate-300"></iconify-icon>
                                    <p class="text-xs text-slate-400">No students pending section assignment.</p>
                                    <p class="text-xs text-slate-300">Approve applications in Admission to see students here.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">
                    Showing {{ $regularStudents->firstItem() ?? 0 }}–{{ $regularStudents->lastItem() ?? 0 }}
                    of {{ $regularStudents->total() }}
                </p>
                {{ $regularStudents->links() }}
            </div>

        </div>{{-- end regular tab --}}

        {{-- ═══════════════════════ IRREGULAR TAB ═══════════════════════ --}}
        <div x-show="tab === 'irregular'" x-cloak>

            {{-- Header --}}
            <div class="px-6 pt-5 pb-3">
                <div class="flex items-center gap-2 mb-1">
                    <iconify-icon icon="solar:clipboard-list-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Pending Section Assignment</h2>
                </div>
                <p class="text-xs text-slate-400 ml-6">List of Irregular Student Eligible for Enrollment</p>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 pb-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                        <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $irregularStats['total'] }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 leading-tight">Total Irregular Enrollee</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                        <iconify-icon icon="solar:user-id-bold" width="20" class="text-yellow-600 dark:text-yellow-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $irregularStats['pending'] }}</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 leading-tight">Pending Section Assignment</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                        <iconify-icon icon="solar:check-circle-bold" width="20" class="text-green-600 dark:text-green-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $irregularStats['fully_scheduled'] }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 leading-tight">Fully Scheduled</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-orange-200 bg-orange-50 dark:border-orange-900/30 dark:bg-orange-900/10 px-4 py-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-900/30">
                        <iconify-icon icon="solar:pie-chart-2-bold" width="20" class="text-orange-600 dark:text-orange-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $irregularStats['incomplete'] }}</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1 leading-tight">Incomplete Schedule</p>
                    </div>
                </div>
            </div>

            {{-- Irregular Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-1 text-xs text-slate-500 font-medium mb-3">
                    <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
                </div>
                <form method="GET" action="{{ route('admin.enrollment.enroll') }}">
                    <input type="hidden" name="tab" value="irregular">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">School Year</label>
                            <div class="relative">
                                <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="2026-2027">SY 2026-2027</option>
                                    <option value="2024-2025">SY 2024-2025</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Grade</label>
                            <div class="relative">
                                <select name="grade" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="Grade 11" {{ request('grade')==='Grade 11'?'selected':'' }}>Grade 11</option>
                                    <option value="Grade 12" {{ request('grade')==='Grade 12'?'selected':'' }}>Grade 12</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Track</label>
                            <div class="relative">
                                <select name="track" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="Academic"      {{ request('track')==='Academic'?'selected':'' }}>Academic</option>
                                    <option value="TVL"           {{ request('track')==='TVL'?'selected':'' }}>TVL</option>
                                    <option value="Arts & Design" {{ request('track')==='Arts & Design'?'selected':'' }}>Arts & Design</option>
                                    <option value="Sports"        {{ request('track')==='Sports'?'selected':'' }}>Sports</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Strand</label>
                            <div class="relative">
                                <select name="strand" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="STEM"  {{ request('strand')==='STEM'?'selected':'' }}>STEM</option>
                                    <option value="ABM"   {{ request('strand')==='ABM'?'selected':'' }}>ABM</option>
                                    <option value="HUMSS" {{ request('strand')==='HUMSS'?'selected':'' }}>HUMSS</option>
                                    <option value="GAS"   {{ request('strand')==='GAS'?'selected':'' }}>GAS</option>
                                    <option value="HE"    {{ request('strand')==='HE'?'selected':'' }}>HE</option>
                                    <option value="ICT"   {{ request('strand')==='ICT'?'selected':'' }}>ICT</option>
                                    <option value="IA"    {{ request('strand')==='IA'?'selected':'' }}>IA</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ route('admin.enrollment.enroll', ['tab' => 'irregular']) }}"
                           class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                           Clear All
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                        <option>10</option><option>25</option><option>50</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" placeholder="Search Student, Student no.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
                </div>
            </div>

            {{-- Irregular Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:1280px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="irr-check-all" class="rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                            <th class="px-4 py-3">Student Name</th>
                            <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                            <th class="px-4 py-3 whitespace-nowrap">Program Level Applied</th>
                            <th class="px-4 py-3 whitespace-nowrap">Grade Level Applied</th>
                            <th class="px-4 py-3 whitespace-nowrap">Track</th>
                            <th class="px-4 py-3 whitespace-nowrap">Strand</th>
                            <th class="px-4 py-3 whitespace-nowrap">Gender</th>
                            <th class="px-4 py-3 whitespace-nowrap">Enrolled Date</th>
                            <th class="px-4 py-3 whitespace-nowrap">Section Status</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @forelse ($irregularStudents as $enrollment)
                        @php
                            $irStatusClass = match($enrollment->assignment_status) {
                                'fully_scheduled' => 'bg-green-100 text-green-700',
                                'incomplete'      => 'bg-orange-100 text-orange-700',
                                default           => 'bg-amber-100 text-amber-700',
                            };
                            $irStatusLabel = match($enrollment->assignment_status) {
                                'fully_scheduled' => 'Fully Scheduled',
                                'incomplete'      => 'Incomplete',
                                default           => 'Pending',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="irr-check rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="px-4 py-3 text-xs font-mono text-slate-400 truncate">{{ $enrollment->student->student_id ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $enrollment->student->full_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $enrollment->school_year }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $enrollment->program_level ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $enrollment->grade_level }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $enrollment->track ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $enrollment->strand ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $enrollment->gender ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $enrollment->enrollment_date?->format('n/j/y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $irStatusClass }}">
                                    {{ $irStatusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- PENDING: only Assign To Classes --}}
                                    @if($enrollment->assignment_status === 'pending')
                                    <button type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:users-group-rounded-linear" width="13"></iconify-icon>
                                        Assign To Classes
                                    </button>
                                    @endif

                                    {{-- INCOMPLETE: Assign + View --}}
                                    @if($enrollment->assignment_status === 'incomplete')
                                    <button type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:users-group-rounded-linear" width="13"></iconify-icon>
                                        Assign
                                    </button>
                                    <button type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-amber-400 bg-amber-50 hover:bg-amber-100 px-3 py-1 text-xs font-medium text-amber-600 transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon>
                                        View
                                    </button>
                                    @endif

                                    {{-- FULLY SCHEDULED: View + Edit --}}
                                    @if($enrollment->assignment_status === 'fully_scheduled')
                                    <button type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-amber-400 bg-amber-50 hover:bg-amber-100 px-3 py-1 text-xs font-medium text-amber-600 transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon>
                                        View
                                    </button>
                                    <button type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-green-400 bg-green-50 hover:bg-green-100 px-3 py-1 text-xs font-medium text-green-600 transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:pen-linear" width="13"></iconify-icon>
                                        Edit
                                    </button>
                                    @endif

                                    {{-- FALLBACK for old status values --}}
                                    @if(!in_array($enrollment->assignment_status, ['pending','incomplete','fully_scheduled']))
                                    <button type="button"
                                        class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors whitespace-nowrap">
                                        <iconify-icon icon="solar:add-circle-linear" width="13"></iconify-icon>
                                        Assign To Classes
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-4 py-14 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <iconify-icon icon="solar:inbox-linear" width="32" class="text-slate-300"></iconify-icon>
                                    <p class="text-xs text-slate-400">No irregular students found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">
                    Showing {{ $irregularStudents->firstItem() ?? 0 }}–{{ $irregularStudents->lastItem() ?? 0 }}
                    of {{ $irregularStudents->total() }}
                </p>
                {{ $irregularStudents->links() }}
            </div>

            {{-- Irregular Send Notice bar --}}
            <div id="irr-bulk-bar" class="hidden items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/5">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-linear" width="16" class="text-slate-500"></iconify-icon>
                    <span id="irr-count" class="text-xs font-medium text-slate-600 dark:text-slate-300">0 Selected Student</span>
                </div>
                <button class="flex items-center gap-2 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon>
                    Send Notice
                </button>
            </div>

        </div>{{-- end irregular tab --}}

    </div>{{-- end card --}}

    {{-- ── Regular Bulk Bar (fixed bottom) ── --}}
    <div id="reg-bulk-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 hidden">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
            <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-400"></iconify-icon>
            <span id="reg-bulk-count" class="text-xs font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">0 Selected</span>
            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700"></div>
            <button type="button" onclick="openBulkAssignModal()"
                class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                <iconify-icon icon="solar:add-circle-linear" width="14"></iconify-icon>
                Assign Section
            </button>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>


{{-- ══════════════════════════════════════════
     DIRECT ENROLL MODAL (bypass admission)
══════════════════════════════════════════ --}}
<div id="direct-enroll-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEnrollModal('direct-enroll-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:92vh;overflow-y:auto">

        {{-- Header --}}
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h3 class="text-white text-sm font-bold tracking-wide">ENROLL STUDENT</h3>
                <p class="text-blue-200 text-xs mt-0.5">Bypass admission — directly enroll and assign section</p>
            </div>
            <button onclick="closeEnrollModal('direct-enroll-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div class="px-6 py-5 space-y-6">

            {{-- ── PERSONAL INFORMATION ── --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Personal Information</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">First Name <span class="text-red-500">*</span></label>
                        <input id="de-first-name" type="text" placeholder="e.g. Maria"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Middle Name</label>
                        <input id="de-middle-name" type="text" placeholder="e.g. Santos"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Last Name <span class="text-red-500">*</span></label>
                        <input id="de-last-name" type="text" placeholder="e.g. Cruz"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Suffix</label>
                        <input id="de-suffix" type="text" placeholder="e.g. Jr., III"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Gender <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="de-gender" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">— Select —</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Date of Birth <span class="text-red-500">*</span></label>
                        <input id="de-dob" type="date"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Mobile Number</label>
                        <input id="de-mobile" type="text" placeholder="09XXXXXXXXX"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Personal Email</label>
                        <input id="de-email" type="email" placeholder="email@example.com"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">LRN</label>
                        <input id="de-lrn" type="text" placeholder="12-digit LRN"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-xs font-medium text-slate-500">Home Address</label>
                        <input id="de-address" type="text" placeholder="Street, Barangay, City"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- ── GUARDIAN INFORMATION ── --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Guardian / Parent</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Guardian Name <span class="text-red-500">*</span></label>
                        <input id="de-guardian-name" type="text" placeholder="Full name"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Relationship</label>
                        <div class="relative">
                            <select id="de-guardian-rel" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">— Select —</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Guardian">Guardian</option>
                                <option value="Grandparent">Grandparent</option>
                                <option value="Sibling">Sibling</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Guardian Contact</label>
                        <input id="de-guardian-contact" type="text" placeholder="09XXXXXXXXX"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Guardian Email</label>
                        <input id="de-guardian-email" type="email" placeholder="guardian@example.com"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- ── ENROLLMENT DETAILS ── --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Enrollment Details</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">School Year <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="de-school-year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="2026-2027">SY 2026-2027</option>
                                <option value="2025-2026">SY 2025-2026</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Enrollment Type <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="de-enroll-type" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="new">New Student</option>
                                <option value="transferee">Transferee</option>
                                <option value="return">Returning Student</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Program Level <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="de-program" onchange="deUpdateGrades(this.value)"
                                class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">— Select —</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Junior High School">Junior High School</option>
                                <option value="Senior High School">Senior High School</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500">Grade Level <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="de-grade" onchange="deLoadSections()"
                                class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">— Select Program First —</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    {{-- SHS fields --}}
                    <div id="de-shs-block" class="col-span-2 grid grid-cols-2 gap-3 hidden">
                        <div class="col-span-2 flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-500">SHS Student Type <span class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600">
                                    <input type="radio" name="de_shs_type" id="de-shs-type-regular" value="Regular" checked class="text-blue-600 focus:ring-blue-500">
                                    Regular
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600">
                                    <input type="radio" name="de_shs_type" id="de-shs-type-irregular" value="Irregular" class="text-blue-600 focus:ring-blue-500">
                                    Irregular
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-500">Track</label>
                            <div class="relative">
                                <select id="de-track" onchange="deFilterStrands(this.value); deLoadSections()"
                                    class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">— Select Track —</option>
                                    <option value="Academic">Academic</option>
                                    <option value="TVL">TVL</option>
                                    <option value="Arts & Design">Arts & Design</option>
                                    <option value="Sports">Sports</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-500">Strand</label>
                            <div class="relative">
                                <select id="de-strand" onchange="deLoadSections()"
                                    class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">— Select Strand —</option>
                                    <option value="STEM"  data-track="Academic">STEM</option>
                                    <option value="ABM"   data-track="Academic">ABM</option>
                                    <option value="HUMSS" data-track="Academic">HUMSS</option>
                                    <option value="GAS"   data-track="Academic">GAS</option>
                                    <option value="HE"    data-track="TVL">HE</option>
                                    <option value="ICT"   data-track="TVL">ICT</option>
                                    <option value="IA"    data-track="TVL">IA</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION ASSIGNMENT ── --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Section Assignment</p>
                <div id="de-section-placeholder" class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-xs text-slate-400">
                    Select a grade level above to load available sections.
                </div>
                <div id="de-sections-loading" class="hidden py-6 text-center text-xs text-slate-400">
                    <iconify-icon icon="solar:loading-bold" width="20" class="animate-spin text-blue-500 mb-1"></iconify-icon><br>Loading sections...
                </div>
                <div id="de-sections-list" class="space-y-2 max-h-52 overflow-y-auto"></div>
                <div id="de-no-sections" class="hidden rounded-xl border border-orange-200 bg-orange-50 px-4 py-3 text-xs text-orange-700">
                    <iconify-icon icon="solar:danger-triangle-bold" width="14" class="inline mr-1"></iconify-icon>
                    No available sections for this grade level.
                    <a href="{{ route('admin.classes.sections') }}" class="underline font-semibold ml-1">Create a section →</a>
                </div>
                <div id="de-section-indicator" class="hidden mt-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-xs">
                    <span class="text-slate-500">Selected:</span>
                    <span id="de-section-name" class="font-semibold text-[#0d4c8f] ml-1">—</span>
                </div>
            </div>

            {{-- ── ACTIONS ── --}}
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="button" id="de-submit-btn" onclick="submitDirectEnroll()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:user-plus-bold" width="14"></iconify-icon>
                    ENROLL &amp; ASSIGN
                </button>
                <button type="button" onclick="closeEnrollModal('direct-enroll-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     STAGE 3: INDIVIDUAL ASSIGN SECTION MODAL
══════════════════════════════════════════ --}}
<div id="assign-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEnrollModal('assign-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">

        {{-- Header --}}
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h3 id="assign-modal-title" class="text-white text-sm font-bold">ASSIGN SECTION</h3>
                <p id="assign-modal-subtitle" class="text-blue-200 text-xs mt-0.5"></p>
            </div>
            <button onclick="closeEnrollModal('assign-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div class="px-6 py-5 space-y-4">

            {{-- Student info --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 p-4">
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div><p class="text-slate-400 mb-0.5">Student ID</p><p id="assign-student-id" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div><p class="text-slate-400 mb-0.5">Student Name</p><p id="assign-student-name" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div><p class="text-slate-400 mb-0.5">Grade Level</p><p id="assign-grade" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div><p class="text-slate-400 mb-0.5">Student Type</p><p id="assign-type" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div id="assign-track-row" class="hidden"><p class="text-slate-400 mb-0.5">Track</p><p id="assign-track" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div id="assign-strand-row" class="hidden"><p class="text-slate-400 mb-0.5">Strand</p><p id="assign-strand" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                </div>
            </div>

            {{-- Select Section --}}
            <div>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Select Section:</p>
                <div id="sections-loading" class="hidden py-6 text-center text-xs text-slate-400">
                    <iconify-icon icon="solar:loading-bold" width="20" class="animate-spin text-blue-500 mb-1"></iconify-icon><br>Loading sections...
                </div>
                <div id="sections-list" class="space-y-2 max-h-64 overflow-y-auto"></div>
                <div id="no-sections-msg" class="hidden rounded-xl border border-orange-200 bg-orange-50 px-4 py-3 text-xs text-orange-700">
                    <iconify-icon icon="solar:danger-triangle-bold" width="14" class="inline mr-1"></iconify-icon>
                    No available sections found for this grade level.
                    <a href="{{ route('admin.classes.sections') }}" class="underline font-semibold ml-1">Create a section first →</a>
                </div>
            </div>

            {{-- Selected indicator --}}
            <div id="assign-selected-indicator" class="hidden rounded-xl border border-blue-200 bg-blue-50 dark:bg-blue-900/10 px-4 py-2.5 text-xs">
                <span class="text-slate-500">Selected:</span>
                <span id="assign-selected-name" class="font-semibold text-[#0d4c8f] ml-1">—</span>
            </div>

            {{-- Send email checkbox --}}
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors">
                <input type="checkbox" id="assign-send-email" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Send confirmation email to student</span>
            </label>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" id="confirm-assign-btn" onclick="confirmAssign()"
                    class="px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    CONFIRM ASSIGNMENT
                </button>
                <button type="button" onclick="closeEnrollModal('assign-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     STAGE 4: BULK ASSIGN MODAL
══════════════════════════════════════════ --}}
<div id="bulk-assign-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEnrollModal('bulk-assign-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h3 class="text-white text-sm font-bold">BULK ASSIGNMENT</h3>
                <p id="bulk-modal-subtitle" class="text-blue-200 text-xs mt-0.5"></p>
            </div>
            <button onclick="closeEnrollModal('bulk-assign-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="bulk-modal-content" class="px-6 py-5">
            <div class="py-8 text-center text-xs text-slate-400">
                <iconify-icon icon="solar:loading-bold" width="20" class="animate-spin text-blue-500 mb-1"></iconify-icon><br>Loading...
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     STAGE 5: SECTION BALANCING PREVIEW MODAL
══════════════════════════════════════════ --}}
<div id="balance-preview-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEnrollModal('balance-preview-modal')"></div>
    <div class="relative w-full max-w-xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">SECTION BALANCING PREVIEW</h3>
            <button onclick="closeEnrollModal('balance-preview-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="balance-preview-content" class="px-6 py-5">
            <div class="py-8 text-center text-xs text-slate-400">Loading...</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     EDIT SECTION MODAL
══════════════════════════════════════════ --}}
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEnrollModal('edit-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h3 class="text-white text-sm font-bold">EDIT SECTION</h3>
                <p id="edit-modal-subtitle" class="text-blue-200 text-xs mt-0.5"></p>
            </div>
            <button onclick="closeEnrollModal('edit-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 p-4 text-xs">
                <p class="text-slate-400 mb-0.5">Current Section</p>
                <p id="edit-current-section" class="font-semibold text-slate-700 dark:text-slate-300">—</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">
                    Reason for Section Change <span class="text-red-500">*</span>
                </label>
                <textarea id="edit-reason" rows="3"
                    placeholder="e.g., schedule conflict, parent request, academic need"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">New Section <span class="text-red-500">*</span></label>
                <div id="edit-sections-list" class="space-y-2 max-h-52 overflow-y-auto"></div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <button type="button" onclick="confirmEditSection()"
                    class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-colors">
                    Approve &amp; Move
                </button>
                <button type="button" onclick="closeEnrollModal('edit-modal')"
                    class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── State ────────────────────────────────────────────────
let currentEnrollmentId      = null;
let currentSelectedSectionId = null;
let selectedRegularIds       = [];
let _bulkData                = null;
let selectedBulkSectionId    = null;

// ── Modal helpers ─────────────────────────────────────────
function openEnrollModal(id)  {
    if (id === 'direct-enroll-modal') deResetModal();
    document.getElementById(id)?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEnrollModal(id) { document.getElementById(id)?.classList.add('hidden');    document.body.style.overflow = ''; }

// ── Checkbox — Regular ────────────────────────────────────
const regCheckAll = document.getElementById('reg-check-all');
const regBulkBar  = document.getElementById('reg-bulk-bar');
const regCount    = document.getElementById('reg-bulk-count');

regCheckAll?.addEventListener('change', function () {
    document.querySelectorAll('.reg-check').forEach(cb => cb.checked = this.checked);
    updateRegBulkBar();
});
document.querySelectorAll('.reg-check').forEach(cb => cb.addEventListener('change', updateRegBulkBar));

function updateRegBulkBar() {
    const checked = [...document.querySelectorAll('.reg-check:checked')];
    selectedRegularIds = checked.map(cb => cb.value);
    if (regCount) regCount.textContent = selectedRegularIds.length + ' Selected';
    if (regBulkBar) regBulkBar.classList.toggle('hidden', selectedRegularIds.length === 0);
    if (regCheckAll) {
        regCheckAll.checked       = checked.length > 0 && checked.length === document.querySelectorAll('.reg-check').length;
        regCheckAll.indeterminate = checked.length > 0 && checked.length < document.querySelectorAll('.reg-check').length;
    }
}

// ── Checkbox — Irregular ──────────────────────────────────
const irrCheckAll = document.getElementById('irr-check-all');
irrCheckAll?.addEventListener('change', function () {
    document.querySelectorAll('.irr-check').forEach(cb => cb.checked = this.checked);
    const c   = [...document.querySelectorAll('.irr-check:checked')].length;
    const bar = document.getElementById('irr-bulk-bar');
    const lbl = document.getElementById('irr-count');
    if (bar) { bar.classList.toggle('hidden', c === 0); bar.classList.toggle('flex', c > 0); }
    if (lbl) lbl.textContent = c + ' Selected Student';
});

// ══════════════════════════════════════════════════════════
// STAGE 3 — INDIVIDUAL ASSIGN MODAL
// ══════════════════════════════════════════════════════════
function openAssignModal(enrollmentId, studentName, grade, studentId, type, track, strand) {
    currentEnrollmentId      = enrollmentId;
    currentSelectedSectionId = null;

    const shsLabel = [track, strand].filter(Boolean).join(' · ');
    const subtitle = shsLabel ? grade + ' — ' + shsLabel + ' | ' + type : grade + ' | ' + type;

    document.getElementById('assign-modal-title').textContent    = 'ASSIGN SECTION — ' + studentName;
    document.getElementById('assign-modal-subtitle').textContent = subtitle;
    document.getElementById('assign-student-id').textContent     = studentId   || '—';
    document.getElementById('assign-student-name').textContent   = studentName || '—';
    document.getElementById('assign-grade').textContent          = grade || '—';
    document.getElementById('assign-type').textContent           = type  || 'Regular';

    // Track / Strand rows — show only for SHS
    const trackRow  = document.getElementById('assign-track-row');
    const strandRow = document.getElementById('assign-strand-row');
    if (track) {
        document.getElementById('assign-track').textContent = track;
        trackRow.classList.remove('hidden');
    } else { trackRow.classList.add('hidden'); }
    if (strand) {
        document.getElementById('assign-strand').textContent = strand;
        strandRow.classList.remove('hidden');
    } else { strandRow.classList.add('hidden'); }

    document.getElementById('sections-list').innerHTML           = '';
    document.getElementById('no-sections-msg').classList.add('hidden');
    document.getElementById('sections-loading').classList.remove('hidden');
    document.getElementById('assign-selected-indicator').classList.add('hidden');

    openEnrollModal('assign-modal');

    fetch('{{ route("admin.enrollment.sections") }}?enrollment_id=' + enrollmentId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('sections-loading').classList.add('hidden');

        if (!data.sections || data.sections.length === 0) {
            document.getElementById('no-sections-msg').classList.remove('hidden');
            return;
        }

        const container = document.getElementById('sections-list');
        data.sections.forEach(s => {
            const isFull = s.available_slots === 0;
            const div    = document.createElement('div');

            div.className = 'flex items-start justify-between rounded-xl border px-4 py-3 transition-all ' +
                (isFull ? 'border-slate-200 bg-slate-50 opacity-60 cursor-not-allowed' : 'border-slate-200 hover:border-blue-300 hover:bg-blue-50/60 cursor-pointer');

            div.innerHTML = `
                <div class="flex items-start gap-3 min-w-0">
                    <div class="mt-0.5 shrink-0">
                        ${isFull
                            ? '<span class="text-slate-300"><iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon></span>'
                            : '<input type="radio" name="section_radio" class="section-radio text-blue-600 focus:ring-blue-500 mt-0.5">'}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">${s.section_name} — ${s.section_name.includes('Grade') ? '' : (data.enrollment?.grade || '')}</p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Capacity: ${s.current}/${s.capacity} &bull;
                            <span class="${isFull ? 'text-red-500 font-semibold' : 'text-green-600 font-semibold'}">
                                ${isFull ? 'FULL' : 'Available: ' + s.available_slots + ' slot' + (s.available_slots !== 1 ? 's' : '')}
                            </span>
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Adviser: ${s.adviser} &bull; Room: ${s.room}</p>
                    </div>
                </div>`;

            if (!isFull) {
                div.querySelector('input[type=radio]').addEventListener('change', () => {
                    selectSection(s.id, s.section_name, div);
                });
                div.addEventListener('click', (e) => {
                    if (e.target.tagName !== 'INPUT') {
                        div.querySelector('input[type=radio]').checked = true;
                        selectSection(s.id, s.section_name, div);
                    }
                });
            }
            container.appendChild(div);
        });
    })
    .catch(() => {
        document.getElementById('sections-loading').classList.add('hidden');
        document.getElementById('no-sections-msg').classList.remove('hidden');
    });
}

function selectSection(sectionId, sectionName, selectedDiv) {
    currentSelectedSectionId = sectionId;

    // Reset all rows
    document.querySelectorAll('#sections-list > div').forEach(d => {
        d.classList.remove('border-blue-400', 'bg-blue-50');
        d.classList.add('border-slate-200');
    });

    // Highlight selected
    selectedDiv.classList.add('border-blue-400', 'bg-blue-50');
    selectedDiv.classList.remove('border-slate-200');

    // Show selected indicator
    const row  = document.getElementById('assign-selected-indicator');
    const name = document.getElementById('assign-selected-name');
    if (row && name) {
        name.textContent = sectionName;
        row.classList.remove('hidden');
    }
}

function confirmAssign() {
    if (!currentSelectedSectionId) { showToast('Please select a section first.', 'error'); return; }

    const btn = document.getElementById('confirm-assign-btn');
    btn.disabled    = true;
    btn.innerHTML   = '<iconify-icon icon="solar:loading-bold" width="14" class="animate-spin"></iconify-icon> Assigning...';

    const sendEmail = document.getElementById('assign-send-email')?.checked ?? true;

    fetch('{{ route("admin.enrollment.assign") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            enrollment_id: currentEnrollmentId,
            section_id:    currentSelectedSectionId,
            send_email:    sendEmail,
        }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled  = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> CONFIRM ASSIGNMENT';
        if (data.success) {
            closeEnrollModal('assign-modal');
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1400);
        } else {
            showToast(data.message || 'Error assigning section.', 'error');
        }
    })
    .catch(() => {
        btn.disabled  = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> CONFIRM ASSIGNMENT';
        showToast('Request failed.', 'error');
    });
}

// ══════════════════════════════════════════════════════════
// STAGE 4 — BULK ASSIGN MODAL
// ══════════════════════════════════════════════════════════
function openBulkAssignModal() {
    if (selectedRegularIds.length === 0) return;
    selectedBulkSectionId = null;
    document.getElementById('bulk-modal-content').innerHTML =
        '<div class="py-10 text-center text-xs text-slate-400"><iconify-icon icon="solar:loading-bold" width="24" class="animate-spin text-blue-500 mb-2"></iconify-icon><br>Loading...</div>';
    document.getElementById('bulk-modal-subtitle').textContent = '';
    openEnrollModal('bulk-assign-modal');

    fetch('{{ route("admin.enrollment.bulk-preview") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ enrollment_ids: selectedRegularIds }),
    })
    .then(r => r.json())
    .then(data => {
        _bulkData = data;
        // Use only the pending IDs the server filtered down to
        if (data.enrollment_ids && data.enrollment_ids.length) {
            selectedRegularIds = data.enrollment_ids.map(String);
        }
        let subtitle = 'Pending Students: ' + data.total_students + ' | Grade Level: ' + (data.grade_level || 'Mixed');
        if (data.already_assigned_count > 0) subtitle += ' | ' + data.already_assigned_count + ' already assigned (excluded)';
        document.getElementById('bulk-modal-subtitle').textContent = subtitle;
        renderBulkModal(data);
    })
    .catch(() => {
        document.getElementById('bulk-modal-content').innerHTML =
            '<div class="py-8 text-center text-xs text-red-500">Failed to load. Please try again.</div>';
    });
}

function renderBulkModal(data) {
    const hasEnough  = data.has_enough_slots;
    const mixedGrade = data.mixed_grades;
    let html = '';

    // All selected were already assigned
    if (data.total_students === 0) {
        html = `<div class="py-8 text-center flex flex-col items-center gap-2">
            <iconify-icon icon="solar:check-circle-bold" width="32" class="text-green-400"></iconify-icon>
            <p class="text-sm font-semibold text-slate-700">All selected students are already assigned.</p>
            <p class="text-xs text-slate-400">No pending students to process.</p>
        </div>
        <div class="flex justify-end pt-3 border-t border-slate-100">
            <button type="button" onclick="closeEnrollModal('bulk-assign-modal')"
                class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Close</button>
        </div>`;
        document.getElementById('bulk-modal-content').innerHTML = html;
        return;
    }

    // Already-assigned exclusion notice
    if (data.already_assigned_count > 0) {
        html += `<div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-500 flex items-center gap-2">
            <iconify-icon icon="solar:info-circle-linear" width="14" class="shrink-0 text-slate-400"></iconify-icon>
            <span><strong>${data.already_assigned_count}</strong> already-assigned student(s) were excluded. Processing <strong>${data.total_students}</strong> pending student(s).</span>
        </div>`;
    }

    // Summary
    html += `<div class="mb-4 text-xs space-y-1">
        <div class="flex gap-2"><span class="text-slate-400 w-32">Pending Students:</span><strong>${data.total_students}</strong></div>
        <div class="flex gap-2"><span class="text-slate-400 w-32">Grade Level:</span><strong>${data.grade_level || '—'}</strong></div>
    </div>`;

    if (mixedGrade) {
        html += `<div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-700 flex items-start gap-2">
            <iconify-icon icon="solar:danger-triangle-bold" width="14" class="shrink-0 mt-0.5"></iconify-icon>
            <div>Mixed grade levels selected: <strong>${(data.grade_levels||[]).join(', ')}</strong>.<br>
            Bulk assignment requires the same grade level. Please re-select.</div>
        </div>`;
    }

    html += `<div class="border-t border-slate-100 my-3"></div>`;

    // Available sections table
    html += `<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Available Sections</p>
    <div class="rounded-xl border border-slate-200 overflow-hidden mb-4">
        <table class="w-full text-xs">
            <thead class="bg-slate-50">
                <tr class="text-slate-500 font-semibold">
                    <th class="px-3 py-2 text-left">Section Name</th>
                    <th class="px-3 py-2 text-center">Current Enrollment</th>
                    <th class="px-3 py-2 text-center">Capacity</th>
                    <th class="px-3 py-2 text-center">Available Slots</th>
                    <th class="px-3 py-2 text-left">Adviser</th>
                    <th class="px-3 py-2 text-left">Room</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">`;

    if (!data.sections || data.sections.length === 0) {
        html += `<tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">No sections found for this grade level.</td></tr>`;
    } else {
        data.sections.forEach(s => {
            const slotColor = s.available_slots === 0 ? 'text-red-500 font-semibold' : 'text-green-600 font-semibold';
            html += `<tr class="hover:bg-slate-50 ${s.is_full ? 'opacity-60' : ''}">
                <td class="px-3 py-2 font-medium text-slate-700">${s.section_name}</td>
                <td class="px-3 py-2 text-center text-slate-500">${s.current}</td>
                <td class="px-3 py-2 text-center text-slate-500">${s.capacity}</td>
                <td class="px-3 py-2 text-center ${slotColor}">${s.available_slots}</td>
                <td class="px-3 py-2 text-slate-500">${s.adviser}</td>
                <td class="px-3 py-2 text-slate-500">${s.room}</td>
            </tr>`;
        });
    }
    html += `</tbody></table></div>`;

    // Stats summary
    const shortage  = data.total_students - data.total_avail_slots;
    const statusBadge = hasEnough
        ? `<span class="inline-flex items-center gap-1 text-green-600 font-semibold"><iconify-icon icon="solar:check-circle-bold" width="13"></iconify-icon> Enough slots available</span>`
        : `<span class="inline-flex items-center gap-1 text-red-600 font-semibold"><iconify-icon icon="solar:danger-triangle-bold" width="13"></iconify-icon> NOT ENOUGH SLOTS! ${shortage} student(s) cannot be assigned.</span>`;

    html += `<div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 mb-4 text-xs space-y-1.5">
        <div class="flex justify-between"><span class="text-slate-500">Total Available Slots:</span><strong>${data.total_avail_slots}</strong></div>
        <div class="flex justify-between"><span class="text-slate-500">Students to Assign:</span><strong>${data.total_students}</strong></div>
        ${!hasEnough ? `<div class="flex justify-between"><span class="text-slate-500">Shortage:</span><strong class="text-red-600">${shortage} students</strong></div>` : ''}
        <div class="flex justify-between items-center"><span class="text-slate-500">Status:</span>${statusBadge}</div>
    </div>`;

    if (!hasEnough) {
        // Not enough slots — warning + 3 options
        html += `<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 mb-4 text-xs text-red-700 flex items-start gap-2">
            <iconify-icon icon="solar:danger-triangle-bold" width="14" class="shrink-0 mt-0.5"></iconify-icon>
            NOT ENOUGH SLOTS! ${shortage} student(s) cannot be assigned.
        </div>
        <div class="border-t border-slate-100 my-3"></div>
        <p class="text-xs font-semibold text-slate-600 mb-3">How would you like to handle this?</p>
        <div class="space-y-2 mb-4">
            <label class="flex items-start gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                <input type="radio" name="bulk_handling" value="split_sections" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Split between existing sections</p>
                    <div class="mt-1 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-500 space-y-0.5">
                        <p>System will redistribute students to balance sections</p>
                        <p>Min 20 students per section | Max 30 students per section</p>
                    </div>
                </div>
            </label>
            <label class="flex items-start gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                <input type="radio" name="bulk_handling" value="new_section" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Create new section</p>
                    <div class="mt-1 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-500">
                        <p>Redirect to Section Management to create new section</p>
                    </div>
                </div>
            </label>
            <label class="flex items-start gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                <input type="radio" name="bulk_handling" value="assign_available" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Assign to available only</p>
                    <div class="mt-1 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-500">
                        <p>Assign ${data.total_avail_slots} now, ${shortage} student(s) will remain in pending status</p>
                    </div>
                </div>
            </label>
        </div>`;
    } else {
        // Enough slots — distribution method
        html += `<div class="border-t border-slate-100 my-3"></div>
        <p class="text-xs font-semibold text-slate-600 mb-3">Distribution Method:</p>
        <div class="space-y-2 mb-4">
            <label class="flex items-start gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                <input type="radio" name="bulk_handling" value="single_section" checked class="mt-0.5 text-blue-600 focus:ring-blue-500">
                <div class="w-full">
                    <p class="text-xs font-semibold text-slate-700">Assign all to one section</p>
                    <div class="mt-2">
                        <select id="bulk-single-section-select" class="w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="selectedBulkSectionId = this.value ? parseInt(this.value) : null">
                            <option value="">— Select Section —</option>
                            ${(data.sections||[]).filter(s => !s.is_full).map(s =>
                                `<option value="${s.id}">${s.section_name} (${s.available_slots} slots available)${s.available_slots < data.total_students ? ' ⚠ Not enough for all students' : ''}</option>`
                            ).join('')}
                        </select>
                    </div>
                </div>
            </label>
            <label class="flex items-start gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                <input type="radio" name="bulk_handling" value="distribute_across" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Distribute across sections</p>
                    ${data.distribution_preview ? `
                    <div class="mt-1 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-500 space-y-0.5">
                        ${(data.distribution_preview||[]).map(p => `<p>${p.section}: ${p.count} student(s)</p>`).join('')}
                    </div>` : ''}
                </div>
            </label>
        </div>`;
    }

    // Send notification checkboxes
    html += `<div class="space-y-2 mb-4">
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors">
            <input type="checkbox" id="bulk-send-student-email" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-xs font-medium text-slate-600">Send confirmation emails to students</span>
        </label>
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors">
            <input type="checkbox" id="bulk-send-teacher-notif" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-xs font-medium text-slate-600">Send notifications to teachers</span>
        </label>
    </div>`;

    html += `<div class="flex items-center justify-between pt-3 border-t border-slate-100">
        <button type="button" onclick="processBulkAssign()"
            class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
            <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> PROCEED
        </button>
        <button type="button" onclick="closeEnrollModal('bulk-assign-modal')"
            class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
    </div>`;

    document.getElementById('bulk-modal-content').innerHTML = html;
}

function processBulkAssign() {
    const method = document.querySelector('input[name="bulk_handling"]:checked')?.value;
    if (!method) { showToast('Please select a method.', 'error'); return; }

    if (method === 'new_section') {
        window.location.href = '{{ route("admin.classes.sections") }}';
        return;
    }
    if (method === 'split_sections') {
        closeEnrollModal('bulk-assign-modal');
        showBalancingPreview();
        return;
    }
    if (method === 'single_section') {
        // pick from dropdown if it exists
        const sel = document.getElementById('bulk-single-section-select');
        if (sel && sel.value) selectedBulkSectionId = parseInt(sel.value);
        if (!selectedBulkSectionId) { showToast('Please select a section.', 'error'); return; }
    }

    fetch('{{ route("admin.enrollment.bulk-assign") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            enrollment_ids:      selectedRegularIds,
            distribution_method: method,
            section_id:          method === 'single_section' ? selectedBulkSectionId : null,
        }),
    })
    .then(r => r.json())
    .then(data => {
        closeEnrollModal('bulk-assign-modal');
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => location.reload(), 1400);
    })
    .catch(() => showToast('Request failed.', 'error'));
}

// ══════════════════════════════════════════════════════════
// STAGE 5 — SECTION BALANCING PREVIEW
// ══════════════════════════════════════════════════════════
function showBalancingPreview() {
    document.getElementById('balance-preview-content').innerHTML =
        '<div class="py-10 text-center text-xs text-slate-400"><iconify-icon icon="solar:loading-bold" width="24" class="animate-spin text-blue-500 mb-2"></iconify-icon><br>Calculating distribution...</div>';
    openEnrollModal('balance-preview-modal');

    fetch('{{ route("admin.enrollment.balancing-preview") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ enrollment_ids: selectedRegularIds }),
    })
    .then(r => r.json())
    .then(data => {
        let html = '';

        // Current status
        html += `<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Current Section Status:</p>
        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 mb-4 text-xs space-y-1.5">
            ${(data.preview||[]).filter(p => !p.section.includes('New')).map(p =>
                `<p class="text-slate-600">
                    <span class="font-semibold">${p.section}:</span> ${p.current}/${p.capacity ?? 30}
                    (${p.capacity - p.current > 0 ? (p.capacity - p.current) + ' slots available' : '0 slots'})
                    ${p.current < 20 ? '<span class="text-amber-600 ml-1">— Below minimum (20)</span>' : ''}
                </p>`
            ).join('')}
        </div>`;

        html += `<div class="text-xs font-semibold text-slate-600 mb-3">Students to Assign: <span class="text-[#0d4c8f]">${data.total_students}</span></div>`;
        html += `<div class="border-t border-slate-100 my-3"></div>`;

        // Proposed distribution
        html += `<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Proposed Distribution:</p>
        <div class="rounded-xl border border-slate-200 overflow-hidden mb-4">
            <table class="w-full text-xs">
                <thead class="bg-slate-50">
                    <tr class="text-slate-500 font-semibold">
                        <th class="px-4 py-2 text-left">Section</th>
                        <th class="px-4 py-2 text-center">Change</th>
                        <th class="px-4 py-2 text-center">Result</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">`;

        const statusColors = { 'FULL': 'text-red-600', 'BALANCED': 'text-green-600', 'BELOW MIN': 'text-amber-600', 'full': 'text-red-600' };

        (data.preview||[]).forEach(p => {
            const isNew = p.section && p.section.includes('New');
            const sc    = statusColors[p.status] || 'text-slate-600';
            const label = p.status === 'FULL' ? 'FULL' : (isNew ? 'New section' : (p.status === 'BALANCED' ? 'Now meets minimum ('+p.new+'/30)' : p.status));
            html += `<tr class="${isNew ? 'bg-blue-50' : 'hover:bg-slate-50'}">
                <td class="px-4 py-2.5 font-medium text-slate-700">
                    ${p.section}${isNew ? '<span class="ml-1.5 text-[10px] font-bold text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded-full">NEW</span>' : ''}
                </td>
                <td class="px-4 py-2.5 text-center text-slate-600">
                    ${p.current} → ${p.new}
                    <span class="text-green-600 font-semibold ml-1">(+${p.added})</span>
                </td>
                <td class="px-4 py-2.5 text-center font-semibold ${sc}">${label}</td>
            </tr>`;
        });

        html += `</tbody></table></div>`;

        if (data.needs_new_section && data.new_section_students > 0) {
            html += `<div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 mb-4 text-xs text-amber-700 flex items-start gap-2">
                <iconify-icon icon="solar:danger-triangle-bold" width="14" class="shrink-0 mt-0.5"></iconify-icon>
                <span>Remaining: <strong>${data.new_section_students} students → New section needed</strong>
                ${data.warning ? '<br>' + data.warning : ''}</span>
            </div>
            <p class="text-xs font-semibold text-slate-600 mb-3">Options:</p>
            <div class="space-y-2 mb-4">
                <label class="flex items-center gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                    <input type="radio" name="balance_option" value="create_new" checked class="text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-medium text-slate-700">Create new section for remaining ${data.new_section_students} students</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 transition-colors">
                    <input type="radio" name="balance_option" value="manual" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-medium text-slate-700">Redistribute differently (manual adjustment)</span>
                </label>
            </div>`;
        }

        html += `<div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <button type="button" onclick="confirmSplitAssign()"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                ${data.needs_new_section ? 'CREATE NEW SECTION' : 'CONFIRM DISTRIBUTION'}
            </button>
            <button type="button" onclick="closeEnrollModal('balance-preview-modal'); openEnrollModal('bulk-assign-modal')"
                class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">ADJUST MANUALLY</button>
        </div>`;

        document.getElementById('balance-preview-content').innerHTML = html;
    })
    .catch(() => {
        document.getElementById('balance-preview-content').innerHTML =
            '<div class="py-8 text-center text-xs text-red-500">Failed to load preview.</div>';
    });
}

function confirmSplitAssign() {
    const balanceOption = document.querySelector('input[name="balance_option"]:checked')?.value;
    if (balanceOption === 'create_new') {
        window.location.href = '{{ route("admin.classes.sections") }}';
        return;
    }

    fetch('{{ route("admin.enrollment.bulk-assign") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ enrollment_ids: selectedRegularIds, distribution_method: 'split_sections' }),
    })
    .then(r => r.json())
    .then(data => {
        closeEnrollModal('balance-preview-modal');
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => location.reload(), 1400);
    })
    .catch(() => showToast('Request failed.', 'error'));
}

// ══════════════════════════════════════════════════════════
// EDIT SECTION MODAL
// ══════════════════════════════════════════════════════════
function openEditModal(enrollmentId, studentName, currentSection, grade) {
    currentEnrollmentId      = enrollmentId;
    currentSelectedSectionId = null;
    document.getElementById('edit-modal-subtitle').textContent  = studentName;
    document.getElementById('edit-current-section').textContent = currentSection || 'Not assigned';
    document.getElementById('edit-reason').value                = '';
    document.getElementById('edit-sections-list').innerHTML     = '<p class="text-xs text-slate-400 py-2">Loading...</p>';
    openEnrollModal('edit-modal');

    fetch('{{ route("admin.enrollment.sections") }}?enrollment_id=' + enrollmentId + '&edit=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const container = document.getElementById('edit-sections-list');
        if (!data.sections || data.sections.length === 0) {
            container.innerHTML = '<p class="text-xs text-slate-400">No available sections for transfer.</p>';
            return;
        }
        container.innerHTML = '';
        data.sections.forEach(s => {
            const name = s.display_name || s.section_name;
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer';
            div.innerHTML = `
                <div>
                    <p class="text-xs font-semibold text-slate-700">${name}</p>
                    <p class="text-xs text-slate-400 mt-0.5">${s.current}/${s.capacity} &bull; ${s.available_slots} slot${s.available_slots !== 1 ? 's' : ''} available &bull; ${s.adviser}</p>
                </div>
                <button type="button" onclick="selectEditSection(${s.id}, '${name.replace(/'/g,"\\'")}', this)"
                    class="edit-sect-btn ml-3 shrink-0 px-2.5 py-1 rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold hover:bg-blue-700 transition-colors">Select</button>`;
            container.appendChild(div);
        });
    });
}

function selectEditSection(id, name, btn) {
    currentSelectedSectionId = id;
    document.querySelectorAll('#edit-sections-list .edit-sect-btn').forEach(b => {
        b.textContent = 'Select';
        b.className   = 'edit-sect-btn ml-3 shrink-0 px-2.5 py-1 rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold hover:bg-blue-700 transition-colors';
    });
    btn.textContent = '✓ Selected';
    btn.className   = 'edit-sect-btn ml-3 shrink-0 px-2.5 py-1 rounded-lg bg-green-600 text-white text-xs font-semibold';
}

function confirmEditSection() {
    const reason = document.getElementById('edit-reason')?.value.trim();
    if (!currentSelectedSectionId) { showToast('Please select a new section.', 'error'); return; }
    if (!reason) { showToast('Please provide a reason.', 'error'); return; }

    fetch('{{ route("admin.enrollment.edit-section") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ enrollment_id: currentEnrollmentId, new_section_id: currentSelectedSectionId, reason }),
    })
    .then(r => r.json())
    .then(data => {
        closeEnrollModal('edit-modal');
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => location.reload(), 1400);
    })
    .catch(() => showToast('Request failed.', 'error'));
}

// ══════════════════════════════════════════════════════════
// DIRECT ENROLL
// ══════════════════════════════════════════════════════════
let deSelectedSectionId   = null;
let deSelectedSectionName = null;

function deResetModal() {
    ['de-first-name','de-middle-name','de-last-name','de-suffix',
     'de-mobile','de-email','de-lrn','de-address',
     'de-guardian-name','de-guardian-contact','de-guardian-email'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    ['de-gender','de-program','de-enroll-type','de-track','de-strand'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    const shsTypeRegular = document.getElementById('de-shs-type-regular');
    if (shsTypeRegular) shsTypeRegular.checked = true;
    document.getElementById('de-grade').innerHTML = '<option value="">— Select Program First —</option>';
    document.getElementById('de-shs-block').classList.add('hidden');
    document.getElementById('de-sections-list').innerHTML = '';
    document.getElementById('de-section-placeholder').classList.remove('hidden');
    document.getElementById('de-sections-loading').classList.add('hidden');
    document.getElementById('de-no-sections').classList.add('hidden');
    document.getElementById('de-section-indicator').classList.add('hidden');
    deSelectedSectionId   = null;
    deSelectedSectionName = null;
    const btn = document.getElementById('de-submit-btn');
    btn.disabled  = false;
    btn.innerHTML = '<iconify-icon icon="solar:user-plus-bold" width="14"></iconify-icon> ENROLL &amp; ASSIGN';
}

const DE_GRADES = {
    'Elementary':        ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'],
    'Junior High School':['Grade 7','Grade 8','Grade 9','Grade 10'],
    'Senior High School':['Grade 11','Grade 12'],
};

function deUpdateGrades(program) {
    const select = document.getElementById('de-grade');
    select.innerHTML = '<option value="">— Select Grade —</option>';
    (DE_GRADES[program] || []).forEach(g => {
        const opt = document.createElement('option');
        opt.value = g; opt.textContent = g;
        select.appendChild(opt);
    });
    document.getElementById('de-shs-block').classList.toggle('hidden', program !== 'Senior High School');
    document.getElementById('de-track').value  = '';
    document.getElementById('de-strand').value = '';
    deFilterStrands('');
    deClearSections();
}

function deFilterStrands(track) {
    document.querySelectorAll('#de-strand option').forEach(opt => {
        if (!opt.value) return;
        opt.hidden = track ? opt.dataset.track !== track : false;
        if (opt.hidden && opt.selected) opt.selected = false;
    });
}

function deClearSections() {
    document.getElementById('de-sections-list').innerHTML = '';
    document.getElementById('de-section-placeholder').classList.remove('hidden');
    document.getElementById('de-sections-loading').classList.add('hidden');
    document.getElementById('de-no-sections').classList.add('hidden');
    document.getElementById('de-section-indicator').classList.add('hidden');
    deSelectedSectionId   = null;
    deSelectedSectionName = null;
}

function deLoadSections() {
    const grade  = document.getElementById('de-grade').value;
    const sy     = document.getElementById('de-school-year').value;
    const track  = document.getElementById('de-track')?.value  || '';
    const strand = document.getElementById('de-strand')?.value || '';

    if (!grade) { deClearSections(); return; }

    document.getElementById('de-section-placeholder').classList.add('hidden');
    document.getElementById('de-sections-loading').classList.remove('hidden');
    document.getElementById('de-sections-list').innerHTML = '';
    document.getElementById('de-no-sections').classList.add('hidden');
    document.getElementById('de-section-indicator').classList.add('hidden');
    deSelectedSectionId = null;

    const params = new URLSearchParams({ grade_level: grade, school_year: sy });
    if (track)  params.append('track',  track);
    if (strand) params.append('strand', strand);

    fetch('{{ route("admin.enrollment.sections") }}?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('de-sections-loading').classList.add('hidden');
        const list = document.getElementById('de-sections-list');

        if (!data.sections || data.sections.length === 0) {
            document.getElementById('de-no-sections').classList.remove('hidden');
            return;
        }

        data.sections.forEach(s => {
            const isFull = s.available_slots === 0;
            const div    = document.createElement('div');
            div.className = 'flex items-start justify-between rounded-xl border px-4 py-3 transition-all ' +
                (isFull ? 'border-slate-200 bg-slate-50 opacity-50 cursor-not-allowed' : 'border-slate-200 hover:border-blue-300 hover:bg-blue-50/60 cursor-pointer');

            div.innerHTML = `
                <div class="flex items-start gap-3 min-w-0">
                    <div class="mt-0.5 shrink-0">
                        ${isFull
                            ? '<span class="text-slate-300"><iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon></span>'
                            : '<input type="radio" name="de_section_radio" class="de-section-radio text-blue-600 focus:ring-blue-500">'}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700">${s.section_name}</p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Capacity: ${s.current}/${s.capacity} &bull;
                            <span class="${isFull ? 'text-red-500 font-semibold' : 'text-green-600 font-semibold'}">
                                ${isFull ? 'FULL' : s.available_slots + ' slot' + (s.available_slots !== 1 ? 's' : '') + ' available'}
                            </span>
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Adviser: ${s.adviser} &bull; Room: ${s.room}</p>
                    </div>
                </div>`;

            if (!isFull) {
                const radio = div.querySelector('input[type=radio]');
                const select = () => {
                    deSelectedSectionId   = s.id;
                    deSelectedSectionName = s.section_name;
                    document.querySelectorAll('#de-sections-list > div').forEach(d => {
                        d.classList.remove('border-blue-400', 'bg-blue-50');
                        d.classList.add('border-slate-200');
                    });
                    div.classList.add('border-blue-400', 'bg-blue-50');
                    div.classList.remove('border-slate-200');
                    document.getElementById('de-section-indicator').classList.remove('hidden');
                    document.getElementById('de-section-name').textContent = s.section_name;
                };
                radio.addEventListener('change', select);
                div.addEventListener('click', e => { if (e.target.tagName !== 'INPUT') { radio.checked = true; select(); } });
            }
            list.appendChild(div);
        });
    })
    .catch(() => {
        document.getElementById('de-sections-loading').classList.add('hidden');
        document.getElementById('de-no-sections').classList.remove('hidden');
    });
}

function submitDirectEnroll() {
    const firstName    = document.getElementById('de-first-name').value.trim();
    const lastName     = document.getElementById('de-last-name').value.trim();
    const gender       = document.getElementById('de-gender').value;
    const dob          = document.getElementById('de-dob').value;
    const guardianName = document.getElementById('de-guardian-name').value.trim();
    const program      = document.getElementById('de-program').value;
    const grade        = document.getElementById('de-grade').value;

    if (!firstName)    { showToast('First name is required.', 'error'); return; }
    if (!lastName)     { showToast('Last name is required.', 'error'); return; }
    if (!gender)       { showToast('Gender is required.', 'error'); return; }
    if (!dob)          { showToast('Date of birth is required.', 'error'); return; }
    if (!guardianName) { showToast('Guardian name is required.', 'error'); return; }
    if (!program)      { showToast('Program level is required.', 'error'); return; }
    if (!grade)        { showToast('Grade level is required.', 'error'); return; }
    if (!deSelectedSectionId) { showToast('Please select a section.', 'error'); return; }

    const btn = document.getElementById('de-submit-btn');
    btn.disabled  = true;
    btn.innerHTML = '<iconify-icon icon="solar:loading-bold" width="14" class="animate-spin"></iconify-icon> Enrolling...';

    fetch('{{ route("admin.enrollment.direct-enroll") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            first_name:          firstName,
            middle_name:         document.getElementById('de-middle-name').value.trim(),
            last_name:           lastName,
            suffix:              document.getElementById('de-suffix').value.trim(),
            gender:              gender,
            date_of_birth:       dob,
            mobile_number:       document.getElementById('de-mobile').value.trim(),
            personal_email:      document.getElementById('de-email').value.trim(),
            lrn:                 document.getElementById('de-lrn').value.trim(),
            home_address:        document.getElementById('de-address').value.trim(),
            guardian_name:       guardianName,
            guardian_relationship: document.getElementById('de-guardian-rel').value,
            guardian_contact:    document.getElementById('de-guardian-contact').value.trim(),
            guardian_email:      document.getElementById('de-guardian-email').value.trim(),
            school_year:         document.getElementById('de-school-year').value,
            enrollment_type:     document.getElementById('de-enroll-type').value,
            program_level:       program,
            grade_level:         grade,
            track:               document.getElementById('de-track')?.value  || null,
            strand:              document.getElementById('de-strand')?.value || null,
            shs_student_type:    document.querySelector('input[name="de_shs_type"]:checked')?.value || 'Regular',
            section_id:          deSelectedSectionId,
        }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled  = false;
        btn.innerHTML = '<iconify-icon icon="solar:user-plus-bold" width="14"></iconify-icon> ENROLL &amp; ASSIGN';
        if (data.success) {
            closeEnrollModal('direct-enroll-modal');
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1400);
        } else {
            showToast(data.message || 'Enrollment failed.', 'error');
        }
    })
    .catch(() => {
        btn.disabled  = false;
        btn.innerHTML = '<iconify-icon icon="solar:user-plus-bold" width="14"></iconify-icon> ENROLL &amp; ASSIGN';
        showToast('Request failed.', 'error');
    });
}

// ── Toast ──────────────────────────────────────────────────
function showToast(msg, type) {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[100] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-xl transition-all duration-300 ' +
        (type === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type === 'success' ? 'check-circle-bold' : 'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3500);
}
</script>
@endpush
@endsection