@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 p-4 border border-emerald-200 shadow-lg shadow-emerald-500/10']) }}>
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-semibold text-emerald-800">{{ $status }}</p>
        </div>
    </div>
@endif
