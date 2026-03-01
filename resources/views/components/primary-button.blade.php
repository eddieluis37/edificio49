@props([
    'icon' => null,
    'iconPosition' => 'left',
    'size' => 'md',
])

@php
    // Tamaños
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm min-h-[44px]',
        'md' => 'px-6 py-3 text-base min-h-[52px]',
        'lg' => 'px-8 py-4 text-lg min-h-[56px]',
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<button
    {{ $attributes->merge(['class' => "$sizeClass bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all touch-manipulation"]) }}>
    <span class="flex items-center justify-center gap-2">
        @if ($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif

        <span>{{ $slot }}</span>

        @if ($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </span>
</button>
