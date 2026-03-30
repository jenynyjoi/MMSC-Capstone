<!-- resource/views/admin/enrollment/enroll.blade.php -->

@extends('layouts.admin_layout')

@section('title', 'Enrollment')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Enrollment</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Section Assignment and Student Promotion</p>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Current school year:</span>
            <div class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY 2025–2026</span>
                <button class="text-slate-400 hover:text-slate-600 transition-colors">
                    <iconify-icon icon="solar:menu-dots-bold" width="14"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- ── Card Header ── --}}
        <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:clipboard-list-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Pending Section Assignment</h2>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 ml-6">Students approved for enrollment but not yet assigned to a section</p>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-4 rounded-2xl border border-yellow-200 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                    <iconify-icon icon="solar:user-bold" width="22" class="text-yellow-600 dark:text-yellow-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $pendingCount }}</p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1.5 leading-tight">Pending Section Assignment</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                    <iconify-icon icon="solar:door-open-bold" width="22" class="text-green-600 dark:text-green-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">0</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1.5 leading-tight">Available Sections</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/30">
                    <iconify-icon icon="solar:user-block-bold" width="22" class="text-red-600 dark:text-red-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">0</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 leading-tight">Full Sections</p>
                </div>
            </div>
        </div>

        {{-- ── Filters ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <form method="GET" action="{{ route('admin.enrollment.enroll') }}" class="flex flex-wrap items-center gap-2">
                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                    <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
                </span>
                <select name="level" class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-1.5 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Grade Levels</option>
                    <option value="Elementary"         {{ request('level')==='Elementary'        ?'selected':'' }}>Elementary</option>
                    <option value="Junior High School" {{ request('level')==='Junior High School'?'selected':'' }}>Junior High School</option>
                    <option value="Senior High School" {{ request('level')==='Senior High School'?'selected':'' }}>Senior High School</option>
                </select>
                <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-1.5 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                </button>
                <a href="{{ route('admin.enrollment.enroll') }}" class="rounded-lg border border-slate-200 px-4 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    Clear All
                </a>
            </form>
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" placeholder="Search..."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="table-layout:fixed;min-width:660px">
                <colgroup>
                    <col style="width:52px">
                    <col style="width:130px">
                    <col style="width:170px">
                    <col style="width:170px">
                    <col style="width:130px">
                    <col style="width:200px">
                </colgroup>
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3">
                            <div class="flex flex-col items-center gap-0.5">
                                <span class="text-xs text-slate-400 font-normal whitespace-nowrap">Select all</span>
                                <input type="checkbox" id="select-all" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </div>
                        </th>
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Grade Level Applied</th>
                        <th class="px-4 py-3">Enrollment Date</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @forelse ($students as $student)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" name="selected[]" value="{{ $student->id }}"
                                class="row-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 truncate">{{ $student->student_id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $student->full_name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $student->grade_level }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">
                            {{ $student->enrolled_at?->format('n/j/y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                   title="View Profile"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 transition-colors">
                                    <iconify-icon icon="solar:eye-bold" width="14"></iconify-icon>
                                </a>
                                <button type="button"
                                    onclick="openAssignModal({{ $student->id }}, '{{ addslashes($student->full_name) }}', '{{ $student->grade_level }}')"
                                    class="flex items-center gap-1.5 rounded-lg border border-blue-300 bg-blue-50 hover:bg-blue-100 text-blue-700 dark:border-blue-700 dark:bg-blue-900/20 dark:text-blue-400 px-2.5 py-1 text-xs font-medium transition-colors">
                                    <iconify-icon icon="solar:add-circle-linear" width="13"></iconify-icon>
                                    Assign Section
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center">
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

        {{-- ── Pagination ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>10</option><option>25</option><option>50</option>
                </select>
                <span>entries</span>
            </div>
            {{ $students->links() }}
        </div>

        {{-- ── Bulk Assign Bar ── --}}
        <div id="bulk-assign-bar" class="hidden items-center justify-between px-6 py-4 border-t border-blue-100 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/10">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:users-group-rounded-linear" width="16" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                <span id="selected-count" class="text-sm font-medium text-blue-700 dark:text-blue-300">0 student(s) selected</span>
            </div>
            <button type="button"
                class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-5 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                <iconify-icon icon="solar:add-circle-linear" width="16"></iconify-icon>
                Assign Section
            </button>
        </div>

    </div>

    {{-- ════════════════════════════════════════
         ASSIGN SECTION MODAL
    ════════════════════════════════════════ --}}
    <div id="assign-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAssignModal()"></div>
        <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
            <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-white text-sm font-bold">Assign Section</h3>
                    <p id="assign-student-name" class="text-blue-200 text-xs mt-0.5"></p>
                </div>
                <button onclick="closeAssignModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors text-sm">✕</button>
            </div>
            <form id="assign-form" method="POST" action="" class="px-6 py-5 space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" id="assign-student-id" name="student_id">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Grade Level</label>
                    <p id="assign-grade" class="text-sm font-semibold text-slate-700 dark:text-slate-300 px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800/40"></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Assign to Section <span class="text-red-500">*</span></label>
                    <select name="section_name" required
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Section</option>
                        <option>Section A</option>
                        <option>Section B</option>
                        <option>Section C</option>
                        <option>Section D</option>
                        <option>Section E</option>
                    </select>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                        Confirm Assignment
                    </button>
                    <button type="button" onclick="closeAssignModal()"
                        class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>

@push('scripts')
<script>
// ── Checkbox bulk bar ──
const selectAll     = document.getElementById('select-all');
const rowCheckboxes = document.querySelectorAll('.row-checkbox');
const bulkBar       = document.getElementById('bulk-assign-bar');
const countLabel    = document.getElementById('selected-count');

function updateBulkBar() {
    const checked = [...rowCheckboxes].filter(cb => cb.checked);
    if (checked.length > 0) {
        bulkBar.classList.remove('hidden');
        bulkBar.classList.add('flex');
        countLabel.textContent = checked.length + ' student' + (checked.length > 1 ? 's' : '') + ' selected';
    } else {
        bulkBar.classList.add('hidden');
        bulkBar.classList.remove('flex');
    }
    selectAll.checked       = checked.length === rowCheckboxes.length && rowCheckboxes.length > 0;
    selectAll.indeterminate = checked.length > 0 && checked.length < rowCheckboxes.length;
}

selectAll?.addEventListener('change', () => {
    rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkBar();
});

rowCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));

// ── Assign Section Modal ──
function openAssignModal(studentId, studentName, gradeLevel) {
    document.getElementById('assign-student-id').value = studentId;
    document.getElementById('assign-student-name').textContent = studentName;
    document.getElementById('assign-grade').textContent = gradeLevel;
    document.getElementById('assign-form').action = `/admin/enrollment/assign/${studentId}`;
    document.getElementById('assign-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAssignModal() {
    document.getElementById('assign-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endpush

@endsection