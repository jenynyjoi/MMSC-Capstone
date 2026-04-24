@extends('layouts.admin_layout')

@section('title', 'Announcements')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
/* ── Quill editor sizing & border ─────────────────── */
.ql-container.ql-snow { border-radius: 0 0 0.5rem 0.5rem; border-color: #e2e8f0; font-size: 0.875rem; min-height: 180px; }
.ql-toolbar.ql-snow    { border-radius: 0.5rem 0.5rem 0 0; border-color: #e2e8f0; background: #f8fafc; flex-wrap: wrap; }
.ql-editor { min-height: 180px; line-height: 1.7; }
.ql-editor.ql-blank::before { color: #94a3b8; font-style: normal; }

/* ── Toolbar button sizing ─────────────────────────── */
.ql-toolbar.ql-snow .ql-formats { margin-right: 6px; }
.ql-toolbar.ql-snow button { width: 26px; height: 26px; padding: 3px; }
.ql-toolbar.ql-snow .ql-picker { height: 26px; }
.ql-toolbar.ql-snow .ql-picker-label { padding: 0 4px; }

/* ── Dark mode overrides ───────────────────────────── */
.dark .ql-toolbar.ql-snow    { background: #1a2744; border-color: #1e2d45; }
.dark .ql-container.ql-snow  { border-color: #1e2d45; background: #111827; }
.dark .ql-editor             { color: #e2e8f0; }
.dark .ql-editor.ql-blank::before { color: #475569; }
.dark .ql-snow .ql-stroke    { stroke: #94a3b8; }
.dark .ql-snow .ql-fill      { fill: #94a3b8; }
.dark .ql-snow .ql-picker-label { color: #94a3b8; border-color: #1e2d45; }
.dark .ql-snow .ql-picker-options { background: #111827; border-color: #1e2d45; }
.dark .ql-snow .ql-picker-item  { color: #cbd5e1; }
.dark .ql-toolbar.ql-snow button:hover .ql-stroke,
.dark .ql-toolbar.ql-snow button.ql-active .ql-stroke { stroke: #fff; }
.dark .ql-toolbar.ql-snow button:hover .ql-fill,
.dark .ql-toolbar.ql-snow button.ql-active .ql-fill { fill: #fff; }
.dark .ql-toolbar.ql-snow .ql-picker-label:hover,
.dark .ql-toolbar.ql-snow .ql-picker-label.ql-active { color: #fff; }

/* ── Rendered body HTML in view modal / cards ─────── */
.ann-body h1 { font-size:1.3rem; font-weight:700; margin-bottom:.4rem; }
.ann-body h2 { font-size:1.1rem; font-weight:700; margin-bottom:.3rem; }
.ann-body h3 { font-size:.95rem; font-weight:600; margin-bottom:.25rem; }
.ann-body p  { margin-bottom:.5rem; }
.ann-body strong { font-weight:700; }
.ann-body em     { font-style:italic; }
.ann-body u      { text-decoration:underline; }
.ann-body s      { text-decoration:line-through; }
.ann-body ul     { list-style:disc; padding-left:1.4rem; margin-bottom:.5rem; }
.ann-body ol     { list-style:decimal; padding-left:1.4rem; margin-bottom:.5rem; }
.ann-body blockquote { border-left:3px solid #0d4c8f; padding-left:.75rem; color:#64748b; font-style:italic; margin:.5rem 0; }
.ann-body a      { color:#0d4c8f; text-decoration:underline; }
.ann-body .ql-align-center { text-align:center; }
.ann-body .ql-align-right  { text-align:right; }
.ann-body .ql-align-justify{ text-align:justify; }
</style>
@endpush

@section('content')
@php
    $importanceDot = [
        'high'   => 'bg-red-500',
        'medium' => 'bg-yellow-400',
        'low'    => 'bg-green-400',
    ];
    $bannerGradient = [
        'high'   => 'from-[#0b2a5e] via-[#0d4c8f] to-[#1565c0]',
        'medium' => 'from-[#0d4c8f] via-[#1a6bb5] to-[#2d7dd2]',
        'low'    => 'from-[#1a4f72] via-[#1a6bb5] to-[#2e86c1]',
    ];
    $activeTab = request('tab', 'create');
@endphp

{{-- ═══════════════════════════════════════════════════════
     Single root Alpine component — all modal state lives here
     ═══════════════════════════════════════════════════════ --}}
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4"
     x-data="{
         activeTab: '{{ $activeTab }}',

         /* ── View modal ── */
         viewOpen: false,
         viewTitle: '',
         viewBody: '',
         viewPostedBy: '',
         viewDate: '',
         viewViewers: '',
         viewImportance: '',
         viewAttachmentName: '',
         viewAttachmentUrl: '',
         openView(ann) {
             this.viewTitle        = ann.title;
             this.viewBody         = ann.body;
             this.viewPostedBy     = ann.postedBy;
             this.viewDate         = ann.date;
             this.viewViewers      = ann.viewers;
             this.viewImportance   = ann.importance;
             this.viewAttachmentName = ann.attachmentName;
             this.viewAttachmentUrl  = ann.attachmentUrl;
             this.viewOpen         = true;
         },

         /* ── Edit modal ── */
         editOpen: false,
         editId: null,
         editTitle: '',
         editBody: '',
         editImportance: 'low',
         editViewers: [],
         editAttachmentName: '',
         openEdit(ann) {
             this.editId             = ann.id;
             this.editTitle          = ann.title;
             this.editBody           = ann.body;
             this.editImportance     = ann.importance;
             this.editViewers        = ann.viewers;
             this.editAttachmentName = ann.attachmentName;
             this.editOpen           = true;
             this.$nextTick(() => {
                 window.dispatchEvent(new CustomEvent('edit-quill-set', { detail: { body: ann.body || '' } }));
             });
         }
     }">

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
         class="mb-4 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <iconify-icon icon="solar:check-circle-bold" width="16"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── Page Header ── --}}
    <x-admin.page-header
        title="Announcements"
        subtitle="Manage and post school announcements."
        school-year="{{ $activeSchoolYear }}"
    />

    {{-- ── Main Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- ── Tabs ── --}}
        <div class="flex border-b border-slate-200 dark:border-dark-border">
            <button @click="activeTab = 'create'"
                :class="activeTab === 'create'
                    ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold'
                    : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700'"
                class="px-6 py-4 text-sm -mb-px transition-colors">
                Create Announcement
            </button>
            <button @click="activeTab = 'board'"
                :class="activeTab === 'board'
                    ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold'
                    : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700'"
                class="px-6 py-4 text-sm -mb-px transition-colors">
                Announcement Board
            </button>
        </div>

        {{-- ════════════════════════════════════ --}}
        {{-- TAB 1 — CREATE ANNOUNCEMENT         --}}
        {{-- ════════════════════════════════════ --}}
        <div x-show="activeTab === 'create'" x-transition.opacity>

            <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <iconify-icon icon="solar:megaphone-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Create Notice</h2>
            </div>

            <form id="create-ann-form" method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data"
                  class="px-8 py-6 space-y-6">
                @csrf

                @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- Title --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Announcement Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        placeholder="Title of Announcement"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border
                               bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                               px-4 py-2.5 text-sm text-slate-600 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                </div>

                {{-- Body --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Announcement Body</label>
                    <input type="hidden" name="body" id="create-body-input">
                    <div id="create-editor" class="rounded-lg"></div>
                </div>

                {{-- Attach File --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Attach File</label>
                    <div class="flex items-center gap-0 w-fit">
                        <label class="flex items-center justify-center rounded-l-lg border border-slate-300 dark:border-dark-border
                                      bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5
                                      px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300
                                      cursor-pointer transition-colors select-none">
                            Choose File
                            <input type="file" name="attachment" class="hidden" id="file-upload"
                                onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'No File Chosen'">
                        </label>
                        <div class="rounded-r-lg border border-l-0 border-slate-300 dark:border-dark-border
                                    bg-slate-50 dark:bg-slate-800/40 px-4 py-2 text-xs text-slate-400 dark:text-slate-500 min-w-[180px]">
                            <span id="file-name">No File Chosen</span>
                        </div>
                    </div>
                </div>

                {{-- Viewers --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Viewers</label>
                    <div class="relative w-64" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="w-full flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border
                                   bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                   px-4 py-2.5 text-sm text-slate-400 dark:text-slate-500
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            <span>Choose Viewers</span>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="text-slate-400"></iconify-icon>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute top-full left-0 z-20 mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                            @foreach(['All', 'Admin', 'Teachers', 'Students', 'Parents'] as $viewer)
                            <label class="flex items-center gap-2 px-4 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                <input type="checkbox" name="viewers[]" value="{{ $viewer }}"
                                    {{ in_array($viewer, old('viewers', [])) ? 'checked' : '' }}
                                    class="h-3.5 w-3.5 rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f]">
                                {{ $viewer }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Importance --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Importance</label>
                    <div class="flex items-center gap-3" x-data="{ imp: '{{ old('importance', 'low') }}' }">
                        <input type="hidden" name="importance" :value="imp">
                        @foreach(['low' => ['ring-green-400','bg-green-400','Low'], 'medium' => ['ring-yellow-400','bg-yellow-400','Medium'], 'high' => ['ring-red-500','bg-red-500','High']] as $val => $cfg)
                        <label class="flex items-center gap-1.5 cursor-pointer" title="{{ $cfg[2] }}">
                            <input type="radio" value="{{ $val }}" x-model="imp" class="hidden">
                            <span :class="imp === '{{ $val }}' ? 'ring-2 ring-offset-1 {{ $cfg[0] }}' : ''"
                                  class="flex h-6 w-6 items-center justify-center rounded-full {{ $cfg[1] }} transition-all"></span>
                        </label>
                        @endforeach
                        <span class="text-xs text-slate-400 dark:text-slate-500"
                              x-text="imp === 'low' ? 'Low' : imp === 'medium' ? 'Medium' : 'High'"></span>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-dark-border">

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="reset"
                        class="rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-6 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 transition-colors">
                        Reset
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-6 py-2 text-sm font-semibold text-white transition-colors">
                        Post
                    </button>
                </div>
            </form>
        </div>
        {{-- end create tab --}}


        {{-- ════════════════════════════════════ --}}
        {{-- TAB 2 — ANNOUNCEMENT BOARD          --}}
        {{-- ════════════════════════════════════ --}}
        <div x-show="activeTab === 'board'" x-transition.opacity>

            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:megaphone-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Announcement Board</h2>
                </div>
                <button @click="activeTab = 'create'"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors"
                    title="Create new announcement">
                    <iconify-icon icon="solar:add-circle-linear" width="18"></iconify-icon>
                </button>
            </div>

            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.announcements') }}"
                  class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <input type="hidden" name="tab" value="board">
                <input type="hidden" name="importance" id="importance-filter" value="{{ request('importance', '') }}">
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach(['All' => '', 'High' => 'high', 'Medium' => 'medium', 'Low' => 'low'] as $label => $val)
                    @php $dotColors = ['high' => 'bg-red-500', 'medium' => 'bg-yellow-400', 'low' => 'bg-green-400']; @endphp
                    <button type="button"
                        onclick="document.getElementById('importance-filter').value='{{ $val }}'; this.closest('form').submit();"
                        class="flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium border transition-colors
                            {{ request('importance', '') === $val
                                ? 'bg-[#0d4c8f] text-white border-[#0d4c8f]'
                                : 'bg-white dark:bg-dark-card border-slate-200 dark:border-dark-border text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5' }}">
                        @if($val)<span class="h-1.5 w-1.5 rounded-full {{ $dotColors[$val] }}"></span>@endif
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search announcements..."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
                </div>
            </form>

            {{-- Card Grid --}}
            @if($announcements->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                <iconify-icon icon="solar:megaphone-linear" width="48" class="mb-3 opacity-40"></iconify-icon>
                <p class="text-sm">No announcements yet.</p>
                <button @click="activeTab = 'create'" class="mt-3 text-xs text-[#0d4c8f] hover:underline font-medium">Post the first one</button>
            </div>
            @else
            <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($announcements as $ann)
                @php
                    $annJson = json_encode([
                        'id'             => $ann->id,
                        'title'          => $ann->title,
                        'body'           => $ann->body,
                        'postedBy'       => $ann->posted_by,
                        'date'           => $ann->created_at->format('M d, Y'),
                        'viewers'        => $ann->viewers ?? ['All'],
                        'importance'     => $ann->importance,
                        'attachmentName' => $ann->attachment ? basename($ann->attachment) : '',
                        'attachmentUrl'  => $ann->attachment ? Storage::url($ann->attachment) : '',
                    ]);
                @endphp
                <div class="group flex flex-col rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between px-4 pt-4 pb-2">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0d4c8f] text-white text-xs font-bold uppercase shrink-0">
                                {{ substr($ann->posted_by, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 leading-none">{{ $ann->posted_by }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $ann->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="h-3 w-3 rounded-full {{ $importanceDot[$ann->importance] }} shrink-0 ring-2 ring-white dark:ring-dark-card"
                              title="{{ ucfirst($ann->importance) }} importance"></span>
                    </div>

                    {{-- Banner --}}
                    <div class="mx-4 rounded-xl overflow-hidden relative bg-gradient-to-br {{ $bannerGradient[$ann->importance] }} h-[110px] flex flex-col justify-end p-3.5 select-none">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-400/25 rounded-bl-full"></div>
                        <div class="absolute top-3 right-4 w-5 h-5 bg-white/20 rotate-45 rounded-sm"></div>
                        <div class="absolute top-1 right-10 w-3 h-3 bg-white/10 rotate-12 rounded-sm"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/[0.04] rounded-tr-full"></div>
                        <div class="absolute bottom-3 left-3 w-8 h-8 border border-white/10 rounded-full"></div>
                        <p class="relative text-[8px] font-bold text-white/60 uppercase tracking-[0.15em] leading-none mb-1">Important</p>
                        <p class="relative text-[13px] font-extrabold text-white leading-snug line-clamp-2 drop-shadow-sm">{{ $ann->title }}</p>
                    </div>

                    {{-- Body preview --}}
                    <div class="px-4 pt-3 pb-1 flex-1">
                        <p class="text-[11.5px] text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-4">{{ Str::limit(strip_tags($ann->body), 220) }}</p>
                        <button type="button" @click="openView({{ $annJson }})"
                            class="mt-1.5 text-[11px] font-semibold text-[#0d4c8f] dark:text-blue-400 hover:underline">
                            See more
                        </button>
                    </div>

                    {{-- Attachment badge --}}
                    @if($ann->attachment)
                    <div class="px-4 pb-1 pt-0.5">
                        <a href="{{ Storage::url($ann->attachment) }}" target="_blank"
                           class="inline-flex items-center gap-1 rounded-full bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 px-2 py-0.5 text-[10px] font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-100 transition-colors max-w-full">
                            <iconify-icon icon="solar:file-text-linear" width="11" class="shrink-0"></iconify-icon>
                            <span class="truncate max-w-[160px]">{{ basename($ann->attachment) }}</span>
                        </a>
                    </div>
                    @endif

                    {{-- Viewers badge --}}
                    <div class="px-4 pb-2 pt-1">
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-white/10 px-2 py-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
                            <iconify-icon icon="solar:users-group-rounded-linear" width="11"></iconify-icon>
                            {{ implode(', ', $ann->viewers ?? ['All']) }}
                        </span>
                    </div>

                    <div class="mx-4 border-t border-slate-100 dark:border-dark-border"></div>

                    {{-- Action Row --}}
                    <div class="flex items-center justify-between px-4 py-2.5">
                        {{-- View --}}
                        <button type="button" title="View full announcement"
                            @click="openView({{ $annJson }})"
                            class="text-slate-400 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">
                            <iconify-icon icon="solar:eye-linear" width="17"></iconify-icon>
                        </button>

                        <div class="flex items-center gap-3">
                            {{-- Edit --}}
                            <button type="button" title="Edit"
                                @click="openEdit({{ $annJson }})"
                                class="text-slate-400 hover:text-green-500 dark:hover:text-green-400 transition-colors">
                                <iconify-icon icon="solar:pen-2-linear" width="17"></iconify-icon>
                            </button>
                            {{-- Delete --}}
                            <form method="POST"
                                  action="{{ route('admin.announcements.destroy', $ann->id) }}"
                                  onsubmit="return confirm('Delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete"
                                    class="text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                                    <iconify-icon icon="solar:trash-bin-trash-linear" width="17"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            @if($announcements->hasPages())
            <div class="flex items-center justify-end gap-1 px-6 py-5 border-t border-slate-100 dark:border-dark-border">
                {{ $announcements->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
            @endif
            @endif

        </div>
        {{-- end board tab --}}

    </div>
    {{-- end main card --}}


    {{-- ══════════════════════════════════════ --}}
    {{-- VIEW MODAL                             --}}
    {{-- ══════════════════════════════════════ --}}
    <div x-show="viewOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="viewOpen = false"></div>
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-lg rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-xl overflow-hidden">

            {{-- Modal banner --}}
            <div class="relative h-24 bg-gradient-to-br from-[#0b2a5e] via-[#0d4c8f] to-[#1565c0] flex flex-col justify-end p-4 select-none">
                <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-400/20 rounded-bl-full"></div>
                <div class="absolute top-3 right-5 w-5 h-5 bg-white/15 rotate-45 rounded-sm"></div>
                <p class="relative text-[8px] font-bold text-white/60 uppercase tracking-[0.15em] leading-none mb-1">Announcement</p>
                <p class="relative text-base font-extrabold text-white leading-snug drop-shadow-sm" x-text="viewTitle"></p>
                <button @click="viewOpen = false"
                    class="absolute top-3 right-3 flex h-7 w-7 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                    <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
                </button>
            </div>

            <div class="p-5 space-y-4 max-h-[60vh] overflow-y-auto">

                {{-- Meta row --}}
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0d4c8f] text-white text-xs font-bold uppercase shrink-0"
                             x-text="viewPostedBy.charAt(0)"></div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 leading-none" x-text="viewPostedBy"></p>
                            <p class="text-[10px] text-slate-400 mt-0.5" x-text="viewDate"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Importance badge --}}
                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold border"
                              :class="{
                                  'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800/30': viewImportance === 'high',
                                  'bg-yellow-50 text-yellow-600 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800/30': viewImportance === 'medium',
                                  'bg-green-50 text-green-600 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800/30': viewImportance === 'low'
                              }">
                            <span class="h-1.5 w-1.5 rounded-full inline-block"
                                  :class="{
                                      'bg-red-500': viewImportance === 'high',
                                      'bg-yellow-400': viewImportance === 'medium',
                                      'bg-green-400': viewImportance === 'low'
                                  }"></span>
                            <span x-text="viewImportance.charAt(0).toUpperCase() + viewImportance.slice(1)"></span>
                        </span>
                        {{-- Viewers badge --}}
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-white/10 px-2 py-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
                            <iconify-icon icon="solar:users-group-rounded-linear" width="11"></iconify-icon>
                            <span x-text="Array.isArray(viewViewers) ? viewViewers.join(', ') : viewViewers"></span>
                        </span>
                    </div>
                </div>

                {{-- Full body --}}
                <div class="ann-body rounded-xl border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-slate-800/30 px-5 py-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed" x-html="viewBody"></div>

                {{-- Attachment --}}
                <template x-if="viewAttachmentName">
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-1">Attachment</p>
                        <a :href="viewAttachmentUrl" target="_blank"
                           class="inline-flex items-center gap-2 rounded-lg border border-blue-100 dark:border-blue-800/30 bg-blue-50 dark:bg-blue-900/20 px-3 py-2 text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-100 transition-colors">
                            <iconify-icon icon="solar:file-download-linear" width="15"></iconify-icon>
                            <span x-text="viewAttachmentName"></span>
                        </a>
                    </div>
                </template>

            </div>

            <div class="flex justify-end gap-2 px-5 py-4 border-t border-slate-100 dark:border-dark-border">
                <button @click="viewOpen = false"
                    class="rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card px-5 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════ --}}
    {{-- EDIT MODAL                             --}}
    {{-- ══════════════════════════════════════ --}}
    <div x-show="editOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editOpen = false"></div>
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-lg rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-xl p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Edit Announcement</h3>
                <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <iconify-icon icon="solar:close-circle-linear" width="20"></iconify-icon>
                </button>
            </div>

            <form id="edit-ann-form" method="POST" :action="'/admin/announcements/' + editId" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Title</label>
                    <input type="text" name="title" x-model="editTitle"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Body --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Body</label>
                    <input type="hidden" name="body" id="edit-body-input">
                    <div id="edit-editor" class="rounded-lg"></div>
                </div>

                {{-- Replace attachment --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Attachment</label>
                    <template x-if="editAttachmentName">
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">
                            Current: <span class="font-medium text-slate-600 dark:text-slate-300" x-text="editAttachmentName"></span>
                        </p>
                    </template>
                    <div class="flex items-center gap-0 w-fit">
                        <label class="flex items-center justify-center rounded-l-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer transition-colors select-none">
                            Replace File
                            <input type="file" name="attachment" class="hidden" id="edit-file-upload"
                                onchange="document.getElementById('edit-file-name').textContent = this.files[0]?.name || 'No File Chosen'">
                        </label>
                        <div class="rounded-r-lg border border-l-0 border-slate-300 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-2 text-xs text-slate-400 dark:text-slate-500 min-w-[160px]">
                            <span id="edit-file-name">No File Chosen</span>
                        </div>
                    </div>
                </div>

                {{-- Viewers --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Viewers</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['All', 'Admin', 'Teachers', 'Students', 'Parents'] as $viewer)
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="viewers[]" value="{{ $viewer }}"
                                :checked="editViewers.includes('{{ $viewer }}')"
                                class="h-3.5 w-3.5 rounded border-slate-300 text-[#0d4c8f]">
                            {{ $viewer }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Importance --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Importance</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="importance" value="low" x-model="editImportance" class="h-3.5 w-3.5 accent-green-500">
                            <span class="h-3.5 w-3.5 rounded-full bg-green-400 inline-block"></span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Low</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="importance" value="medium" x-model="editImportance" class="h-3.5 w-3.5 accent-yellow-500">
                            <span class="h-3.5 w-3.5 rounded-full bg-yellow-400 inline-block"></span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Medium</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="importance" value="high" x-model="editImportance" class="h-3.5 w-3.5 accent-red-500">
                            <span class="h-3.5 w-3.5 rounded-full bg-red-500 inline-block"></span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">High</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="editOpen = false"
                        class="rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card px-5 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-sm font-semibold text-white transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const TOOLBAR = [
    [{ 'header': [1, 2, 3, false] }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'align': [] }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    ['blockquote', 'link'],
    ['clean'],
];

// ── Create form editor ─────────────────────────────
const createQuill = new Quill('#create-editor', {
    theme: 'snow',
    placeholder: 'Write your announcement here…',
    modules: { toolbar: TOOLBAR },
});

@if(old('body'))
createQuill.clipboard.dangerouslyPasteHTML('{!! addslashes(old('body')) !!}');
@endif

document.getElementById('create-ann-form').addEventListener('formdata', function (e) {
    e.formData.set('body', createQuill.root.innerHTML);
});
document.getElementById('create-ann-form').addEventListener('submit', function () {
    document.getElementById('create-body-input').value = createQuill.root.innerHTML;
});

// ── Edit modal editor ──────────────────────────────
const editQuill = new Quill('#edit-editor', {
    theme: 'snow',
    placeholder: 'Edit announcement body…',
    modules: { toolbar: TOOLBAR },
});

window.addEventListener('edit-quill-set', function (e) {
    editQuill.clipboard.dangerouslyPasteHTML(e.detail.body || '');
});

document.getElementById('edit-ann-form').addEventListener('submit', function () {
    document.getElementById('edit-body-input').value = editQuill.root.innerHTML;
});
</script>
@endpush
