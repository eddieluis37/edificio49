@props([
    'type' => 'button',
    'color' => 'primary', // primary, secondary, success, danger, warning
    'size' => 'md', // sm, md, lg, xl
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'loading' => false,
    'loadingText' => 'Procesando...',
])

@php
    // Definir clases base según tamaño
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm min-h-[44px]',
        'md' => 'px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg min-h-[56px]',
        'lg' => 'px-8 sm:px-10 py-4 sm:py-5 text-lg sm:text-xl min-h-[64px]',
        'xl' => 'px-10 sm:px-12 py-5 sm:py-6 text-xl sm:text-2xl min-h-[72px]',
    ];

    // Definir colores según tipo
    $colorClasses = [
        'primary' => 'bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary hover:from-theme-secondary hover:via-theme-primary hover:to-slate-900',
        'secondary' => 'bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800',
        'success' => 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700',
        'danger' => 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800',
        'warning' => 'bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700',
    ];

    $baseClasses = 'group relative inline-flex items-center justify-center font-bold text-white transition-all duration-300 ease-out rounded-2xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 touch-manipulation disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100';
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $colorClass = $colorClasses[$color] ?? $colorClasses['primary'];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $sizeClass $colorClass"]) }}
    @if($loading) disabled @endif
>
    {{-- Efecto de hover (overlay) --}}
    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-white/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
    
    {{-- Loading State --}}
    @if($loading)
        <span class="relative z-10 flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ $loadingText }}
        </span>
    @else
        {{-- Icon Left --}}
        @if($icon && $iconPosition === 'left')
            <span class="relative z-10 mr-2">
                {!! $icon !!}
            </span>
        @endif

        {{-- Content --}}
        <span class="relative z-10">
            {{ $slot }}
        </span>

        {{-- Icon Right --}}
        @if($icon && $iconPosition === 'right')
            <span class="relative z-10 ml-2">
                {!! $icon !!}
            </span>
        @endif
    @endif
</button>
