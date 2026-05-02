@props([
    'title'      => '',
    'subtitle'   => '',
    'schoolYear' => null,
    'showMenu'   => false,
])

<div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
    <div>
        @isset($breadcrumb)
            {{ $breadcrumb }}
        @endisset
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        {{-- Custom right-side content passed via default slot --}}
        {{ $slot }}
    @elseif($schoolYear !== null)
        {{-- Standard school year badge --}}
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Current school year:</span>
            <div class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY {{ $schoolYear }}</span>
                @if($showMenu)
                    <button class="text-slate-400 hover:text-slate-600 transition-colors">
                        <iconify-icon icon="solar:menu-dots-bold" width="14"></iconify-icon>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
