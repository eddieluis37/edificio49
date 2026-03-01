@props([
    'type' => 'button',
    'size' => 'md',
    'variant' => 'outline', // outline, ghost, light
    'icon' => null,
])

@php
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm min-h-[44px]',
        'md' => 'px-6 py-4 text-base min-h-[56px]',
        'lg' => 'px-8 py-5 text-lg min-h-[64px]',
    ];

    $variantClasses = [
        'outline' => 'bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700',
        'ghost' => 'bg-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800',
        'light' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700',
    ];

    $baseClasses = 'inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95 touch-manipulation disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $variantClass = $variantClasses[$variant] ?? $variantClasses['outline'];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $sizeClass $variantClass"]) }}
>
    @if($icon)
        <span class="mr-2">{!! $icon !!}</span>
    @endif
    
    {{ $slot }}
</button>
