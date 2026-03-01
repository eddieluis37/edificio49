@props([
    'action' => 'edit', // edit, delete, view, download, print, etc.
    'size' => 'md',
    'iconOnly' => false,
])

@php
    $actionConfig = [
        'edit' => [
            'color' => 'bg-theme-primary hover:bg-theme-primary-dark',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
            'label' => 'Editar',
        ],
        'delete' => [
            'color' => 'bg-red-600 hover:bg-red-700',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
            'label' => 'Eliminar',
        ],
        'view' => [
            'color' => 'bg-blue-600 hover:bg-blue-700',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>',
            'label' => 'Ver',
        ],
        'print' => [
            'color' => 'bg-purple-600 hover:bg-purple-700',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>',
            'label' => 'Imprimir',
        ],
        'download' => [
            'color' => 'bg-green-600 hover:bg-green-700',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>',
            'label' => 'Descargar',
        ],
    ];

    $config = $actionConfig[$action] ?? $actionConfig['edit'];
    
    $sizeClasses = [
        'sm' => 'p-2 min-h-[44px] min-w-[44px]',
        'md' => 'p-3 sm:p-4 min-h-[56px] min-w-[56px]',
        'lg' => 'p-4 sm:p-5 min-h-[64px] min-w-[64px]',
    ];

    $baseClasses = 'transform transition-transform duration-300 text-white rounded-xl shadow-lg hover:shadow-2xl active:scale-95 touch-manipulation inline-flex items-center justify-center';
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<button 
    {{ $attributes->merge(['class' => "$baseClasses $sizeClass {$config['color']}"]) }}
    title="{{ $config['label'] }}"
>
    @if($iconOnly)
        {!! $config['icon'] !!}
    @else
        {!! $config['icon'] !!}
        @if($slot->isNotEmpty())
            <span class="ml-2 hidden sm:inline">{{ $slot }}</span>
        @else
            <span class="ml-2 hidden sm:inline">{{ $config['label'] }}</span>
        @endif
    @endif
</button>
