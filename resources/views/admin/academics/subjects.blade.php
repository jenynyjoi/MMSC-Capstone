@extends('layouts.admin_layout')
@section('title', 'Subjects Management')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4" x-data="{ activeTab: 'subject' }">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Academics"
        subtitle="Subject Management and Allocation"
        school-year="{{ $schoolYear ?? $activeSchoolYear }}"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Tabs --}}
        <div class="flex border-b border-slate-200 dark:border-dark-border overflow-x-auto">
            @foreach([['subject','Subject List'],['allocation','Subject Allocation'],['component','Component List'],['assessment','Assessment List']] as [$tab,$label])
            <button x-on:click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700 dark:hover:text-slate-200'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- ══ TAB 1: SUBJECT MASTER ══ --}}
        <div x-show="activeTab === 'subject'" x-cloak x-transition.opacity>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:notebook-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Subject Master</h2>
                </div>
                <button onclick="openAcadModal('add-subject-modal')"
                    class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/20 px-4 py-2 text-xs font-semibold text-blue-600 dark:text-blue-400 transition-colors">
                    <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                    Add Subject
                </button>
            </div>

            {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <form method="GET" action="{{ route('admin.academic.subjects') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-3">
                        @foreach([
                            ['department','Department',[''=>'All','Mathematics'=>'Mathematics','Science'=>'Science','English'=>'English','Filipino'=>'Filipino','Social Studies'=>'Social Studies','MAPEH'=>'MAPEH','TLE'=>'TLE','Values Education'=>'Values Education']],
                            ['grade_level','Grade Level',array_merge([''=>'All'],array_combine(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'],['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12']))],
                            ['program_level','Program Level',[''=>'All','Elementary'=>'Elementary','Junior High School'=>'Junior High School','Senior High School'=>'Senior High School']],
                        ] as [$name,$label,$opts])
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $label }}</label>
                            <div class="relative">
                                <select name="{{ $name }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    @foreach($opts as $v=>$t)
                                    <option value="{{ $v }}" {{ request($name)===$v?'selected':'' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ route('admin.academic.subjects') }}" class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Clear All</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 px-2 py-1 text-xs focus:outline-none dark:bg-dark-card dark:border-dark-border dark:text-slate-300"><option>10</option><option>25</option></select>
                    <span>Entries</span>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" id="subject-search" placeholder="Search subject.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:1000px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 whitespace-nowrap">Subject Code</th>
                            <th class="px-4 py-3">Subject Name</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3 whitespace-nowrap">Grade Level</th>
                            <th class="px-4 py-3 whitespace-nowrap">Program Level</th>
                            <th class="px-4 py-3 whitespace-nowrap text-center">Hrs/Meeting</th>
                            <th class="px-4 py-3 whitespace-nowrap text-center">Meetings/Wk</th>
                            <th class="px-4 py-3 whitespace-nowrap text-center">Hrs/Wk</th>
                            <th class="px-4 py-3 whitespace-nowrap">Type</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border" id="subject-tbody">
                    @forelse($subjects as $s)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors subject-row" data-name="{{ strtolower($s->subject_name) }}">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400">{{ $s->subject_code }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $s->subject_name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $s->department ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $s->grade_level ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $s->program_level ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-center text-slate-500">{{ $s->hours_per_meeting }}</td>
                        <td class="px-4 py-3 text-xs text-center text-slate-500">{{ $s->meetings_per_week }}</td>
                        <td class="px-4 py-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300">{{ $s->hours_per_week }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $s->subject_type ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button x-on:click="open=!open" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                    Select <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" x-on:click.outside="open=false"
                                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 z-20 mt-1 w-36 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button type="button" x-on:click="open=false" onclick="editSubject({{ $s->id }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon> Edit
                                    </button>
                                    <button type="button" x-on:click="open=false" onclick="deleteSubject({{ $s->id }}, '{{ addslashes($s->subject_name) }}')"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" width="14"></iconify-icon> Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="px-4 py-12 text-center text-xs text-slate-400">No subjects found. <button onclick="openAcadModal('add-subject-modal')" class="text-blue-600 hover:underline">Add the first subject →</button></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $subjects->firstItem() ?? 0 }}–{{ $subjects->lastItem() ?? 0 }} of {{ $subjects->total() }}</p>
                {{ $subjects->links() }}
            </div>
        </div>

        {{-- ══ TAB 2: SUBJECT ALLOCATION ══ --}}
        <div x-show="activeTab === 'allocation'" x-cloak x-transition.opacity>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-6 border-b border-slate-100 dark:border-dark-border">
                <div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:layers-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                        <h2 class="text-base font-bold text-slate-800 dark:text-white">Subject Allocation</h2>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 ml-7">Manage curriculum subjects per grade level and assign teachers to sections</p>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════ --}}
            {{-- CURRICULUM — Subject Allocated per Grade Level            --}}
            {{-- ══════════════════════════════════════════════════════════ --}}
            <div x-data="curriculumApp()" x-init="init()" id="curriculum-root">

                {{-- Section header --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between px-6 py-5 bg-slate-50 dark:bg-white/[0.02] border-b border-slate-100 dark:border-dark-border">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20 shrink-0">
                            <iconify-icon icon="solar:library-bold" width="18" class="text-[#0d4c8f]"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Curriculum Configuration</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Subjects allocated per grade level and strand</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">School Year:</label>
                        <div class="relative">
                            <select x-model="schoolYear" @change="loadGradeStats()"
                                class="appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-7">
                                <option value="2024-2025">SY 2024-2025</option>
                                <option value="2025-2026">SY 2025-2026</option>
                                <option value="2026-2027">SY 2026-2027</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                </div>

                {{-- Grade quick filter buttons --}}
                <div class="px-6 py-6 border-b border-slate-100 dark:border-dark-border">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Quick Filter — Grade Level</p>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="g in grades" :key="g.grade_level">
                            <button @click="selectGrade(g.grade_level)"
                                :class="selectedGrade === g.grade_level
                                    ? 'bg-[#0d4c8f] border-[#0d4c8f] text-white shadow-md scale-105'
                                    : 'bg-white dark:bg-dark-card border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 hover:border-[#0d4c8f] hover:text-[#0d4c8f] hover:shadow-sm'"
                                class="flex flex-col items-center rounded-xl border px-4 py-3 transition-all min-w-[64px] cursor-pointer">
                                <span class="text-xs font-bold leading-none" x-text="g.short"></span>
                                <span class="text-[10px] font-semibold mt-1.5 leading-none"
                                      :class="g.has_config
                                          ? (g.assigned >= g.required && g.required > 0
                                              ? (selectedGrade === g.grade_level ? 'text-green-300' : 'text-green-500')
                                              : (selectedGrade === g.grade_level ? 'text-blue-200' : 'text-blue-500'))
                                          : (selectedGrade === g.grade_level ? 'text-slate-300' : 'text-slate-400')"
                                      x-text="g.has_config ? (g.assigned + '/' + g.required) : '—'"></span>
                            </button>
                        </template>
                    </div>

                    {{-- SHS Strand sub-filter --}}
                    <template x-if="isSHSGrade">
                        <div class="mt-6 pt-5 border-t border-slate-200 dark:border-dark-border">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">
                                Select Strand — <span x-text="selectedGrade"></span>
                            </p>
                            <div class="flex flex-wrap gap-2.5">
                                <template x-for="strand in SHS_STRANDS" :key="strand">
                                    <button @click="selectStrand(strand)"
                                        :class="selectedStrand === strand
                                            ? 'bg-[#0d4c8f] border-[#0d4c8f] text-white shadow-md'
                                            : 'bg-white dark:bg-dark-card border-slate-200 dark:border-dark-border text-slate-600 dark:text-slate-300 hover:border-[#0d4c8f] hover:text-[#0d4c8f] hover:shadow-sm'"
                                        class="inline-flex items-center gap-2 rounded-xl border px-4 py-2.5 text-xs font-bold transition-all cursor-pointer whitespace-nowrap">
                                        <iconify-icon icon="solar:diploma-linear" width="13"></iconify-icon>
                                        <span x-text="selectedGrade.replace('Grade ','G') + ' — ' + strand"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- No grade selected placeholder --}}
                <template x-if="!selectedGrade">
                    <div class="px-6 py-14 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 mb-4">
                            <iconify-icon icon="solar:tuning-2-linear" width="28" class="text-slate-400 dark:text-slate-500"></iconify-icon>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">No Grade Selected</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Select a grade level above to view or manage its curriculum.</p>
                    </div>
                </template>

                {{-- SHS grade selected but no strand yet --}}
                <template x-if="isSHSGrade && !selectedStrand">
                    <div class="px-6 py-14 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 mb-4">
                            <iconify-icon icon="solar:diploma-linear" width="28" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300" x-text="'Select a Strand for ' + selectedGrade"></p>
                        <p class="text-xs text-slate-400 mt-1">Choose a strand from the filter above to view or manage its curriculum.</p>
                    </div>
                </template>

                {{-- Grade detail panel — shows for non-SHS, or SHS + strand --}}
                <template x-if="selectedGrade && (!isSHSGrade || selectedStrand)">
                    <div class="px-6 py-6">

                        {{-- Loading --}}
                        <template x-if="loading">
                            <div class="flex items-center justify-center gap-3 py-10">
                                <iconify-icon icon="svg-spinners:ring-resize" width="20" class="text-[#0d4c8f]"></iconify-icon>
                                <p class="text-xs text-slate-400">Loading curriculum…</p>
                            </div>
                        </template>

                        <template x-if="!loading">
                            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">

                                {{-- Panel header --}}
                                <div class="flex items-center justify-between px-6 py-4 bg-[#0d4c8f]">
                                    <div>
                                        <h3 class="text-sm font-bold text-white uppercase"
                                            x-text="effectiveGradeKey + ' — CURRICULUM'"></h3>
                                        <template x-if="curriculumConfig">
                                            <p class="text-xs text-blue-200 mt-0.5">
                                                Program Level: <span class="font-semibold" x-text="curriculumConfig.program_level"></span>
                                                &nbsp;·&nbsp; SY <span class="font-semibold" x-text="schoolYear"></span>
                                                &nbsp;·&nbsp; Total Required: <span class="font-semibold" x-text="curriculumConfig.total_subjects_required + ' subjects'"></span>
                                            </p>
                                        </template>
                                    </div>
                                    {{-- Non-SHS: single assign button --}}
                                    <template x-if="!isSHSGrade">
                                        <button @click="openAssignModal()"
                                            class="flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-xs font-bold text-white transition-colors">
                                            <iconify-icon icon="solar:add-circle-bold" width="13"></iconify-icon>
                                            ASSIGN SUBJECTS
                                        </button>
                                    </template>
                                    {{-- SHS: two assign buttons per semester --}}
                                    <template x-if="isSHSGrade">
                                        <div class="flex items-center gap-2">
                                            <button @click="openAssignModal('1st Semester')"
                                                class="flex items-center gap-1 rounded-lg bg-white/20 hover:bg-white/30 px-2.5 py-1.5 text-[11px] font-bold text-white transition-colors">
                                                <iconify-icon icon="solar:add-circle-bold" width="12"></iconify-icon>
                                                + 1st Sem
                                            </button>
                                            <button @click="openAssignModal('2nd Semester')"
                                                class="flex items-center gap-1 rounded-lg bg-white/20 hover:bg-white/30 px-2.5 py-1.5 text-[11px] font-bold text-white transition-colors">
                                                <iconify-icon icon="solar:add-circle-bold" width="12"></iconify-icon>
                                                + 2nd Sem
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                {{-- Progress bar --}}
                                <template x-if="curriculumConfig">
                                    <div class="px-6 py-4 bg-blue-50 dark:bg-blue-900/10 border-b border-blue-200 dark:border-blue-900/30">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400"
                                               x-text="'Progress: ' + curriculumConfig.assigned_count + '/' + curriculumConfig.total_subjects_required + ' subjects assigned (' + Math.round((curriculumConfig.assigned_count / Math.max(curriculumConfig.total_subjects_required,1))*100) + '%)'"></p>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold"
                                                  :class="curriculumConfig.assigned_count >= curriculumConfig.total_subjects_required && curriculumConfig.total_subjects_required > 0
                                                      ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                                      : curriculumConfig.assigned_count > 0
                                                          ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                          : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'"
                                                  x-text="curriculumConfig.assigned_count >= curriculumConfig.total_subjects_required && curriculumConfig.total_subjects_required > 0 ? 'Complete' : curriculumConfig.assigned_count > 0 ? 'In Progress' : 'Pending'">
                                            </span>
                                        </div>
                                        <div class="h-2 rounded-full bg-blue-200 dark:bg-blue-800 overflow-hidden">
                                            <div class="h-full rounded-full bg-[#0d4c8f] transition-all duration-500"
                                                 :style="'width:' + Math.min(100, Math.round((curriculumConfig.assigned_count/Math.max(curriculumConfig.total_subjects_required,1))*100)) + '%'"></div>
                                        </div>
                                    </div>
                                </template>

                                {{-- No config state --}}
                                <template x-if="!curriculumConfig">
                                    <div class="px-6 py-12 text-center bg-white dark:bg-dark-card">
                                        <iconify-icon icon="solar:book-2-linear" width="36" class="text-slate-300 dark:text-slate-600 mx-auto mb-2"></iconify-icon>
                                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300" x-text="'No curriculum set up for ' + selectedGrade"></p>
                                        <p class="text-xs text-slate-400 mt-1 mb-4">Define the subjects required for this grade level.</p>
                                        <button @click="openAssignModal()"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-bold text-white transition-colors">
                                            <iconify-icon icon="solar:add-circle-bold" width="13"></iconify-icon>
                                            Set Up Curriculum
                                        </button>
                                    </div>
                                </template>

                                {{-- NON-SHS: single flat table --}}
                                <template x-if="!isSHSGrade && curriculumSubjects.length > 0">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm" style="min-width:540px">
                                            <thead>
                                                <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02]">
                                                    <th class="px-5 py-3.5">Subject Code</th>
                                                    <th class="px-5 py-3.5">Subject Name</th>
                                                    <th class="px-5 py-3.5 text-center whitespace-nowrap">Hours / Week</th>
                                                    <th class="px-5 py-3.5 text-center whitespace-nowrap">Meetings / Week</th>
                                                    <th class="px-5 py-3.5 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                                                <template x-for="cs in curriculumSubjects" :key="cs.id">
                                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                                        <td class="px-5 py-3.5 text-xs font-mono text-slate-400" x-text="cs.subject_code"></td>
                                                        <td class="px-5 py-3.5 text-sm font-medium text-slate-700 dark:text-slate-300">
                                                            <span x-text="cs.subject_name"></span>
                                                            <template x-if="!cs.hours_per_week || !cs.meetings_per_week">
                                                                <span class="ml-2 inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">MISSING</span>
                                                            </template>
                                                        </td>
                                                        <td class="px-5 py-3.5 text-xs text-center text-slate-500" x-text="cs.hours_per_week ? cs.hours_per_week + ' hrs' : '—'"></td>
                                                        <td class="px-5 py-3.5 text-xs text-center text-slate-500" x-text="cs.meetings_per_week ? cs.meetings_per_week + 'x / week' : '—'"></td>
                                                        <td class="px-5 py-3.5 text-center">
                                                            <div class="flex items-center justify-center gap-2">
                                                                <button @click="openEditSubject(cs)" class="rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 text-blue-600 dark:text-blue-400 px-3 py-1.5 text-xs font-semibold transition-colors">Edit</button>
                                                                <button @click="openRemoveSubject(cs)" class="rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-500 dark:text-red-400 px-3 py-1.5 text-xs font-semibold transition-colors">Remove</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                {{-- SHS: semester-split tables --}}
                                <template x-if="isSHSGrade">
                                    <div>
                                        {{-- 1st Semester --}}
                                        <div class="border-b border-slate-200 dark:border-dark-border">
                                            <div class="flex items-center justify-between px-6 py-3.5 bg-blue-50 dark:bg-blue-900/10">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-bold">1st Semester</span>
                                                    <span class="text-xs text-slate-400" x-text="firstSemSubjects.length + ' subject(s)'"></span>
                                                </div>
                                                <button @click="openAssignModal('1st Semester')"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white px-3 py-1.5 text-xs font-bold transition-colors">
                                                    <iconify-icon icon="solar:add-circle-bold" width="12"></iconify-icon> Add Subject
                                                </button>
                                            </div>
                                            <template x-if="firstSemSubjects.length > 0">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs" style="min-width:500px">
                                                        <thead>
                                                            <tr class="text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02] border-b border-slate-100 dark:border-dark-border">
                                                                <th class="px-5 py-3">Subject Code</th>
                                                                <th class="px-5 py-3">Subject Name</th>
                                                                <th class="px-5 py-3 text-center">Hrs / Week</th>
                                                                <th class="px-5 py-3 text-center">Meetings / Week</th>
                                                                <th class="px-5 py-3 text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                                                            <template x-for="cs in firstSemSubjects" :key="cs.id">
                                                                <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors">
                                                                    <td class="px-5 py-3 font-mono text-slate-400 dark:text-slate-500" x-text="cs.subject_code"></td>
                                                                    <td class="px-5 py-3 font-medium text-slate-700 dark:text-slate-300">
                                                                        <span x-text="cs.subject_name"></span>
                                                                        <template x-if="!cs.hours_per_week || !cs.meetings_per_week">
                                                                            <span class="ml-2 inline-flex items-center rounded px-1.5 py-0.5 text-[9px] font-semibold bg-red-100 text-red-600">MISSING</span>
                                                                        </template>
                                                                    </td>
                                                                    <td class="px-5 py-3 text-center text-slate-500" x-text="cs.hours_per_week ? cs.hours_per_week + ' hrs' : '—'"></td>
                                                                    <td class="px-5 py-3 text-center text-slate-500" x-text="cs.meetings_per_week ? cs.meetings_per_week + 'x / week' : '—'"></td>
                                                                    <td class="px-5 py-3 text-center">
                                                                        <div class="flex items-center justify-center gap-2">
                                                                            <button @click="openEditSubject(cs)" class="rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 text-blue-600 dark:text-blue-400 px-2.5 py-1.5 text-[11px] font-semibold transition-colors">Edit</button>
                                                                            <button @click="openRemoveSubject(cs)" class="rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-500 dark:text-red-400 px-2.5 py-1.5 text-[11px] font-semibold transition-colors">Remove</button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </template>
                                            <template x-if="firstSemSubjects.length === 0">
                                                <p class="px-6 py-6 text-xs text-slate-400 text-center bg-white dark:bg-dark-card">No 1st Semester subjects assigned yet.</p>
                                            </template>
                                        </div>

                                        {{-- 2nd Semester --}}
                                        <div>
                                            <div class="flex items-center justify-between px-6 py-3.5 bg-violet-50 dark:bg-violet-900/10">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center rounded-full bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 px-3 py-1 text-xs font-bold">2nd Semester</span>
                                                    <span class="text-xs text-slate-400" x-text="secondSemSubjects.length + ' subject(s)'"></span>
                                                </div>
                                                <button @click="openAssignModal('2nd Semester')"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 hover:bg-violet-700 text-white px-3 py-1.5 text-xs font-bold transition-colors">
                                                    <iconify-icon icon="solar:add-circle-bold" width="12"></iconify-icon> Add Subject
                                                </button>
                                            </div>
                                            <template x-if="secondSemSubjects.length > 0">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-left text-xs" style="min-width:500px">
                                                        <thead>
                                                            <tr class="text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02] border-b border-slate-100 dark:border-dark-border">
                                                                <th class="px-5 py-3">Subject Code</th>
                                                                <th class="px-5 py-3">Subject Name</th>
                                                                <th class="px-5 py-3 text-center">Hrs / Week</th>
                                                                <th class="px-5 py-3 text-center">Meetings / Week</th>
                                                                <th class="px-5 py-3 text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                                                            <template x-for="cs in secondSemSubjects" :key="cs.id">
                                                                <tr class="hover:bg-violet-50/40 dark:hover:bg-violet-900/10 transition-colors">
                                                                    <td class="px-5 py-3 font-mono text-slate-400 dark:text-slate-500" x-text="cs.subject_code"></td>
                                                                    <td class="px-5 py-3 font-medium text-slate-700 dark:text-slate-300">
                                                                        <span x-text="cs.subject_name"></span>
                                                                        <template x-if="!cs.hours_per_week || !cs.meetings_per_week">
                                                                            <span class="ml-2 inline-flex items-center rounded px-1.5 py-0.5 text-[9px] font-semibold bg-red-100 text-red-600">MISSING</span>
                                                                        </template>
                                                                    </td>
                                                                    <td class="px-5 py-3 text-center text-slate-500" x-text="cs.hours_per_week ? cs.hours_per_week + ' hrs' : '—'"></td>
                                                                    <td class="px-5 py-3 text-center text-slate-500" x-text="cs.meetings_per_week ? cs.meetings_per_week + 'x / week' : '—'"></td>
                                                                    <td class="px-5 py-3 text-center">
                                                                        <div class="flex items-center justify-center gap-2">
                                                                            <button @click="openEditSubject(cs)" class="rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 text-blue-600 dark:text-blue-400 px-2.5 py-1.5 text-[11px] font-semibold transition-colors">Edit</button>
                                                                            <button @click="openRemoveSubject(cs)" class="rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-500 dark:text-red-400 px-2.5 py-1.5 text-[11px] font-semibold transition-colors">Remove</button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </template>
                                            <template x-if="secondSemSubjects.length === 0">
                                                <p class="px-6 py-6 text-xs text-slate-400 text-center bg-white dark:bg-dark-card">No 2nd Semester subjects assigned yet.</p>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Empty — has config but no subjects (non-SHS only) --}}
                                <template x-if="!isSHSGrade && curriculumConfig && curriculumSubjects.length === 0">
                                    <div class="px-5 py-6 text-center text-xs text-slate-400 bg-white dark:bg-dark-card">
                                        No subjects assigned yet. Click "Assign Subjects" to add.
                                    </div>
                                </template>

                            </div>
                        </template>
                    </div>
                </template>

                {{-- ══ ASSIGN SUBJECTS MODAL ══ --}}
                <div x-show="showAssignModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div @click.outside="showAssignModal = false"
                        class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10 rounded-t-2xl">
                            <h3 class="text-white text-sm font-bold" x-text="'ASSIGN SUBJECTS — ' + (assignForm.grade_level || selectedGrade || '')"></h3>
                            <button @click="showAssignModal = false" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            {{-- Curriculum Configuration --}}
                            <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 space-y-3">
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Curriculum Configuration</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Program Level</label>
                                        <div class="relative">
                                            <select x-model="assignForm.program_level" @change="onAssignProgramChange()"
                                                class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                                <option value="Elementary">Elementary</option>
                                                <option value="Junior High School">Junior High School (JHS)</option>
                                                <option value="Senior High School">Senior High School (SHS)</option>
                                            </select>
                                            <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Grade Level</label>
                                        <div class="relative">
                                            <select x-model="assignForm.grade_level" @change="onAssignGradeChange()"
                                                class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                                <template x-for="g in gradesForProgram" :key="g">
                                                    <option :value="g" x-text="g"></option>
                                                </template>
                                            </select>
                                            <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">School Year</label>
                                        <input type="text" :value="schoolYear" readonly
                                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-3 py-2 text-xs cursor-not-allowed font-semibold text-slate-700">
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Total Subjects Required <span class="text-red-500">*</span></label>
                                        <input type="number" x-model.number="assignForm.total_required" min="1" max="50"
                                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    {{-- Semester — SHS only --}}
                                    <template x-if="assignForm.program_level === 'Senior High School'">
                                        <div class="flex flex-col gap-1.5 col-span-2">
                                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                                                Semester
                                                <span class="font-normal text-slate-400 ml-1">(for SHS)</span>
                                            </label>
                                            <div class="flex items-center gap-4">
                                                <template x-for="sem in ['1st Semester','2nd Semester','Full Year']" :key="sem">
                                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                                        <input type="radio" :value="sem" x-model="assignForm.semester"
                                                            class="text-[#0d4c8f] focus:ring-blue-500">
                                                        <span class="text-xs text-slate-600 dark:text-slate-300" x-text="sem"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Available Subjects --}}
                            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                                <div class="px-4 py-3 bg-slate-50 dark:bg-white/[0.02] border-b border-slate-200 dark:border-dark-border flex items-center justify-between">
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide" x-text="'Available Subjects for ' + (assignForm.grade_level || selectedGrade)"></p>
                                    <span class="text-[10px] text-slate-400" x-text="availableSubjects.length + ' subject(s) available'"></span>
                                </div>
                                <div class="px-4 py-2.5 border-b border-slate-100 dark:border-dark-border bg-white dark:bg-dark-card">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox"
                                            :checked="availableSubjects.length > 0 && availableSubjects.every(s => assignForm.selected_ids.includes(s.id))"
                                            @change="toggleSelectAll()"
                                            class="rounded border-slate-300 text-[#0d4c8f] focus:ring-blue-500">
                                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Select All</span>
                                    </label>
                                </div>
                                <template x-if="availableSubjects.length === 0">
                                    <div class="px-4 py-6 text-center text-xs text-slate-400">
                                        All available subjects for this grade level are already in the curriculum.
                                    </div>
                                </template>
                                <template x-if="availableSubjects.length > 0">
                                    <div class="overflow-x-auto" style="max-height:260px;overflow-y:auto">
                                        <table class="w-full text-xs">
                                            <thead class="sticky top-0">
                                                <tr class="border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.02] text-slate-500 dark:text-slate-400 font-semibold">
                                                    <th class="px-4 py-2.5 w-10"></th>
                                                    <th class="px-4 py-2.5">Subject Code</th>
                                                    <th class="px-4 py-2.5">Subject Name</th>
                                                    <th class="px-4 py-2.5 text-center">Hours/Week</th>
                                                    <th class="px-4 py-2.5 text-center">Meetings/Week</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-dark-border bg-white dark:bg-dark-card">
                                                <template x-for="s in availableSubjects" :key="s.id">
                                                    <tr class="hover:bg-blue-50/60 dark:hover:bg-blue-900/10 transition-colors cursor-pointer"
                                                        @click="toggleSubject(s.id)">
                                                        <td class="px-4 py-2.5">
                                                            <input type="checkbox" :checked="assignForm.selected_ids.includes(s.id)"
                                                                @click.stop="toggleSubject(s.id)"
                                                                class="rounded border-slate-300 text-[#0d4c8f] focus:ring-blue-500">
                                                        </td>
                                                        <td class="px-4 py-2.5 font-mono text-slate-400" x-text="s.subject_code"></td>
                                                        <td class="px-4 py-2.5 font-medium text-slate-700 dark:text-slate-300" x-text="s.subject_name"></td>
                                                        <td class="px-4 py-2.5 text-center text-slate-500" x-text="s.hours_per_week ? s.hours_per_week + 'h' : '—'"></td>
                                                        <td class="px-4 py-2.5 text-center text-slate-500" x-text="s.meetings_per_week ? s.meetings_per_week + 'x/wk' : '—'"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>
                            </div>
                            <p class="text-[10px] text-slate-400" x-text="assignForm.selected_ids.length + ' subject(s) selected'"></p>
                        </div>
                        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                            <button @click="saveAssign()" :disabled="assignLoading || assignForm.selected_ids.length === 0"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 disabled:opacity-50 text-white text-xs font-bold transition-colors">
                                <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon>
                                <span x-text="assignLoading ? 'Saving…' : 'ASSIGN SUBJECTS'"></span>
                            </button>
                            <button @click="showAssignModal = false"
                                class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">CANCEL</button>
                        </div>
                    </div>
                </div>

                {{-- ══ EDIT CURRICULUM SUBJECT MODAL ══ --}}
                <div x-show="showEditModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div @click.outside="showEditModal = false"
                        class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border w-full max-w-md max-h-[90vh] overflow-y-auto">
                        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10 rounded-t-2xl">
                            <h3 class="text-white text-sm font-bold" x-text="'EDIT SUBJECT IN CURRICULUM — ' + (selectedGrade || '')"></h3>
                            <button @click="showEditModal = false" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-3 text-xs">
                                <p class="font-bold text-slate-700 dark:text-slate-200" x-text="editForm.subject_name + ' (' + editForm.subject_code + ')'"></p>
                                <p class="text-slate-400 mt-0.5" x-text="'Grade Level: ' + selectedGrade"></p>
                            </div>
                            <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 space-y-3">
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Subject Details</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Hours per Week</label>
                                        <input type="number" x-model.number="editForm.hours_per_week" min="0" max="40" step="0.5"
                                            @input="calcHoursPerMeeting()"
                                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Meetings per Week</label>
                                        <input type="number" x-model.number="editForm.meetings_per_week" min="1" max="10"
                                            @input="calcHoursPerMeeting()"
                                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="flex flex-col gap-1.5 col-span-2">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Hours per Meeting <span class="font-normal text-slate-400">(calculated)</span></label>
                                        <input type="text" :value="editForm.hours_per_meeting + ' hours'" readonly
                                            class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-3 py-2 text-sm cursor-not-allowed font-semibold text-slate-700">
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Subject Type</label>
                                    <div class="relative">
                                        <select x-model="editForm.subject_type"
                                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                            <option value="Core">Core</option>
                                            <option value="Elective">Elective</option>
                                            <option value="Specialized">Specialized</option>
                                            <option value="Applied">Applied</option>
                                            <option value="Not Applicable">Not Applicable</option>
                                        </select>
                                        <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Is Required</label>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" :checked="editForm.is_required === true" @change="editForm.is_required = true" class="text-[#0d4c8f] focus:ring-blue-500">
                                            <span class="text-xs text-slate-600 dark:text-slate-300">Yes</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" :checked="editForm.is_required === false" @change="editForm.is_required = false" class="text-[#0d4c8f] focus:ring-blue-500">
                                            <span class="text-xs text-slate-600 dark:text-slate-300">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 space-y-3">
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Semester <span class="font-normal normal-case text-slate-400">(for SHS only)</span></p>
                                <div class="flex items-center gap-4 flex-wrap">
                                    <template x-for="sem in ['1st Semester','2nd Semester','Full Year']" :key="sem">
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" :value="sem" x-model="editForm.semester" class="text-[#0d4c8f] focus:ring-blue-500">
                                            <span class="text-xs text-slate-600 dark:text-slate-300" x-text="sem"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                            <button @click="saveEdit()" :disabled="editLoading"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 disabled:opacity-50 text-white text-xs font-bold transition-colors">
                                <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon>
                                <span x-text="editLoading ? 'Saving…' : 'SAVE CHANGES'"></span>
                            </button>
                            <button @click="showEditModal = false"
                                class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">CANCEL</button>
                        </div>
                    </div>
                </div>

                {{-- ══ REMOVE CURRICULUM SUBJECT MODAL ══ --}}
                <div x-show="showRemoveModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div @click.outside="showRemoveModal = false"
                        class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border w-full max-w-sm">
                        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between rounded-t-2xl">
                            <h3 class="text-white text-sm font-bold">REMOVE SUBJECT FROM CURRICULUM</h3>
                            <button @click="showRemoveModal = false" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
                        </div>
                        <div class="px-6 py-5">
                            <div class="rounded-xl border border-amber-200 dark:border-amber-900/30 bg-amber-50 dark:bg-amber-900/10 p-4 space-y-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <iconify-icon icon="solar:danger-triangle-bold" width="16" class="text-amber-500 flex-shrink-0"></iconify-icon>
                                    <span class="font-bold text-amber-700 dark:text-amber-400">WARNING!</span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                    Are you sure you want to remove
                                    <strong class="text-slate-800 dark:text-white" x-text="removeTarget?.subject_name"></strong>
                                    from <strong x-text="(selectedGrade || '') + ' curriculum'"></strong>?
                                </p>
                                <template x-if="sectionCount > 0">
                                    <div class="text-xs text-amber-700 dark:text-amber-300 space-y-0.5 pt-1">
                                        <p x-text="'This subject is currently assigned to ' + sectionCount + ' section(s).'"></p>
                                        <p>Removing it will affect existing class allocations.</p>
                                    </div>
                                </template>
                                <p class="text-xs font-semibold text-red-600 dark:text-red-400 pt-1">This action cannot be undone.</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                            <button @click="confirmRemove()" :disabled="removeLoading"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white text-xs font-bold transition-colors">
                                <iconify-icon icon="solar:trash-bin-trash-bold" width="14"></iconify-icon>
                                <span x-text="removeLoading ? 'Removing…' : 'CONFIRM REMOVE'"></span>
                            </button>
                            <button @click="showRemoveModal = false"
                                class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">CANCEL</button>
                        </div>
                    </div>
                </div>

            </div>{{-- END CURRICULUM --}}

            {{-- ══ ASSIGN TEACHER TABLE ══ --}}
            <div x-data="teacherAssignApp()" x-init="init()" id="assign-teacher-section">

                {{-- Label --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between px-6 py-5 bg-slate-50 dark:bg-white/[0.02] border-t-4 border-t-slate-200 dark:border-t-dark-border border-b border-slate-100 dark:border-dark-border">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-green-50 dark:bg-green-900/20 shrink-0">
                            <iconify-icon icon="solar:user-check-rounded-bold" width="18" class="text-green-600 dark:text-green-400"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Assign Teacher</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Assign teachers to subjects per section</p>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                    <form method="GET" action="{{ route('admin.academic.subjects') }}#assign-teacher-section">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">

                            {{-- School Year --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-slate-500">School Year</label>
                                <div class="relative">
                                    <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                        @foreach(['2026-2027'=>'SY 2026-2027','2025-2026'=>'SY 2025-2026'] as $v=>$t)
                                        <option value="{{ $v }}" {{ ($schoolYear??'2026-2027')===$v?'selected':'' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                </div>
                            </div>

                            {{-- Grade Level --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-slate-500">Grade Level</label>
                                <div class="relative">
                                    <select name="alloc_grade" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                        <option value="">All Grades</option>
                                        @foreach($allocGradeLevels as $gl)
                                        <option value="{{ $gl }}" {{ request('alloc_grade')===$gl?'selected':'' }}>{{ $gl }}</option>
                                        @endforeach
                                    </select>
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                </div>
                            </div>

                            {{-- Search --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-slate-500">Search</label>
                                <div class="relative">
                                    <input type="text" name="alloc_search" value="{{ request('alloc_search') }}"
                                        placeholder="Section name or grade…"
                                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 pl-8 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400">
                                    <iconify-icon icon="solar:magnifer-linear" width="13" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                </div>
                            </div>

                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                                <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                            </button>
                            <a href="{{ route('admin.academic.subjects') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Clear</a>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" style="min-width:700px">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-5 py-3.5 whitespace-nowrap w-10"></th>
                                <th class="px-5 py-3.5 whitespace-nowrap">School Year</th>
                                <th class="px-5 py-3.5 whitespace-nowrap">Grade and Section</th>
                                <th class="px-5 py-3.5 whitespace-nowrap">Program Level</th>
                                <th class="px-5 py-3.5 whitespace-nowrap">Teacher Assignment Progress</th>
                                <th class="px-5 py-3.5 whitespace-nowrap text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($allocations as $alloc)
                        @php
                            $allocd  = $allocCounts[$alloc->id]  ?? 0;
                            $taught  = $teacherCounts[$alloc->id] ?? 0;
                            $tpct    = $allocd > 0 ? min(100, round(($taught / $allocd) * 100)) : 0;
                            $tStatus = $allocd === 0 ? 'none' : ($taught >= $allocd ? 'complete' : ($taught > 0 ? 'partial' : 'pending'));
                        @endphp

                        {{-- Parent row --}}
                        <tr class="border-b border-slate-100 dark:border-dark-border hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors cursor-pointer select-none"
                            @click="toggleRow({{ $alloc->id }}, '{{ $schoolYear }}')">
                            <td class="px-5 py-4 text-center">
                                <iconify-icon icon="solar:alt-arrow-right-linear" width="14"
                                    class="text-slate-400 transition-transform duration-200"
                                    :style="expandedRow === {{ $alloc->id }} ? 'transform:rotate(90deg)' : ''"></iconify-icon>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $schoolYear }}</td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $alloc->display_name }}</span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $alloc->program_level ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden" style="min-width:90px">
                                        @if($allocd > 0)
                                        <div class="h-full rounded-full transition-all {{ $tStatus==='complete' ? 'bg-green-500' : 'bg-blue-500' }}" style="width:{{ $tpct }}%"></div>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium whitespace-nowrap
                                        {{ $tStatus==='complete' ? 'text-green-600' : ($tStatus==='partial' ? 'text-blue-600' : 'text-slate-400') }}">
                                        {{ $taught }}/{{ $allocd }} teachers assigned
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $tStatus==='complete' ? 'bg-green-100 text-green-700' : ($tStatus==='partial' ? 'bg-blue-100 text-blue-700' : ($tStatus==='pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500')) }}">
                                    {{ $tStatus==='complete' ? 'Complete' : ($tStatus==='partial' ? 'Partial' : ($tStatus==='pending' ? 'Pending' : 'No Subjects')) }}
                                </span>
                            </td>
                        </tr>

                        {{-- Expanded subjects row --}}
                        <tr x-show="expandedRow === {{ $alloc->id }}" style="display:none"
                            class="border-b border-slate-200 dark:border-dark-border bg-blue-50/30 dark:bg-blue-900/5">
                            <td colspan="6" class="p-0">

                                {{-- Loading state --}}
                                <template x-if="loadingRow === {{ $alloc->id }}">
                                    <div class="flex items-center justify-center gap-2 py-8 text-xs text-slate-400">
                                        <iconify-icon icon="svg-spinners:ring-resize" width="16"></iconify-icon>
                                        Loading subjects…
                                    </div>
                                </template>

                                {{-- Subjects table --}}
                                <template x-if="subjectsMap[{{ $alloc->id }}] && loadingRow !== {{ $alloc->id }}">
                                    <div class="px-6 py-5">
                                        <template x-if="subjectsMap[{{ $alloc->id }}].length === 0">
                                            <p class="text-xs text-slate-400 text-center py-6">No subjects allocated to this section yet.</p>
                                        </template>
                                        <template x-if="subjectsMap[{{ $alloc->id }}].length > 0">
                                            <table class="w-full text-xs rounded-lg overflow-hidden" style="min-width:580px">
                                                <thead>
                                                    <tr class="bg-slate-100 dark:bg-white/[0.04] text-slate-500 dark:text-slate-400 font-semibold">
                                                        <th class="px-4 py-3 text-left">Subject Code</th>
                                                        <th class="px-4 py-3 text-left">Subject Name</th>
                                                        <th class="px-4 py-3 text-center">Hrs / Week</th>
                                                        <th class="px-4 py-3 text-left">Assigned Teacher</th>
                                                        <th class="px-4 py-3 text-center w-36">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 dark:divide-white/[0.04] bg-white dark:bg-dark-card">
                                                    <template x-for="subj in subjectsMap[{{ $alloc->id }}]" :key="subj.id">
                                                        <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors">
                                                            <td class="px-4 py-3 font-mono font-semibold text-slate-700 dark:text-slate-200" x-text="subj.subject_code"></td>
                                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300" x-text="subj.subject_name"></td>
                                                            <td class="px-4 py-3 text-center text-slate-500" x-text="subj.hours_per_week || '—'"></td>
                                                            <td class="px-4 py-3">
                                                                {{-- View mode --}}
                                                                <template x-if="!editingTeacher[subj.id]">
                                                                    <span :class="subj.teacher_name ? 'text-slate-700 dark:text-slate-200 font-medium' : 'text-slate-400 italic'"
                                                                          x-text="subj.teacher_name || 'Not assigned'"></span>
                                                                </template>
                                                                {{-- Edit mode: custom teacher picker --}}
                                                                <template x-if="editingTeacher[subj.id]">
                                                                    <div class="space-y-1.5 min-w-[220px]">
                                                                        {{-- Selected chip --}}
                                                                        <div class="flex items-center gap-1.5 text-[10px]">
                                                                            <template x-if="editingTeacher[subj.id].teacher_id">
                                                                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 px-2 py-0.5 font-semibold">
                                                                                    <iconify-icon icon="solar:user-check-rounded-bold" width="10"></iconify-icon>
                                                                                    <span x-text="selectedTeacherName(subj.id)"></span>
                                                                                </span>
                                                                            </template>
                                                                            <template x-if="!editingTeacher[subj.id].teacher_id">
                                                                                <span class="text-slate-400 italic">No teacher selected</span>
                                                                            </template>
                                                                        </div>
                                                                        {{-- Search input --}}
                                                                        <div class="relative">
                                                                            <input type="text"
                                                                                :value="teacherSearch[subj.id] || ''"
                                                                                @input="teacherSearch = {...teacherSearch, [subj.id]: $event.target.value}"
                                                                                @click.stop
                                                                                placeholder="Search teacher…"
                                                                                class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 pl-6 pr-2.5 py-1.5 text-[10px] focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder:text-slate-400">
                                                                            <iconify-icon icon="solar:magnifer-linear" width="10" class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                                                        </div>
                                                                        {{-- Teacher list --}}
                                                                        <div class="rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 overflow-hidden" style="max-height:176px;overflow-y:auto">
                                                                            {{-- Loading --}}
                                                                            <template x-if="loadingTeachers === subj.id">
                                                                                <div class="flex items-center justify-center gap-1.5 py-4 text-[10px] text-slate-400">
                                                                                    <iconify-icon icon="svg-spinners:ring-resize" width="12"></iconify-icon> Loading…
                                                                                </div>
                                                                            </template>
                                                                            {{-- Unassign option --}}
                                                                            <template x-if="loadingTeachers !== subj.id">
                                                                                <div>
                                                                                    <div @click.stop="selectTeacher(subj.id, '', null)"
                                                                                        class="flex items-center gap-1.5 px-3 py-2 cursor-pointer text-[10px] border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors"
                                                                                        :class="!editingTeacher[subj.id].teacher_id ? 'bg-slate-50 dark:bg-white/5 font-semibold text-slate-600 dark:text-slate-300' : 'text-slate-400'">
                                                                                        <iconify-icon icon="solar:user-cross-rounded-linear" width="11"></iconify-icon>
                                                                                        Unassign teacher
                                                                                    </div>
                                                                                    <template x-if="filteredTeachers(subj.id).length === 0 && !teacherSearch[subj.id]">
                                                                                        <p class="px-3 py-3 text-[10px] text-slate-400 text-center">No available teachers for this subject.</p>
                                                                                    </template>
                                                                                    <template x-if="filteredTeachers(subj.id).length === 0 && teacherSearch[subj.id]">
                                                                                        <p class="px-3 py-3 text-[10px] text-slate-400 text-center">No results match your search.</p>
                                                                                    </template>
                                                                                    <template x-for="t in filteredTeachers(subj.id)" :key="t.id">
                                                                                        <div @click.stop="selectTeacher(subj.id, String(t.id), t.name)"
                                                                                            class="flex items-center justify-between gap-2 px-3 py-2 cursor-pointer text-[10px] hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors border-b border-slate-50 dark:border-slate-700/50 last:border-0"
                                                                                            :class="editingTeacher[subj.id].teacher_id === String(t.id) ? 'bg-blue-50 dark:bg-blue-900/20' : ''">
                                                                                            <div class="min-w-0">
                                                                                                <p class="font-medium text-slate-700 dark:text-slate-200 truncate" x-text="t.name"></p>
                                                                                                <div class="flex items-center gap-1 mt-0.5 flex-wrap">
                                                                                                    <span class="text-slate-400" x-text="t.available_hours + 'h free'"></span>
                                                                                                    <template x-if="t.has_warnings">
                                                                                                        <span class="inline-flex items-center gap-0.5 text-amber-600 dark:text-amber-400">
                                                                                                            <iconify-icon icon="solar:danger-triangle-linear" width="9"></iconify-icon>
                                                                                                            <span x-text="t.warnings[0]" class="truncate" style="max-width:140px"></span>
                                                                                                        </span>
                                                                                                    </template>
                                                                                                </div>
                                                                                            </div>
                                                                                            <span class="shrink-0 rounded-full px-1.5 py-0.5 font-semibold text-[9px]"
                                                                                                :class="t.status === 'available' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400'"
                                                                                                x-text="t.status === 'available' ? 'Available' : 'Near Full'"></span>
                                                                                        </div>
                                                                                    </template>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </td>
                                                            <td class="px-4 py-3 text-center align-top">
                                                                {{-- View mode: Assign / Change button --}}
                                                                <template x-if="!editingTeacher[subj.id]">
                                                                    <button @click.stop="startEdit(subj, {{ $alloc->id }}, '{{ $schoolYear }}')"
                                                                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[10px] font-semibold transition-colors"
                                                                        :class="subj.teacher_name ? 'border border-slate-300 text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-white/5' : 'bg-[#0d4c8f] text-white hover:bg-blue-700'">
                                                                        <iconify-icon :icon="subj.teacher_name ? 'solar:pen-linear' : 'solar:user-plus-rounded-linear'" width="11"></iconify-icon>
                                                                        <span x-text="subj.teacher_name ? 'Change' : 'Assign'"></span>
                                                                    </button>
                                                                </template>
                                                                {{-- Edit mode: Save / Cancel --}}
                                                                <template x-if="editingTeacher[subj.id]">
                                                                    <div class="flex flex-col items-center gap-1.5 pt-7">
                                                                        <button @click.stop="saveTeacher(subj, {{ $alloc->id }})"
                                                                            :disabled="savingId === subj.id"
                                                                            class="inline-flex items-center gap-1 rounded-lg bg-green-600 hover:bg-green-700 disabled:opacity-60 px-2.5 py-1 text-[10px] font-semibold text-white transition-colors w-full justify-center">
                                                                            <iconify-icon x-show="savingId !== subj.id" icon="solar:check-circle-linear" width="11"></iconify-icon>
                                                                            <iconify-icon x-show="savingId === subj.id" icon="svg-spinners:ring-resize" width="11"></iconify-icon>
                                                                            Save
                                                                        </button>
                                                                        <button @click.stop="cancelEdit(subj.id)"
                                                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-white/5 px-2.5 py-1 text-[10px] font-medium text-slate-600 dark:text-slate-300 transition-colors w-full justify-center">
                                                                            Cancel
                                                                        </button>
                                                                    </div>
                                                                </template>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </template>
                                    </div>
                                </template>

                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-xs text-slate-400">
                                No sections found for SY {{ $schoolYear }}. Create sections in Section Management first.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                    <p class="text-xs text-slate-400">Showing {{ $allocations->firstItem() ?? 0 }}–{{ $allocations->lastItem() ?? 0 }} of {{ $allocations->total() }}</p>
                    {{ $allocations->links() }}
                </div>

            </div>{{-- END ASSIGN TEACHER --}}
        </div>

        {{-- ══ TAB 3: COMPONENT LIST ══ --}}
        <div x-show="activeTab === 'component'" x-cloak x-transition.opacity>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:notebook-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Component List</h2>
                </div>
                <button onclick="openAcadModal('add-component-modal')"
                    class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100 px-4 py-2 text-xs font-semibold text-blue-600 transition-colors">
                    <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                    Add Component
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:640px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3">Component Code</th>
                            <th class="px-4 py-3">Component Name</th>
                            <th class="px-4 py-3 text-center">Grade Percentage</th>
                            <th class="px-4 py-3">Grade Level</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @forelse($components as $c)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400">{{ $c->component_code }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $c->component_name }}</td>
                        <td class="px-4 py-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300">{{ $c->grade_percentage }}%</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $c->grade_level ?? 'All Levels' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button x-on:click="open=!open" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                    Select <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" x-on:click.outside="open=false"
                                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 z-20 mt-1 w-36 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button type="button" x-on:click="open=false" onclick="editComponent({{ $c->id }}, {{ json_encode($c) }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon> Edit
                                    </button>
                                    <button type="button" x-on:click="open=false" onclick="deleteComponent({{ $c->id }}, '{{ addslashes($c->component_name) }}')"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" width="14"></iconify-icon> Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-xs text-slate-400">No components found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $components->firstItem() ?? 0 }}–{{ $components->lastItem() ?? 0 }} of {{ $components->total() }}</p>
                {{ $components->links() }}
            </div>
        </div>

        {{-- ══ TAB 4: ASSESSMENT LIST ══ --}}
        <div x-show="activeTab === 'assessment'" x-cloak x-transition.opacity>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:diploma-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Assessment List</h2>
                </div>
                <button onclick="openAcadModal('add-assessment-modal')"
                    class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100 px-4 py-2 text-xs font-semibold text-blue-600 transition-colors">
                    <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                    Add Assessment
                </button>
            </div>

            {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <form method="GET" action="{{ route('admin.academic.subjects') }}">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Class (Allocation)</label>
                            <div class="relative">
                                <select name="assess_allocation" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All Classes</option>
                                    @foreach($allAllocations as $a)
                                    <option value="{{ $a->id }}" {{ request('assess_allocation')==$a->id?'selected':'' }}>
                                        {{ $a->section?->display_name ?? '—' }}: {{ $a->subject_name }}
                                    </option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Component</label>
                            <div class="relative">
                                <select name="assess_component" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    @foreach($allComponents as $c)
                                    <option value="{{ $c->id }}" {{ request('assess_component')==$c->id?'selected':'' }}>{{ $c->component_name }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Quarter</label>
                            <div class="relative">
                                <select name="assess_quarter" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    @foreach(['First','Second','Third','Fourth'] as $q)
                                    <option value="{{ $q }}" {{ request('assess_quarter')===$q?'selected':'' }}>{{ $q }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ route('admin.academic.subjects') }}" class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Clear All</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:800px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3">Class</th>
                            <th class="px-4 py-3">Component</th>
                            <th class="px-4 py-3">Quarter</th>
                            <th class="px-4 py-3">Assessment Name</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Max Score</th>
                            <th class="px-4 py-3 whitespace-nowrap">Date</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @forelse($assessments as $a)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $a->allocation?->section?->display_name ?? '—' }}:
                            <span class="text-slate-600 dark:text-slate-300">{{ $a->allocation?->subject_name }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $a->component?->component_name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $a->quarter }}</td>
                        <td class="px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300">{{ $a->assessment_name }}</td>
                        <td class="px-4 py-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300">{{ $a->max_score }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $a->assessment_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button x-on:click="open=!open" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                    Select <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" x-on:click.outside="open=false"
                                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 z-20 mt-1 w-36 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button type="button" x-on:click="open=false" onclick="editAssessment({{ $a->id }}, {{ json_encode($a) }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon> Edit
                                    </button>
                                    <button type="button" x-on:click="open=false" onclick="deleteAssessment({{ $a->id }}, '{{ addslashes($a->assessment_name) }}')"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" width="14"></iconify-icon> Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-xs text-slate-400">No assessments found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $assessments->firstItem() ?? 0 }}–{{ $assessments->lastItem() ?? 0 }} of {{ $assessments->total() }}</p>
                {{ $assessments->links() }}
            </div>
        </div>

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══ ADD SUBJECT MODAL ══ --}}
<div id="add-subject-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAcadModal('add-subject-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="subject-modal-title" class="text-white text-sm font-bold">ADD SUBJECT</h3>
            <button onclick="closeAcadModal('add-subject-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <form id="subject-form" class="px-6 py-5 space-y-4">
            <input type="hidden" id="subject-id" value="">

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Subject Code <span class="text-red-500">*</span></label>
                        <span id="sub-code-auto-badge" class="text-[10px] font-semibold text-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded px-1.5 py-0.5">AUTO</span>
                    </div>
                    <input type="text" id="sub-code" name="subject_code" placeholder="Auto-generated"
                        oninput="subCodeManualEdit()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-[10px] text-slate-400">Edit to override auto-generation.</p>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Subject Name <span class="text-red-500">*</span></label>
                    <input type="text" id="sub-name" name="subject_name" placeholder="e.g. Mathematics 7"
                        oninput="autoGenSubjectCode()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Description</label>
                <textarea id="sub-desc" name="description" rows="2"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Optional description..."></textarea>
            </div>

            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Classification</p>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['sub-dept','department','Department',['Mathematics','Science','English','Filipino','Social Studies','MAPEH','TLE','Values Education','Other']],
                    ['sub-grade','grade_level','Grade Level',['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12']],
                    ['sub-program','program_level','Program Level',['Elementary','Junior High School','Senior High School']],
                    ['sub-type','subject_type','Subject Type',['Core','Specialized','Applied','Elective','Not Applicable']],
                ] as [$eid,$ename,$elabel,$eopts])
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $elabel }}</label>
                    <div class="relative">
                        <select id="{{ $eid }}" name="{{ $ename }}"
                            {!! $eid === 'sub-grade'   ? 'onchange="onGradeChange()"'   : '' !!}
                            {!! $eid === 'sub-program' ? 'onchange="onProgramChange()"' : '' !!}
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select —</option>
                            @foreach($eopts as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- SHS Track & Strand (shown only for Grade 11 / Grade 12) --}}
            <div id="shs-fields" class="grid grid-cols-2 gap-4" style="display:none">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Track</label>
                    <div class="relative">
                        <select id="sub-track" name="track" onchange="onTrackChange()"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select Track —</option>
                            <option value="Academic">Academic Track</option>
                            <option value="TVL">TVL Track</option>
                            <option value="Sports">Sports Track</option>
                            <option value="Arts and Design">Arts and Design Track</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Strand</label>
                    <div class="relative">
                        <select id="sub-strand" name="strand" onchange="autoGenSubjectCode()"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select Strand —</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
            </div>

            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Hour Requirements</p>
            <div class="grid grid-cols-3 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Hours per Meeting <span class="text-red-500">*</span></label>
                    <input type="number" id="sub-hpm" name="hours_per_meeting" min="0.5" max="8" step="0.5" value="1"
                        oninput="calcHoursPerWeek()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Meetings per Week <span class="text-red-500">*</span></label>
                    <input type="number" id="sub-mpw" name="meetings_per_week" min="1" max="10" value="1"
                        oninput="calcHoursPerWeek()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Hours per Week (auto)</label>
                    <input type="text" id="sub-hpw" readonly value="1"
                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-3 py-2.5 text-sm cursor-not-allowed font-semibold text-slate-700">
                </div>
            </div>

            {{-- SHS Semester --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" id="sub-has-sem" onchange="toggleSemester(this.checked)" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">This subject has semester (SHS only)</span>
                </label>
                <div id="sem-field" class="hidden flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Default Semester</label>
                    <div class="relative">
                        <select id="sub-sem" name="default_semester" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Full Year">Full Year</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="submitSubject()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon>
                    <span id="subject-btn-label">SAVE SUBJECT</span>
                </button>
                <button type="button" onclick="closeAcadModal('add-subject-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ ALLOCATION DETAIL MODAL (Stage 2.2 - 2.8) ══ --}}
<div id="allocation-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAcadModal('allocation-detail-modal')"></div>
    <div class="relative w-full max-w-3xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="alloc-modal-title" class="text-white text-sm font-bold">SUBJECT ALLOCATION</h3>
            <button onclick="closeAcadModal('allocation-detail-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-5">

            {{-- Section Info Card --}}
            <div class="grid grid-cols-3 gap-3 rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 p-4 text-xs">
                <div><p class="text-slate-400 mb-0.5">Section</p><p id="alloc-section-name" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">School Year</p><p id="alloc-sy" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Program Level</p><p id="alloc-program" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Grade Level</p><p id="alloc-grade" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Adviser</p><p id="alloc-adviser" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Room</p><p id="alloc-room" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
            </div>

            {{-- Progress --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-4 text-xs">
                        <div class="flex items-center gap-1.5">
                            <label class="text-slate-500">Total Subjects Required:</label>
                            <input type="number" id="alloc-required-input" min="1" max="30" value="0"
                                class="w-16 rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="saveRequiredCount()" class="px-3 py-1 rounded-lg bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold transition-colors">Set</button>
                        </div>
                        <div class="text-slate-500">
                            Allocated: <strong id="alloc-allocated-count" class="text-slate-700 dark:text-slate-200">0</strong> /
                            Remaining: <strong id="alloc-remaining" class="text-orange-600">0</strong>
                        </div>
                    </div>
                    <span id="alloc-status-badge" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                    <div id="alloc-progress-bar" class="h-full rounded-full bg-blue-500 transition-all duration-500" style="width:0%"></div>
                </div>
            </div>

            {{-- Assign button --}}
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Assigned Subjects</h3>
                <button id="assign-subject-btn" onclick="openAssignSubjectModal()"
                    class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100 px-4 py-2 text-xs font-semibold text-blue-600 transition-colors">
                    <iconify-icon icon="solar:add-circle-linear" width="14"></iconify-icon>
                    + Assign Subject
                </button>
            </div>

            {{-- Assigned subjects table --}}
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-dark-border">
                <table class="w-full text-sm" style="min-width:560px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/40">
                            <th class="px-4 py-3">Subject Code</th>
                            <th class="px-4 py-3">Subject Name</th>
                            <th class="px-4 py-3 text-center">Hrs/Wk</th>
                            <th class="px-4 py-3">Teacher</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="assigned-subjects-tbody" class="divide-y divide-slate-100 dark:divide-dark-border">
                        <tr><td colspan="5" class="px-4 py-6 text-center text-xs text-slate-400">Loading...</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-dark-border">
                <button onclick="closeAcadModal('allocation-detail-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CLOSE</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ ASSIGN SUBJECT MODAL ══ --}}
<div id="assign-subject-modal" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAcadModal('assign-subject-modal')"></div>
    <div class="relative w-full max-w-xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">ASSIGN SUBJECT TO SECTION</h3>
            <button onclick="closeAcadModal('assign-subject-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            {{-- Section info (read-only) --}}
            <div class="grid grid-cols-3 gap-3 rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 p-3 text-xs">
                <div><p class="text-slate-400 mb-0.5">Section</p><p id="assign-section-name" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Grade</p><p id="assign-grade" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Remaining Slots</p><p id="assign-remaining" class="font-semibold text-orange-600">—</p></div>
            </div>

            {{-- Subject select --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Select Subject <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="assign-subject-select" onchange="loadSubjectDetails(this.value)"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— Loading subjects... —</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>

            {{-- Subject details (read-only from master) --}}
            <div class="grid grid-cols-3 gap-3 rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 p-3 text-xs" id="assign-subject-details">
                <div><p class="text-slate-400 mb-0.5">Subject Code</p><p id="assign-sub-code" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Hours/Week</p><p id="assign-sub-hpw" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                <div><p class="text-slate-400 mb-0.5">Meetings/Week</p><p id="assign-sub-mpw" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
            </div>

            {{-- Teacher --}}
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Assign Teacher</label>
                    <span id="teacher-load-hint" class="text-[10px] text-slate-400 hidden">Select a subject first to see availability</span>
                </div>
                <div class="relative">
                    <select id="assign-teacher" onchange="onTeacherChange(this.value)"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— Select a subject first —</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>

                {{-- Teacher availability card --}}
                <div id="teacher-avail-card" class="hidden rounded-xl border p-3 text-xs mt-1"></div>
            </div>

            {{-- Conflict warning --}}
            <div id="teacher-conflict-banner" class="hidden rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 px-3 py-2.5 flex items-start gap-2">
                <iconify-icon icon="solar:danger-triangle-bold" width="14" class="text-amber-500 mt-0.5 shrink-0"></iconify-icon>
                <p id="teacher-conflict-msg" class="text-xs text-amber-700 dark:text-amber-400"></p>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="submitAssignment()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> ASSIGN SUBJECT
                </button>
                <button type="button" onclick="closeAcadModal('assign-subject-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ ADD COMPONENT MODAL ══ --}}
<div id="add-component-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAcadModal('add-component-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <h3 id="comp-modal-title" class="text-white text-sm font-bold">ADD COMPONENT</h3>
            <button onclick="closeAcadModal('add-component-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <form id="component-form" class="px-6 py-5 space-y-4">
            <input type="hidden" id="comp-id" value="">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Component Code <span class="text-red-500">*</span></label>
                <input type="text" id="comp-code" name="component_code" placeholder="e.g. PT"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Component Name <span class="text-red-500">*</span></label>
                <input type="text" id="comp-name" name="component_name" placeholder="e.g. Performance Task"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Grade Percentage <span class="text-red-500">*</span></label>
                    <input type="number" id="comp-pct" name="grade_percentage" min="0" max="100" step="0.01" placeholder="50"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Grade Level</label>
                    <div class="relative">
                        <select id="comp-level" name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">All Levels</option>
                            <option value="Elementary">Elementary</option>
                            <option value="Junior High School">Junior High School</option>
                            <option value="Senior High School">Senior High School</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="submitComponent()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon>
                    <span id="comp-btn-label">SAVE COMPONENT</span>
                </button>
                <button type="button" onclick="closeAcadModal('add-component-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ ADD ASSESSMENT MODAL ══ --}}
<div id="add-assessment-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAcadModal('add-assessment-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <h3 id="assess-modal-title" class="text-white text-sm font-bold">ADD ASSESSMENT</h3>
            <button onclick="closeAcadModal('add-assessment-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <form id="assessment-form" class="px-6 py-5 space-y-4">
            <input type="hidden" id="assess-id" value="">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Class (Allocation) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="assess-allocation" name="allocation_id" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— Select Class —</option>
                        @foreach($allAllocations as $a)
                        <option value="{{ $a->id }}">{{ $a->section?->display_name ?? '—' }}: {{ $a->subject_name }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Component <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="assess-component" name="component_id" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select —</option>
                            @foreach($allComponents as $c)
                            <option value="{{ $c->id }}">{{ $c->component_name }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Quarter <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="assess-quarter" name="quarter" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select —</option>
                            @foreach(['First','Second','Third','Fourth'] as $q)
                            <option value="{{ $q }}">{{ $q }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Assessment Name <span class="text-red-500">*</span></label>
                <input type="text" id="assess-name" name="assessment_name" placeholder="e.g. Quiz 1, Periodical Exam"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Max Score <span class="text-red-500">*</span></label>
                    <input type="number" id="assess-max" name="max_score" min="1" value="100"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Assessment Date</label>
                    <input type="date" id="assess-date" name="assessment_date"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="submitAssessment()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon>
                    <span id="assess-btn-label">SAVE ASSESSMENT</span>
                </button>
                <button type="button" onclick="closeAcadModal('add-assessment-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const CSRF    = '{{ csrf_token() }}';
const BASE    = '{{ url("/admin/academic") }}';
const SY      = '{{ $schoolYear ?? "2026-2027" }}';

let _allocSectionId = null;
let _allocSY        = null;
let _allocMpw       = 1;

// ── Helpers ───────────────────────────────────────────────
function openAcadModal(id)  { document.getElementById(id)?.classList.remove('hidden'); document.body.style.overflow='hidden'; }
function closeAcadModal(id) { document.getElementById(id)?.classList.add('hidden');    document.body.style.overflow=''; }

function showToast(msg, type='success') {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[200] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-xl '
        + (type==='success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 3500);
}

function setSelect(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    [...el.options].forEach(o => o.selected = (o.value===val||o.text===val));
}

// ── Subject master search ─────────────────────────────────
document.getElementById('subject-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.subject-row').forEach(row =>
        row.style.display = (!q || (row.dataset.name||'').includes(q)) ? '' : 'none'
    );
});

// ── Hours per week auto-calc ──────────────────────────────
// ── SUBJECT CODE AUTO-GENERATION ─────────────────────────
const _KNOWN_ABBREVS = {
    // Core JHS/Elem
    'mathematics':                    'MATH',
    'math':                           'MATH',
    'english':                        'ENG',
    'science':                        'SCI',
    'earth science':                  'ESCI',
    'life science':                   'LSCI',
    'physical science':               'PSCI',
    'biology':                        'BIO',
    'chemistry':                      'CHEM',
    'physics':                        'PHYS',
    'filipino':                       'FIL',
    'araling panlipunan':             'AP',
    'social studies':                 'SOC',
    'mapeh':                          'MAPEH',
    'music':                          'MUS',
    'arts':                           'ARTS',
    'physical education':             'PE',
    'health':                         'HLT',
    'tle':                            'TLE',
    'technology and livelihood':      'TLE',
    'epp':                            'EPP',
    'values education':               'VALED',
    'edukasyon sa pagpapakatao':      'ESP',
    'esp':                            'ESP',
    'computer':                       'COMP',
    'ict':                            'ICT',
    'reading':                        'READ',
    'christian living':               'CL',
    'religion':                       'REL',
    // SHS Core
    'oral communication':             'OCOMM',
    'reading and writing':            'RDWRT',
    'komunikasyon':                   'KOM',
    'pagbasa':                        'PAG',
    'general mathematics':            'GENMATH',
    'statistics and probability':     'STATPROB',
    'statistics':                     'STAT',
    'earth and life science':         'ELS',
    'personal development':           'PERDEV',
    'understanding culture':          'UCS',
    'media and information literacy': 'MIL',
    'media':                          'MIL',
    'introduction to philosophy':     'PHILO',
    'contemporary arts':              'CARTS',
    'disaster readiness':             'DRR',
    // SHS Applied
    'practical research':             'PRES',
    'research':                       'RES',
    'entrepreneurship':               'ENT',
    'work immersion':                 'WI',
    'community engagement':           'CEGOV',
    'empowerment technologies':       'EMPTECH',
    'empowerment':                    'EMPTECH',
    // SHS Specialized
    'pre-calculus':                   'PC',
    'pre calculus':                   'PC',
    'basic calculus':                 'BC',
    'general biology':                'GBIO',
    'general chemistry':              'GCHEM',
    'general physics':                'GPHYS',
    'business mathematics':           'BMATH',
    'fundamentals of abm':            'FABM',
    'organization and management':    'OM',
    'applied economics':              'APECON',
    'creative writing':               'CW',
    'creative nonfiction':            'CNF',
    'humanities':                     'HUM',
    'philippine politics':            'POLSCI',
    'trends networks':                'TNT',
    'inquiries':                      'INQ',
    'disenyo':                        'DIS',
    'imaging arts':                   'IA',
    'computer programming':           'CP',
    'cookery':                        'COOK',
    'bread and pastry':               'BPP',
    'electrical installation':        'EIM',
    'shielded metal arc':             'SMAW',
};

const _STRANDS_BY_TRACK = {
    'Academic':        ['STEM','HUMSS','ABM','GAS','MAWD'],
    'TVL':             ['ICT','HE','IA','AFA'],
    'Sports':          ['Sports'],
    'Arts and Design': ['AD'],
};

function _subjectAbbrev(name) {
    const clean = name.toLowerCase().replace(/\s+\d+$/, '').trim();
    // Check for exact or prefix match
    for (const [key, abbr] of Object.entries(_KNOWN_ABBREVS)) {
        if (clean === key || clean.startsWith(key + ' ') || clean.startsWith(key)) return abbr;
    }
    // Fallback: initials of each meaningful word, max 6 chars
    const words = clean.split(/\s+/).filter(w => w.length > 1);
    return (words.length === 1
        ? words[0].substring(0, 5)
        : words.map(w => w[0]).join('').substring(0, 6)
    ).toUpperCase();
}

function _gradeNum(grade) {
    if (!grade) return '';
    if (grade === 'Kinder') return 'K';
    const m = grade.match(/\d+/);
    return m ? m[0] : '';
}

function _isSHS(grade) {
    return grade === 'Grade 11' || grade === 'Grade 12';
}

const _GRADES_BY_PROGRAM = {
    'Elementary':         ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'],
    'Junior High School': ['Grade 7','Grade 8','Grade 9','Grade 10'],
    'Senior High School': ['Grade 11','Grade 12'],
};
const _ALL_GRADES = ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];

function onGradeChange() {
    const grade = document.getElementById('sub-grade').value;
    const shsFields = document.getElementById('shs-fields');
    shsFields.style.display = _isSHS(grade) ? 'grid' : 'none';
    if (!_isSHS(grade)) {
        document.getElementById('sub-track').value  = '';
        document.getElementById('sub-strand').innerHTML = '<option value="">— Select Strand —</option>';
    }
    // Auto-set program level from grade
    const progSel = document.getElementById('sub-program');
    if (_isSHS(grade)) {
        progSel.value = 'Senior High School';
    } else if (['Grade 7','Grade 8','Grade 9','Grade 10'].includes(grade)) {
        progSel.value = 'Junior High School';
    } else if (['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'].includes(grade)) {
        progSel.value = 'Elementary';
    }
    autoGenSubjectCode();
}

function onProgramChange() {
    const program  = document.getElementById('sub-program').value;
    const gradeSel = document.getElementById('sub-grade');
    const allowed  = _GRADES_BY_PROGRAM[program] || _ALL_GRADES;
    const current  = gradeSel.value;

    gradeSel.innerHTML = '<option value="">— Select —</option>'
        + allowed.map(g => `<option value="${g}"${g === current ? ' selected' : ''}>${g}</option>`).join('');

    if (current && !allowed.includes(current)) {
        gradeSel.value = '';
    }

    // Show/hide SHS fields based on resulting grade
    const grade = gradeSel.value;
    const shsFields = document.getElementById('shs-fields');
    shsFields.style.display = _isSHS(grade) ? 'grid' : 'none';
    if (!_isSHS(grade)) {
        document.getElementById('sub-track').value = '';
        document.getElementById('sub-strand').innerHTML = '<option value="">— Select Strand —</option>';
    }
    autoGenSubjectCode();
}

function onTrackChange() {
    const track     = document.getElementById('sub-track').value;
    const strandSel = document.getElementById('sub-strand');
    const strands   = _STRANDS_BY_TRACK[track] || [];
    strandSel.innerHTML = '<option value="">— Select Strand —</option>'
        + strands.map(s => `<option value="${s}">${s}</option>`).join('');
    autoGenSubjectCode();
}

function autoGenSubjectCode() {
    const codeEl = document.getElementById('sub-code');
    if (codeEl.dataset.manualEdit === '1') return;
    const name   = (document.getElementById('sub-name').value || '').trim();
    const grade  = document.getElementById('sub-grade').value;
    const strand = document.getElementById('sub-strand')?.value || '';
    const abbr   = name ? _subjectAbbrev(name) : '';
    const num    = _gradeNum(grade);
    if (!abbr) { codeEl.value = ''; return; }
    // SHS with strand: STRAND_ABBREV+GRADE  (e.g. STEM_PC11)
    if (_isSHS(grade) && strand) {
        codeEl.value = `${strand}_${abbr}${num}`;
    } else {
        codeEl.value = num ? `${abbr}-${num}` : abbr;
    }
}

function subCodeManualEdit() {
    const codeEl = document.getElementById('sub-code');
    const badge  = document.getElementById('sub-code-auto-badge');
    codeEl.dataset.manualEdit = '1';
    if (badge) { badge.textContent = 'MANUAL'; badge.className = badge.className.replace('text-blue-500 bg-blue-50 dark:bg-blue-900/20','text-slate-400 bg-slate-100 dark:bg-slate-700'); }
}

function calcHoursPerWeek() {
    const hpm = parseFloat(document.getElementById('sub-hpm').value) || 0;
    const mpw = parseInt(document.getElementById('sub-mpw').value)   || 0;
    document.getElementById('sub-hpw').value = (hpm * mpw).toFixed(1);
}

function toggleSemester(checked) {
    document.getElementById('sem-field').classList.toggle('hidden', !checked);
}

// ── SUBJECT CRUD ──────────────────────────────────────────
function editSubject(id) {
    fetch(`${BASE}/subjects/${id}`)
        .then(r=>r.json())
        .then(({subject:s}) => {
            document.getElementById('subject-id').value   = s.id;
            document.getElementById('sub-code').value     = s.subject_code;
            document.getElementById('sub-name').value     = s.subject_name;
            document.getElementById('sub-desc').value     = s.description ?? '';
            setSelect('sub-dept',    s.department    ?? '');
            setSelect('sub-grade',   s.grade_level   ?? '');
            setSelect('sub-program', s.program_level ?? '');
            setSelect('sub-type',    s.subject_type  ?? '');
            document.getElementById('sub-hpm').value  = s.hours_per_meeting;
            document.getElementById('sub-mpw').value  = s.meetings_per_week;
            calcHoursPerWeek();
            document.getElementById('sub-has-sem').checked = s.has_semester;
            toggleSemester(s.has_semester);
            if (s.default_semester) setSelect('sub-sem', s.default_semester);
            // Restore SHS track/strand fields if applicable
            const isSHS = s.grade_level === 'Grade 11' || s.grade_level === 'Grade 12';
            document.getElementById('shs-fields').style.display = isSHS ? 'grid' : 'none';
            if (isSHS && s.track) {
                setSelect('sub-track', s.track);
                // Populate strand options for this track
                const strands = _STRANDS_BY_TRACK[s.track] || [];
                document.getElementById('sub-strand').innerHTML =
                    '<option value="">— Select Strand —</option>'
                    + strands.map(st => `<option value="${st}"${st===s.strand?' selected':''}>${st}</option>`).join('');
            }
            document.getElementById('subject-modal-title').textContent = 'EDIT SUBJECT';
            document.getElementById('subject-btn-label').textContent   = 'UPDATE SUBJECT';
            // When editing existing subject, preserve code as-is (manual mode)
            const codeEl = document.getElementById('sub-code');
            const badge  = document.getElementById('sub-code-auto-badge');
            codeEl.dataset.manualEdit = '1';
            if (badge) { badge.textContent = 'MANUAL'; badge.className = badge.className.replace('text-blue-500 bg-blue-50 dark:bg-blue-900/20','text-slate-400 bg-slate-100 dark:bg-slate-700'); }
            openAcadModal('add-subject-modal');
        }).catch(() => showToast('Failed to load subject.','error'));
}

function submitSubject() {
    const id = document.getElementById('subject-id').value;
    const payload = {
        subject_code:      document.getElementById('sub-code').value.trim(),
        subject_name:      document.getElementById('sub-name').value.trim(),
        description:       document.getElementById('sub-desc').value.trim(),
        department:        document.getElementById('sub-dept').value,
        grade_level:       document.getElementById('sub-grade').value,
        program_level:     document.getElementById('sub-program').value,
        subject_type:      document.getElementById('sub-type').value,
        track:             document.getElementById('sub-track')?.value  || null,
        strand:            document.getElementById('sub-strand')?.value || null,
        hours_per_meeting: document.getElementById('sub-hpm').value,
        meetings_per_week: document.getElementById('sub-mpw').value,
        has_semester:      document.getElementById('sub-has-sem').checked ? 1 : 0,
        default_semester:  document.getElementById('sub-sem').value,
    };

    if (!payload.subject_code) { showToast('Subject Code is required.','error'); return; }
    if (!payload.subject_name) { showToast('Subject Name is required.','error'); return; }

    const url    = id ? `${BASE}/subjects/${id}` : `${BASE}/subjects`;
    const method = id ? 'PUT' : 'POST';

    fetch(url, { method, headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(payload) })
        .then(r=>r.json())
        .then(data => {
            if (data.success) { closeAcadModal('add-subject-modal'); showToast(data.message); setTimeout(()=>location.reload(),1400); }
            else showToast(data.message||'Error.','error');
        }).catch(()=>showToast('Request failed.','error'));
}

function deleteSubject(id, name) {
    if (!confirm(`Delete subject "${name}"? This cannot be undone.`)) return;
    fetch(`${BASE}/subjects/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r=>r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) setTimeout(()=>location.reload(),1400); })
        .catch(()=>showToast('Request failed.','error'));
}

// Reset add modal on open
document.getElementById('add-subject-modal')?.addEventListener('click', function(e) {
    if (e.target === this) return;
});
function resetSubjectModal() {
    document.getElementById('subject-id').value = '';
    document.getElementById('subject-form').reset();
    document.getElementById('sub-hpw').value = '1';
    document.getElementById('subject-modal-title').textContent = 'ADD SUBJECT';
    document.getElementById('subject-btn-label').textContent   = 'SAVE SUBJECT';
    document.getElementById('sem-field').classList.add('hidden');
    document.getElementById('shs-fields').style.display = 'none';
    document.getElementById('sub-strand').innerHTML = '<option value="">— Select Strand —</option>';
    // Reset auto-gen state
    const codeEl = document.getElementById('sub-code');
    const badge  = document.getElementById('sub-code-auto-badge');
    codeEl.dataset.manualEdit = '0';
    if (badge) { badge.textContent = 'AUTO'; badge.className = badge.className.replace('text-slate-400 bg-slate-100 dark:bg-slate-700','text-blue-500 bg-blue-50 dark:bg-blue-900/20'); }
}

// ── ALLOCATION DETAIL ────────────────────────────────────
function openAllocationDetail(sectionId, schoolYear) {
    _allocSectionId = sectionId;
    _allocSY        = schoolYear;
    document.getElementById('alloc-modal-title').textContent = 'SUBJECT ALLOCATION — Loading...';
    document.getElementById('assigned-subjects-tbody').innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-xs text-slate-400">Loading...</td></tr>';
    openAcadModal('allocation-detail-modal');
    loadAllocationDetail();
}

function loadAllocationDetail() {
    fetch(`${BASE}/allocation/config?section_id=${_allocSectionId}&school_year=${_allocSY}`)
        .then(r=>r.json())
        .then(({section:s, config:c, assigned}) => {
            document.getElementById('alloc-modal-title').textContent = `SUBJECT ALLOCATION — ${s.display_name}`;
            document.getElementById('alloc-section-name').textContent = s.display_name;
            document.getElementById('alloc-sy').textContent      = 'SY ' + s.school_year;
            document.getElementById('alloc-program').textContent = s.program_level;
            document.getElementById('alloc-grade').textContent   = s.grade_level;
            document.getElementById('alloc-adviser').textContent = s.adviser;
            document.getElementById('alloc-room').textContent    = s.room;
            document.getElementById('alloc-required-input').value     = c.total_subjects_required;
            document.getElementById('alloc-allocated-count').textContent = c.total_subjects_allocated;
            document.getElementById('alloc-remaining').textContent    = c.remaining;

            const bar    = document.getElementById('alloc-progress-bar');
            const badge  = document.getElementById('alloc-status-badge');
            const sBadge = {pending:'bg-yellow-100 text-yellow-700',in_progress:'bg-blue-100 text-blue-700',complete:'bg-green-100 text-green-700'};
            const sLabel = {pending:'Pending',in_progress:'In Progress',complete:'Complete'};
            bar.style.width = c.progress_pct + '%';
            bar.className   = 'h-full rounded-full transition-all duration-500 ' + (c.allocation_status==='complete'?'bg-green-500':'bg-blue-500');
            badge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' + (sBadge[c.allocation_status]||'');
            badge.textContent = sLabel[c.allocation_status] || c.allocation_status;

            const btn = document.getElementById('assign-subject-btn');
            if (c.allocation_status === 'complete') {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                btn.disabled = false;
                btn.classList.remove('opacity-50','cursor-not-allowed');
            }

            const tbody = document.getElementById('assigned-subjects-tbody');
            if (!assigned.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-xs text-slate-400">No subjects assigned yet.</td></tr>';
                return;
            }
            tbody.innerHTML = assigned.map(a => `
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <td class="px-4 py-3 text-xs font-mono text-slate-400">${a.subject_code}</td>
                    <td class="px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300">${a.subject_name}</td>
                    <td class="px-4 py-3 text-xs text-center text-slate-500">${a.hours_per_week}</td>
                    <td class="px-4 py-3 text-xs text-slate-500">
                        ${a.teacher !== '—'
                            ? `<span class="inline-flex items-center gap-1"><iconify-icon icon="solar:user-bold" width="11" class="text-blue-400"></iconify-icon>${a.teacher}</span>`
                            : '<span class="text-amber-500 italic">No teacher</span>'}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="removeAllocation(${a.id}, '${a.subject_name.replace(/'/g,"\\\'")}')"
                            class="flex items-center gap-1 mx-auto px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 text-xs font-medium transition-colors">
                            <iconify-icon icon="solar:trash-bin-trash-bold" width="13"></iconify-icon> Remove
                        </button>
                    </td>
                </tr>`).join('');
        }).catch(() => showToast('Failed to load allocation data.','error'));
}

function saveRequiredCount() {
    const count = parseInt(document.getElementById('alloc-required-input').value);
    if (isNaN(count) || count < 1) { showToast('Please enter a valid number.','error'); return; }

    fetch(`${BASE}/allocation/set-required`, {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({section_id:_allocSectionId, school_year:_allocSY, total_subjects_required:count}),
    }).then(r=>r.json()).then(data => {
        if (data.success) { showToast(data.message); loadAllocationDetail(); }
        else showToast(data.message,'error');
    }).catch(()=>showToast('Request failed.','error'));
}

// Teachers cache for current allocation modal session
let _teachersCache = [];

function openAssignSubjectModal() {
    document.getElementById('assign-section-name').textContent = document.getElementById('alloc-section-name').textContent;
    document.getElementById('assign-grade').textContent        = document.getElementById('alloc-grade').textContent;
    document.getElementById('assign-remaining').textContent    = document.getElementById('alloc-remaining').textContent;
    document.getElementById('assign-subject-select').innerHTML = '<option value="">— Loading subjects... —</option>';
    resetSubjectDetails();
    resetTeacherPanel();
    _teachersCache = [];

    fetch(`${BASE}/allocation/subjects-for-section?section_id=${_allocSectionId}&school_year=${_allocSY}`)
        .then(r => r.json())
        .then(({subjects}) => {
            const sel = document.getElementById('assign-subject-select');
            if (!subjects.length) {
                sel.innerHTML = '<option value="">— No subjects available for this grade —</option>';
                return;
            }
            sel.innerHTML = '<option value="">— Select Subject —</option>'
                + subjects.map(s => `<option value="${s.id}" data-code="${s.subject_code}" data-hpw="${(s.hours_per_meeting * s.meetings_per_week).toFixed(1)}" data-hpm="${s.hours_per_meeting}" data-mpw="${s.meetings_per_week}">${s.subject_name} (${s.subject_code})</option>`).join('');
        });

    openAcadModal('assign-subject-modal');
}

function resetSubjectDetails() {
    document.getElementById('assign-sub-code').textContent = '—';
    document.getElementById('assign-sub-hpw').textContent  = '—';
    document.getElementById('assign-sub-mpw').textContent  = '—';
}

function resetTeacherPanel() {
    document.getElementById('assign-teacher').innerHTML = '<option value="">— Select a subject first —</option>';
    document.getElementById('teacher-avail-card').classList.add('hidden');
    document.getElementById('teacher-conflict-banner').classList.add('hidden');
    document.getElementById('teacher-load-hint').classList.add('hidden');
}

function loadSubjectDetails(subjectId) {
    if (!subjectId) {
        resetSubjectDetails();
        resetTeacherPanel();
        return;
    }
    const opt = document.querySelector(`#assign-subject-select option[value="${subjectId}"]`);
    if (!opt) return;

    document.getElementById('assign-sub-code').textContent = opt.dataset.code;
    document.getElementById('assign-sub-hpw').textContent  = opt.dataset.hpw;
    document.getElementById('assign-sub-mpw').textContent  = opt.dataset.mpw;
    _allocMpw = parseInt(opt.dataset.mpw) || 1;

    // Load teachers with availability for this subject
    const hint = document.getElementById('teacher-load-hint');
    hint.textContent = 'Loading teachers…';
    hint.classList.remove('hidden');

    fetch(`${BASE}/allocation/teachers-for-section?section_id=${_allocSectionId}&school_year=${_allocSY}&subject_id=${subjectId}`)
        .then(r => r.json())
        .then(({teachers}) => {
            _teachersCache = teachers;
            const sel = document.getElementById('assign-teacher');
            sel.innerHTML = '<option value="">— No teacher assigned —</option>'
                + teachers.map(t => {
                    const badge = t.status === 'full' ? '⛔ Full' : t.status === 'near_full' ? '⚠ Near Limit' : '✓ Available';
                    const warn  = (t.conflict || t.has_warnings) ? ' ⚠ Mismatch' : '';
                    return `<option value="${t.id}" ${t.status === 'full' ? 'disabled' : ''}>${t.name} — ${badge} (${t.available_hours}h free)${warn}</option>`;
                }).join('');
            hint.textContent = `${teachers.filter(t => t.status === 'available').length} of ${teachers.length} teachers available`;
        })
        .catch(() => { hint.textContent = 'Failed to load teachers.'; });
}

function onTeacherChange(teacherId) {
    const card    = document.getElementById('teacher-avail-card');
    const banner  = document.getElementById('teacher-conflict-banner');
    banner.classList.add('hidden');
    card.classList.add('hidden');

    if (!teacherId) return;

    const teacher = _teachersCache.find(t => String(t.id) === String(teacherId));
    if (!teacher) return;

    // Availability card
    const colorClass = teacher.status === 'full' ? 'border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10'
        : teacher.status === 'near_full'          ? 'border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10'
        : 'border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10';
    const barColor = teacher.status === 'full' ? 'bg-red-500' : teacher.status === 'near_full' ? 'bg-amber-400' : 'bg-green-500';
    const textColor = teacher.status === 'full' ? 'text-red-700' : teacher.status === 'near_full' ? 'text-amber-700' : 'text-green-700';

    card.className = `rounded-xl border p-3 text-xs mt-1 ${colorClass}`;
    card.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <span class="font-semibold ${textColor}">${teacher.name} — Workload</span>
            <span class="font-bold ${textColor}">${teacher.current_hours}h / ${teacher.max_hours}h per week</span>
        </div>
        <div class="h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
            <div class="h-2 rounded-full ${barColor} transition-all" style="width:${teacher.load_pct}%"></div>
        </div>
        <div class="flex justify-between mt-1">
            <span class="${textColor}">${teacher.load_pct}% utilized</span>
            <span class="${textColor}">${teacher.available_hours}h available</span>
        </div>
        ${teacher.already_in_section > 0 ? `<p class="mt-1.5 text-blue-600 dark:text-blue-400"><iconify-icon icon="solar:info-circle-linear" width="11" class="inline"></iconify-icon> Already teaching ${teacher.already_in_section} subject(s) in this section.</p>` : ''}
    `;
    card.classList.remove('hidden');

    // Conflict / warning banner
    const allMessages = [];
    if (teacher.conflict)    allMessages.push(teacher.conflict_msg);
    if (teacher.has_warnings) teacher.warnings.forEach(w => allMessages.push(w));
    if (allMessages.length > 0) {
        document.getElementById('teacher-conflict-msg').innerHTML = allMessages.map(m => `<p>• ${m}</p>`).join('');
        banner.classList.remove('hidden');
    }
}

function submitAssignment() {
    const subjectId = document.getElementById('assign-subject-select').value;
    const teacherId = document.getElementById('assign-teacher').value;
    if (!subjectId) { showToast('Please select a subject.', 'error'); return; }

    // Warn but allow if conflict/mismatch (not block)
    const teacher = _teachersCache.find(t => String(t.id) === String(teacherId));
    const hasIssues = teacher?.conflict || teacher?.has_warnings;
    if (hasIssues) {
        const msgs = [];
        if (teacher.conflict) msgs.push(teacher.conflict_msg);
        if (teacher.has_warnings) teacher.warnings.forEach(w => msgs.push(w));
        if (!confirm('Warning:\n' + msgs.map(m => '• ' + m).join('\n') + '\n\nProceed anyway?')) return;
    }

    fetch(`${BASE}/allocation`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            section_id:  _allocSectionId,
            subject_id:  subjectId,
            school_year: _allocSY,
            teacher_id:  teacherId || null,
        }),
    }).then(r => r.json()).then(data => {
        if (data.success) {
            closeAcadModal('assign-subject-modal');
            showToast(data.message);
            loadAllocationDetail();
        } else showToast(data.message, 'error');
    }).catch(() => showToast('Request failed.', 'error'));
}

function removeAllocation(id, name) {
    if (!confirm(`Remove "${name}" from this section?`)) return;
    fetch(`${BASE}/allocation/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r=>r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) loadAllocationDetail(); })
        .catch(()=>showToast('Request failed.','error'));
}

// ── COMPONENT CRUD ────────────────────────────────────────
let _compId = null;
function editComponent(id, c) {
    _compId = id;
    document.getElementById('comp-id').value    = id;
    document.getElementById('comp-code').value  = c.component_code;
    document.getElementById('comp-name').value  = c.component_name;
    document.getElementById('comp-pct').value   = c.grade_percentage;
    setSelect('comp-level', c.grade_level ?? '');
    document.getElementById('comp-modal-title').textContent = 'EDIT COMPONENT';
    document.getElementById('comp-btn-label').textContent   = 'UPDATE COMPONENT';
    openAcadModal('add-component-modal');
}
function submitComponent() {
    const id = document.getElementById('comp-id').value;
    const payload = {
        component_code:   document.getElementById('comp-code').value.trim(),
        component_name:   document.getElementById('comp-name').value.trim(),
        grade_percentage: document.getElementById('comp-pct').value,
        grade_level:      document.getElementById('comp-level').value,
    };
    if (!payload.component_code) { showToast('Component Code is required.','error'); return; }
    if (!payload.component_name) { showToast('Component Name is required.','error'); return; }
    if (!payload.grade_percentage) { showToast('Grade Percentage is required.','error'); return; }

    const url = id ? `${BASE}/components/${id}` : `${BASE}/components`;
    const method = id ? 'PUT' : 'POST';
    fetch(url, { method, headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(payload) })
        .then(r=>r.json())
        .then(data => {
            if (data.success) { closeAcadModal('add-component-modal'); showToast(data.message); setTimeout(()=>location.reload(),1400); }
            else showToast(data.message||'Error.','error');
        }).catch(()=>showToast('Request failed.','error'));
}
function deleteComponent(id, name) {
    if (!confirm(`Delete component "${name}"?`)) return;
    fetch(`${BASE}/components/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r=>r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) setTimeout(()=>location.reload(),1400); })
        .catch(()=>showToast('Request failed.','error'));
}

// ── ASSESSMENT CRUD ───────────────────────────────────────
let _assessId = null;
function editAssessment(id, a) {
    _assessId = id;
    document.getElementById('assess-id').value  = id;
    setSelect('assess-allocation', a.allocation_id);
    setSelect('assess-component',  a.component_id);
    setSelect('assess-quarter',    a.quarter);
    document.getElementById('assess-name').value = a.assessment_name;
    document.getElementById('assess-max').value  = a.max_score;
    document.getElementById('assess-date').value = a.assessment_date ?? '';
    document.getElementById('assess-modal-title').textContent = 'EDIT ASSESSMENT';
    document.getElementById('assess-btn-label').textContent   = 'UPDATE ASSESSMENT';
    openAcadModal('add-assessment-modal');
}
function submitAssessment() {
    const id = document.getElementById('assess-id').value;
    const payload = {
        allocation_id:   document.getElementById('assess-allocation').value,
        component_id:    document.getElementById('assess-component').value,
        quarter:         document.getElementById('assess-quarter').value,
        assessment_name: document.getElementById('assess-name').value.trim(),
        max_score:       document.getElementById('assess-max').value,
        assessment_date: document.getElementById('assess-date').value || null,
    };
    if (!payload.allocation_id) { showToast('Please select a class.','error'); return; }
    if (!payload.component_id)  { showToast('Please select a component.','error'); return; }
    if (!payload.quarter)       { showToast('Please select a quarter.','error'); return; }
    if (!payload.assessment_name) { showToast('Assessment name is required.','error'); return; }

    const url = id ? `${BASE}/assessments/${id}` : `${BASE}/assessments`;
    const method = id ? 'PUT' : 'POST';
    fetch(url, { method, headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(payload) })
        .then(r=>r.json())
        .then(data => {
            if (data.success) { closeAcadModal('add-assessment-modal'); showToast(data.message); setTimeout(()=>location.reload(),1400); }
            else showToast(data.message||'Error.','error');
        }).catch(()=>showToast('Request failed.','error'));
}
function deleteAssessment(id, name) {
    if (!confirm(`Delete assessment "${name}"?`)) return;
    fetch(`${BASE}/assessments/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r=>r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) setTimeout(()=>location.reload(),1400); })
        .catch(()=>showToast('Request failed.','error'));
}

// Reset add component modal
document.querySelector('[onclick="openAcadModal(\'add-component-modal\')"]')?.addEventListener('click', () => {
    document.getElementById('comp-id').value = '';
    document.getElementById('component-form').reset();
    document.getElementById('comp-modal-title').textContent = 'ADD COMPONENT';
    document.getElementById('comp-btn-label').textContent   = 'SAVE COMPONENT';
});

// Reset add assessment modal
document.querySelector('[onclick="openAcadModal(\'add-assessment-modal\')"]')?.addEventListener('click', () => {
    document.getElementById('assess-id').value = '';
    document.getElementById('assessment-form').reset();
    document.getElementById('assess-modal-title').textContent = 'ADD ASSESSMENT';
    document.getElementById('assess-btn-label').textContent   = 'SAVE ASSESSMENT';
});

// ══════════════════════════════════════════════════════
// CURRICULUM Alpine Component
// ══════════════════════════════════════════════════════
function curriculumApp() {
    return {
        schoolYear: '{{ $schoolYear }}',
        grades: [],
        selectedGrade: null,
        selectedStrand: null,
        SHS_STRANDS: ['STEM', 'HUMSS', 'ABM', 'GAS', 'ICT'],
        curriculumConfig: null,
        curriculumSubjects: [],
        availableSubjects: [],
        loading: false,

        // Assign modal
        showAssignModal: false,
        assignForm: { program_level: '', grade_level: '', total_required: 8, semester: 'Full Year', selected_ids: [] },
        assignLoading: false,

        // Edit modal
        showEditModal: false,
        editForm: { id: null, subject_name: '', subject_code: '', hours_per_week: 0, meetings_per_week: 1, hours_per_meeting: 0, subject_type: 'Core', is_required: true, semester: 'Full Year' },
        editLoading: false,

        // Remove modal
        showRemoveModal: false,
        removeTarget: null,
        sectionCount: 0,
        removeLoading: false,

        get isSHSGrade() {
            return this.selectedGrade === 'Grade 11' || this.selectedGrade === 'Grade 12';
        },

        get effectiveGradeKey() {
            if (this.isSHSGrade && this.selectedStrand) return this.selectedGrade + ' - ' + this.selectedStrand;
            return this.selectedGrade;
        },

        get firstSemSubjects() {
            return this.curriculumSubjects.filter(s => s.semester === '1st Semester');
        },
        get secondSemSubjects() {
            return this.curriculumSubjects.filter(s => s.semester === '2nd Semester');
        },
        get fullYearSubjects() {
            return this.curriculumSubjects.filter(s => !s.semester || s.semester === 'Full Year');
        },

        async init() { await this.loadGradeStats(); },

        async loadGradeStats() {
            try {
                const r = await fetch(`{{ route('admin.academic.curriculum.grades') }}?school_year=${this.schoolYear}&_t=${Date.now()}`, {
                    headers: { 'Accept': 'application/json' }, cache: 'no-store',
                });
                const j = await r.json();
                if (j.success) this.grades = j.grades;
                if (this.effectiveGradeKey) await this.loadCurriculumDetail();
            } catch (e) { console.error('Curriculum grade stats error:', e); }
        },

        async selectGrade(gradeLevel) {
            this.selectedGrade  = gradeLevel;
            this.selectedStrand = null;
            this.curriculumConfig   = null;
            this.curriculumSubjects = [];
            this.availableSubjects  = [];
            // SHS: wait for strand selection before loading
            if (!this.isSHSGrade) await this.loadCurriculumDetail();
        },

        async selectStrand(strand) {
            this.selectedStrand = strand;
            await this.loadCurriculumDetail();
        },

        async loadCurriculumDetail() {
            if (!this.effectiveGradeKey) return;
            this.loading = true;
            this.curriculumConfig   = null;
            this.curriculumSubjects = [];
            this.availableSubjects  = [];
            try {
                const r = await fetch(`{{ route('admin.academic.curriculum.detail') }}?grade_level=${encodeURIComponent(this.effectiveGradeKey)}&school_year=${this.schoolYear}&_t=${Date.now()}`, {
                    headers: { 'Accept': 'application/json' }, cache: 'no-store',
                });
                const j = await r.json();
                if (j.success) {
                    this.curriculumConfig   = j.config;
                    this.curriculumSubjects = j.subjects;
                    this.availableSubjects  = j.all_subjects;
                }
            } catch (e) { console.error('Curriculum detail error:', e); }
            finally { this.loading = false; }
        },

        get gradesForProgram() {
            const map = {
                'Elementary':         ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'],
                'Junior High School': ['Grade 7','Grade 8','Grade 9','Grade 10'],
                'Senior High School': ['Grade 11','Grade 12'],
            };
            return map[this.assignForm.program_level] || [];
        },

        async openAssignModal(semOverride) {
            const prog = this.curriculumConfig?.program_level || this.defaultProgram(this.selectedGrade);
            this.assignForm = {
                program_level:  prog,
                grade_level:    this.effectiveGradeKey,
                total_required: this.curriculumConfig?.total_subjects_required || 8,
                semester:       semOverride || (this.isSHSGrade ? '1st Semester' : 'Full Year'),
                selected_ids:   [],
            };
            this.showAssignModal = true;
            await this.fetchAvailableSubjects();
        },

        async onAssignProgramChange() {
            const grades = this.gradesForProgram;
            this.assignForm.grade_level  = grades[0] || '';
            this.assignForm.selected_ids = [];
            await this.fetchAvailableSubjects();
        },

        async onAssignGradeChange() {
            this.assignForm.selected_ids = [];
            await this.fetchAvailableSubjects();
        },

        async fetchAvailableSubjects() {
            if (!this.assignForm.grade_level) return;
            try {
                const r = await fetch(`{{ route('admin.academic.curriculum.detail') }}?grade_level=${encodeURIComponent(this.assignForm.grade_level)}&school_year=${this.schoolYear}&_t=${Date.now()}`, {
                    headers: { 'Accept': 'application/json' }, cache: 'no-store',
                });
                const j = await r.json();
                if (j.success) this.availableSubjects = j.all_subjects;
            } catch (e) { console.error(e); }
        },

        toggleSubject(id) {
            const idx = this.assignForm.selected_ids.indexOf(id);
            if (idx === -1) this.assignForm.selected_ids.push(id);
            else            this.assignForm.selected_ids.splice(idx, 1);
        },

        toggleSelectAll() {
            const all = this.availableSubjects.map(s => s.id);
            const allSelected = all.length > 0 && all.every(id => this.assignForm.selected_ids.includes(id));
            this.assignForm.selected_ids = allSelected ? [] : [...all];
        },

        async saveAssign() {
            if (!this.assignForm.selected_ids.length) { this.toast('Select at least one subject.', 'error'); return; }
            this.assignLoading = true;
            try {
                const r = await fetch('{{ route('admin.academic.curriculum.save') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        grade_level:              this.assignForm.grade_level,
                        program_level:            this.assignForm.program_level,
                        school_year:              this.schoolYear,
                        total_subjects_required:  this.assignForm.total_required,
                        subject_ids:              this.assignForm.selected_ids,
                        semester:                 this.assignForm.semester,
                    }),
                });
                const j = await r.json();
                if (j.success) {
                    this.showAssignModal = false;
                    this.toast(j.message, 'success');
                    await this.loadGradeStats();
                } else { this.toast(j.message || 'Error saving.', 'error'); }
            } catch (e) { this.toast('Network error.', 'error'); }
            finally { this.assignLoading = false; }
        },

        openEditSubject(cs) {
            this.editForm = {
                id:                cs.id,
                subject_name:      cs.subject_name,
                subject_code:      cs.subject_code,
                hours_per_week:    parseFloat(cs.hours_per_week)     || 0,
                meetings_per_week: parseInt(cs.meetings_per_week)    || 1,
                hours_per_meeting: parseFloat(cs.hours_per_meeting)  || 0,
                subject_type:      cs.subject_type || 'Core',
                is_required:       cs.is_required !== false,
                semester:          cs.semester || 'Full Year',
            };
            this.showEditModal = true;
        },

        calcHoursPerMeeting() {
            const mpw = parseInt(this.editForm.meetings_per_week) || 1;
            this.editForm.hours_per_meeting = mpw > 0
                ? Math.round((parseFloat(this.editForm.hours_per_week) / mpw) * 100) / 100
                : 0;
        },

        async saveEdit() {
            this.editLoading = true;
            try {
                const r = await fetch(`{{ url('/admin/academic/curriculum/subject') }}/${this.editForm.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        hours_per_week:    this.editForm.hours_per_week,
                        meetings_per_week: this.editForm.meetings_per_week,
                        subject_type:      this.editForm.subject_type,
                        is_required:       this.editForm.is_required,
                        semester:          this.editForm.semester,
                    }),
                });
                const j = await r.json();
                if (j.success) {
                    this.showEditModal = false;
                    this.toast(j.message, 'success');
                    await this.loadCurriculumDetail();
                } else { this.toast(j.message || 'Error updating.', 'error'); }
            } catch (e) { this.toast('Network error.', 'error'); }
            finally { this.editLoading = false; }
        },

        openRemoveSubject(cs) {
            this.removeTarget = cs;
            this.sectionCount = cs.section_count || 0;
            this.showRemoveModal = true;
        },

        async confirmRemove() {
            this.removeLoading = true;
            try {
                const r = await fetch(`{{ url('/admin/academic/curriculum/subject') }}/${this.removeTarget.id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const j = await r.json();
                if (j.success) {
                    this.showRemoveModal = false;
                    this.removeTarget    = null;
                    this.toast(j.message, 'success');
                    await this.loadGradeStats();
                } else { this.toast(j.message || 'Error removing.', 'error'); }
            } catch (e) { this.toast('Network error.', 'error'); }
            finally { this.removeLoading = false; }
        },

        defaultProgram(g) {
            if (['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'].includes(g)) return 'Elementary';
            if (['Grade 7','Grade 8','Grade 9','Grade 10'].includes(g)) return 'Junior High School';
            return 'Senior High School';
        },

        toast(msg, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', { detail: { msg, type } }));
        },
    };
}

// ══════════════════════════════════════════════════════
// ASSIGN TEACHER Alpine Component
// ══════════════════════════════════════════════════════
function teacherAssignApp() {
    return {
        expandedRow:      null,
        subjectsMap:      {},
        loadingRow:       null,
        editingTeacher:   {},   // { allocId: { teacher_id, teacher_name } }
        teachersBySubject:{},   // { allocId: [teacher objects with workload info] }
        loadingTeachers:  null, // allocId being fetched
        teacherSearch:    {},   // { allocId: 'search string' }
        savingId:         null,

        async init() {},

        async toggleRow(sectionId, schoolYear) {
            if (this.expandedRow === sectionId) {
                this.expandedRow = null;
                return;
            }
            this.expandedRow = sectionId;
            if (!this.subjectsMap[sectionId]) {
                await this.loadSubjects(sectionId, schoolYear);
            }
        },

        async loadSubjects(sectionId, schoolYear) {
            this.loadingRow = sectionId;
            try {
                const r = await fetch(
                    `{{ route('admin.academic.section-subjects-teachers') }}?section_id=${sectionId}&school_year=${encodeURIComponent(schoolYear)}&_t=${Date.now()}`,
                    { headers: { 'Accept': 'application/json' }, cache: 'no-store' }
                );
                const j = await r.json();
                if (j.success) this.subjectsMap = { ...this.subjectsMap, [sectionId]: j.subjects };
            } catch (e) { console.error(e); }
            finally { this.loadingRow = null; }
        },

        async startEdit(subj, sectionId, schoolYear) {
            this.editingTeacher = {
                ...this.editingTeacher,
                [subj.id]: { teacher_id: subj.teacher_id ? String(subj.teacher_id) : '', teacher_name: subj.teacher_name || null },
            };
            this.teacherSearch = { ...this.teacherSearch, [subj.id]: '' };
            // Fetch availability if not cached
            if (!this.teachersBySubject[subj.id]) {
                await this.loadTeachersForSubject(subj, sectionId, schoolYear);
            }
        },

        async loadTeachersForSubject(subj, sectionId, schoolYear) {
            this.loadingTeachers = subj.id;
            try {
                const url = `{{ route('admin.academic.allocation.teachers-for-section') }}?section_id=${sectionId}&subject_id=${subj.subject_id || ''}&school_year=${encodeURIComponent(schoolYear)}&_t=${Date.now()}`;
                const r = await fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
                const j = await r.json();
                if (j.success) {
                    // Keep only available teachers (not full, not over hours)
                    // Always include current teacher so existing assignment is selectable
                    const currentId = subj.teacher_id ? String(subj.teacher_id) : null;
                    const filtered = j.teachers.filter(t =>
                        t.status !== 'full' && !t.conflict || String(t.id) === currentId
                    );
                    this.teachersBySubject = { ...this.teachersBySubject, [subj.id]: filtered };
                }
            } catch (e) { console.error(e); }
            finally { this.loadingTeachers = null; }
        },

        filteredTeachers(subjId) {
            const teachers = this.teachersBySubject[subjId] || [];
            const q = (this.teacherSearch[subjId] || '').toLowerCase().trim();
            return q ? teachers.filter(t => t.name.toLowerCase().includes(q)) : teachers;
        },

        selectedTeacherName(subjId) {
            const tid = this.editingTeacher[subjId]?.teacher_id;
            if (!tid) return '';
            const t = (this.teachersBySubject[subjId] || []).find(t => String(t.id) === String(tid));
            return t ? t.name : (this.editingTeacher[subjId]?.teacher_name || '');
        },

        selectTeacher(subjId, teacherId, teacherName) {
            this.editingTeacher = {
                ...this.editingTeacher,
                [subjId]: { ...this.editingTeacher[subjId], teacher_id: teacherId, teacher_name: teacherName },
            };
        },

        cancelEdit(subjId) {
            const ec = { ...this.editingTeacher }; delete ec[subjId]; this.editingTeacher = ec;
            const sc = { ...this.teacherSearch };   delete sc[subjId]; this.teacherSearch  = sc;
        },

        async saveTeacher(subj, sectionId) {
            const editing = this.editingTeacher[subj.id];
            if (!editing) return;
            this.savingId = subj.id;
            try {
                const r = await fetch('{{ route('admin.academic.assign-teacher') }}', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body:    JSON.stringify({ allocation_id: subj.id, teacher_id: editing.teacher_id || null }),
                });
                const j = await r.json();
                if (j.success) {
                    const tName = editing.teacher_name || null;
                    const subjects = (this.subjectsMap[sectionId] || []).map(s =>
                        s.id === subj.id ? { ...s, teacher_id: editing.teacher_id || null, teacher_name: tName } : s
                    );
                    this.subjectsMap = { ...this.subjectsMap, [sectionId]: subjects };
                    this.cancelEdit(subj.id);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: j.message, type: 'success' } }));
                } else {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: j.message || 'Failed.', type: 'error' } }));
                }
            } catch (e) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Network error.', type: 'error' } }));
            } finally { this.savingId = null; }
        },
    };
}
</script>
@endpush
@endsection