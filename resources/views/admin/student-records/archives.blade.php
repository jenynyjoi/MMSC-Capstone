@extends('layouts.admin_layout')

@section('title', 'Student Archives')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-24">

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <x-admin.page-header
        title="Student Records"
        subtitle="Student Record and Information"
        school-year="{{ $activeSchoolYear }}"
        :show-menu="true"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:archive-bold" width="20" class="text-purple-500"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Student Archives</h2>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 ml-7">Archived student records. These students have been moved out of the active list.</p>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
            </div>
            <form method="GET" action="{{ route('admin.student-records.archives') }}">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

                    {{-- School Year --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Years</option>
                                @foreach($schoolYears as $sy)
                                <option value="{{ $sy }}" {{ request('school_year') === $sy ? 'selected' : '' }}>SY {{ $sy }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Clearance Status --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Clearance Status</label>
                        <div class="relative">
                            <select name="status_filter" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="cleared" {{ request('status_filter') === 'cleared' ? 'selected' : '' }}>Cleared</option>
                                <option value="pending" {{ request('status_filter') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="overdue" {{ request('status_filter') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Grade Level --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                        <div class="relative">
                            <select name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Grades</option>
                                <optgroup label="Elementary">
                                    @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_level') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Junior High School">
                                    @foreach(['Grade 7','Grade 8','Grade 9','Grade 10'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_level') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Senior High School">
                                    <option value="Grade 11" {{ request('grade_level') === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ request('grade_level') === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                </optgroup>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.student-records.archives') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
        </div>

        {{-- Table Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ $archived->total() }} archived student{{ $archived->total() !== 1 ? 's' : '' }}
            </p>
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" id="archive-search" placeholder="Search student…"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto" x-data="archiveTable()">
            <table class="w-full text-left text-sm border-collapse" style="min-width:1000px">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" x-model="allSelected" @change="toggleAll()"
                                class="h-4 w-4 rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade and Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Archived Date</th>
                        <th class="px-4 py-3 whitespace-nowrap">Clearance</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border" id="archive-table-body">

                @php
                    $clrClass = [
                        'cleared' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                        'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                    ];
                @endphp

                @forelse ($archived as $student)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors archive-row"
                    :class="selected.includes({{ $student->id }}) ? 'bg-purple-50/60 dark:bg-purple-900/10' : ''"
                    data-name="{{ strtolower($student->formatted_name ?? $student->last_name . ' ' . $student->first_name) }}">

                    <td class="px-4 py-3">
                        <input type="checkbox" :value="{{ $student->id }}" x-model="selected"
                            @change="updateAllSelected()"
                            class="h-4 w-4 rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                    </td>
                    <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500">{{ $student->student_id }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $student->school_year }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/20 text-[11px] font-bold text-purple-600 dark:text-purple-400">
                                {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">
                                {{ $student->formatted_name ?? ($student->last_name . ', ' . $student->first_name) }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $student->section_display_name }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                        {{ $student->archived_at?->format('M j, Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrClass[$student->clearance_status ?? 'pending'] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->clearance_status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                title="View Profile"
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:hover:bg-amber-900/40 dark:text-amber-400 transition-colors">
                                <iconify-icon icon="solar:eye-bold" width="14"></iconify-icon>
                            </a>
                            <button title="Restore"
                                onclick="restoreStudent({{ $student->id }}, '{{ addslashes($student->formatted_name ?? $student->last_name . ' ' . $student->first_name) }}')"
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/20 dark:hover:bg-green-900/40 dark:text-green-400 transition-colors">
                                <iconify-icon icon="solar:restart-bold" width="14"></iconify-icon>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <iconify-icon icon="solar:archive-linear" width="36" class="text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-500">No archived students found.</p>
                            <p class="text-xs text-slate-400">Students archived from the Student List will appear here.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>

            {{-- Bulk Action Bar --}}
            <div class="fixed bottom-0 left-0 right-0 z-30 transition-all duration-300"
                 :class="selected.length > 0 ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0 pointer-events-none'">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-8 pb-4">
                    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="solar:archive-bold" width="20" class="text-purple-400"></iconify-icon>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                <span x-text="selected.length"></span>
                                <span x-text="selected.length === 1 ? ' Selected Student' : ' Selected Students'"></span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                                <iconify-icon icon="solar:file-download-bold" width="14" class="text-green-600"></iconify-icon>
                                EXPORT EXCEL
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if($archived->hasPages())
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400 dark:text-slate-500">Showing {{ $archived->firstItem() }}–{{ $archived->lastItem() }} of {{ $archived->total() }}</p>
            {{ $archived->links() }}
        </div>
        @endif

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- Restore confirmation modal --}}
<div id="restore-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('restore-modal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:restart-bold" width="16" class="text-white/80"></iconify-icon>
                <h3 class="text-white text-sm font-bold">RESTORE STUDENT</h3>
            </div>
            <button onclick="document.getElementById('restore-modal').classList.add('hidden')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-slate-600 dark:text-slate-300">Restore <strong id="restore-name"></strong> to active status?</p>
            <p class="text-xs text-slate-400">The student will be moved back to the Student List with <strong>inactive</strong> status for review.</p>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button id="restore-confirm-btn" onclick="submitRestore()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:restart-bold" width="14"></iconify-icon> RESTORE
                </button>
                <button onclick="document.getElementById('restore-modal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function archiveTable() {
    return {
        selected: [],
        allSelected: false,
        rowIds: @json($archived->pluck('id')->values()),
        toggleAll() { this.selected = this.allSelected ? [...this.rowIds] : []; },
        updateAllSelected() { this.allSelected = this.selected.length === this.rowIds.length && this.rowIds.length > 0; }
    }
}

// Search
document.getElementById('archive-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.archive-row').forEach(row => {
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
    });
});

let restoreStudentId = null;
function restoreStudent(id, name) {
    restoreStudentId = id;
    document.getElementById('restore-name').textContent = name;
    document.getElementById('restore-modal').classList.remove('hidden');
}
function submitRestore() {
    const btn = document.getElementById('restore-confirm-btn');
    btn.disabled = true;
    fetch('{{ route("admin.student-records.archive.restore") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ student_id: restoreStudentId })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('restore-modal').classList.add('hidden');
        if (data.success) { setTimeout(() => location.reload(), 1000); }
        btn.disabled = false;
    })
    .catch(() => { btn.disabled = false; });
}
</script>
@endpush

@endsection
