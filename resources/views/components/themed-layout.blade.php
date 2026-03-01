{{-- 
========================================
THEMED LAYOUT COMPONENT
========================================
Layout con sistema de temas para todos los componentes
Guardar en: resources/views/components/themed-layout.blade.php
======================================== 
--}}

@props([
    'title' => 'PARKI',
    'showThemeSwitcher' => true,
    'defaultTheme' => 'red',
    'storageKey' => 'appTheme',
])

<div>
    <div x-data="{ theme: $persist('{{ $defaultTheme }}').as('{{ $storageKey }}') }"
        :class="{
            'theme-red': theme === 'red',
            'theme-orange': theme === 'orange',
            'theme-blue': theme === 'blue'
        }"
        {{ $attributes->merge(['class' => 'min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-slate-100 dark:from-slate-900 dark:via-slate-900 dark:to-black']) }}
    >
        {{-- Theme Switcher (si está habilitado) --}}
        @if($showThemeSwitcher)
            <div class="fixed top-4 right-4 z-50 hidden sm:flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5 shadow-inner">
                <button @click="theme = 'red'"
                    :class="theme === 'red' ? 'bg-red-600 text-white shadow-lg scale-110' : 'text-slate-600 dark:text-slate-400 hover:text-red-600'"
                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                    title="Tema Rojo">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button @click="theme = 'orange'"
                    :class="theme === 'orange' ? 'bg-orange-600 text-white shadow-lg scale-110' : 'text-slate-600 dark:text-slate-400 hover:text-orange-600'"
                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                    title="Tema Naranja">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </button>
                <button @click="theme = 'blue'"
                    :class="theme === 'blue' ? 'bg-blue-600 text-white shadow-lg scale-110' : 'text-slate-600 dark:text-slate-400 hover:text-blue-600'"
                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                    title="Tema Azul">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Contenido --}}
        {{ $slot }}
    </div>

    {{-- Estilos de tema (una sola vez) --}}
    @once
        @include('livewire.customers.partials.theme-styles')
    @endonce
</div>
