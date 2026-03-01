<div>
    <div>
        <div x-data="{ theme: $persist('red').as('ratesTheme') }"
            :class="{
                'theme-red': theme === 'red',
                'theme-orange': theme === 'orange',
                'theme-blue': theme === 'blue'
            }"
            class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-slate-100 dark:from-slate-900 dark:via-slate-900 dark:to-black">

            {{-- HEADER CON ESTADÍSTICAS Y THEME SWITCHER --}}
            <div
                class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <div>
                            <h1
                                class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-slate-800 via-theme-primary to-theme-secondary bg-clip-text text-transparent dark:from-slate-100">
                                💵 Tarifas del Estacionamiento
                            </h1>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Gestiona los precios por tipo de vehículo y tiempo
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Theme Switcher --}}
                            <div
                                class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5 shadow-inner">
                                <button @click="theme = 'red'"
                                    :class="theme === 'red' ? 'bg-red-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-red-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                                    title="Tema Rojo">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="theme = 'orange'"
                                    :class="theme === 'orange' ? 'bg-orange-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-orange-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                                    title="Tema Naranja">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                                <button @click="theme = 'blue'"
                                    :class="theme === 'blue' ? 'bg-blue-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-blue-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                                    title="Tema Azul">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <button wire:click="create"
                                class="group relative inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 font-bold text-white transition-all duration-300 ease-out bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary rounded-2xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 touch-manipulation min-h-[56px]">
                                <span
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-theme-secondary via-theme-primary to-slate-900 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                <svg class="w-6 h-6 mr-2 relative z-10" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="relative z-10 text-base sm:text-lg">Nueva Tarifa</span>
                            </button>
                        </div>
                    </div>

                    {{-- Estadísticas Grid --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div
                            class="bg-gradient-to-br from-theme-primary to-theme-primary-dark rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white/80 text-xs sm:text-sm font-medium">Total Tarifas</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['total_rates'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-xs sm:text-sm font-medium">Activas</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['active_rates'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-xs sm:text-sm font-medium">Por Hora</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['hourly_rates'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-xs sm:text-sm font-medium">Mensuales</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['monthly_rates'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Barra de Búsqueda y Filtros --}}
                    <div class="mt-4 space-y-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="🔍 Buscar por descripción..."
                                class="w-full px-6 py-4 sm:py-5 pl-14 pr-12 text-base sm:text-lg bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 shadow-lg hover:shadow-xl min-h-[56px] touch-manipulation">
                            <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-theme-primary"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            @if ($search)
                                <button wire:click="clearSearch"
                                    class="absolute right-5 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <select wire:model.live="filter_type"
                                class="px-5 py-4 text-base bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 shadow-lg min-h-[56px] touch-manipulation">
                                <option value="all">📋 Todos los tipos</option>
                                <option value="hourly">⏱️ Por Hora</option>
                                <option value="daily">📅 Diaria</option>
                                <option value="monthly">🗓️ Mensual</option>
                                <option value="fractional">⚡ Fraccionada</option>
                            </select>

                            <select wire:model.live="filter_status"
                                class="px-5 py-4 text-base bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 shadow-lg min-h-[56px] touch-manipulation">
                                <option value="all">🔄 Todos los estados</option>
                                <option value="active">✅ Activas</option>
                                <option value="inactive">❌ Inactivas</option>
                            </select>
                        </div>

                        @if ($search || $filter_type !== 'all' || $filter_status !== 'all')
                            <button wire:click="clearFilters"
                                class="text-sm text-theme-primary hover:text-theme-primary-dark font-medium transition-colors">🗑️
                                Limpiar filtros</button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- GRID DE TARIFAS --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                @if ($rates->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($rates as $rate)
                            <div wire:key="rate-{{ $rate->id }}"
                                class="group relative bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-theme-primary/30 hover:scale-105">
                                <div class="absolute top-3 right-3 z-20">
                                    @if ($rate->rate_type === 'hourly')
                                        <span
                                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1.5 rounded-full text-xs sm:text-sm font-bold shadow-lg">⏱️
                                            Horaria</span>
                                    @elseif($rate->rate_type === 'daily')
                                        <span
                                            class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1.5 rounded-full text-xs sm:text-sm font-bold shadow-lg">📅
                                            Diaria</span>
                                    @elseif($rate->rate_type === 'monthly')
                                        <span
                                            class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 px-3 py-1.5 rounded-full text-xs sm:text-sm font-bold shadow-lg">🗓️
                                            Mensual</span>
                                    @elseif($rate->rate_type === 'fractional')
                                        <span
                                            class="bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 px-3 py-1.5 rounded-full text-xs sm:text-sm font-bold shadow-lg">⚡
                                            Fracción</span>
                                    @endif
                                </div>

                                <div class="absolute top-3 left-3 z-20">
                                    <button wire:click="toggleActive({{ $rate->id }})"
                                        class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-300 {{ $rate->active ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200' : 'bg-slate-200 dark:bg-slate-700 text-slate-400 hover:bg-slate-300' }} shadow-lg">
                                        @if ($rate->active)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                </div>

                                <div
                                    class="relative h-40 sm:h-48 overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center">
                                    <div class="text-6xl sm:text-7xl opacity-20">💵</div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-6 gap-3">
                                        <button wire:click="edit({{ $rate->id }})"
                                            class="transform translate-y-8 group-hover:translate-y-0 transition-transform duration-300 bg-theme-primary hover:bg-theme-primary-dark text-white p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-2xl active:scale-95 min-h-[56px] min-w-[56px] touch-manipulation"
                                            title="Editar">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $rate->id }})"
                                            wire:confirm="¿Estás seguro de eliminar esta tarifa?"
                                            class="transform translate-y-8 group-hover:translate-y-0 transition-transform duration-300 delay-75 bg-red-600 hover:bg-red-700 text-white p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-2xl active:scale-95 min-h-[56px] min-w-[56px] touch-manipulation"
                                            title="Eliminar">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-5 sm:p-6">
                                    <div
                                        class="flex items-center text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5 text-theme-primary" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                        <span class="font-medium">{{ $rate->type->name ?? 'Sin tipo' }}</span>
                                    </div>
                                    <h3
                                        class="text-lg sm:text-xl font-bold text-slate-800 dark:text-white group-hover:text-theme-primary transition-colors mb-3">
                                        {{ $rate->description }}</h3>
                                    <div class="mb-3">
                                        <div class="flex items-baseline">
                                            <span
                                                class="text-3xl sm:text-4xl font-bold text-theme-primary">{{ $rate->formatted_price }}</span>
                                            <span class="ml-2 text-sm text-slate-500 dark:text-slate-400">/
                                                {{ $rate->formatted_time }}</span>
                                        </div>
                                    </div>
                                    @if ($rate->time)
                                        <div
                                            class="pt-3 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                                            <span>Precio/min:</span>
                                            <span
                                                class="font-bold">${{ number_format($rate->price_per_minute, 4) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $rates->links() }}</div>
                @else
                    <div class="text-center py-16 sm:py-20">
                        <div
                            class="inline-block p-8 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-full mb-6">
                            <svg class="w-20 h-20 sm:w-24 sm:h-24 text-theme-primary" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-white mb-3">
                            @if ($search || $filter_type !== 'all' || $filter_status !== 'all')
                                No se encontraron resultados
                            @else
                                No hay tarifas registradas
                            @endif
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6 text-base sm:text-lg">
                            @if ($search || $filter_type !== 'all' || $filter_status !== 'all')
                                Intenta con otros filtros de búsqueda
                            @else
                                Comienza creando tu primera tarifa
                            @endif
                        </p>
                        @if (!$search && $filter_type === 'all' && $filter_status === 'all')
                            <button wire:click="create"
                                class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-2xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 min-h-[56px] touch-manipulation">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Primera Tarifa
                            </button>
                        @endif
                    </div>
                @endif
            </div>

            {{-- SLIDE-OVER PANEL --}}
            <div x-data="{ open: @entangle('slideOverOpen') }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-hidden"
                @keydown.escape.window="open = false">
                <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-500"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                        class="w-screen max-w-md sm:max-w-lg lg:max-w-xl">
                        <div class="flex h-full flex-col bg-white dark:bg-slate-900 shadow-2xl overflow-y-auto">
                            <div
                                class="bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary px-6 py-6 sm:px-8 sm:py-8 flex-shrink-0">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl sm:text-3xl font-bold text-white">
                                        {{ $isEditMode ? '✏️ Editar Tarifa' : '➕ Nueva Tarifa' }}</h2>
                                    <button wire:click="closeSlideOver"
                                        class="text-white hover:text-slate-200 transition-colors p-2 hover:bg-white/20 rounded-xl min-h-[48px] min-w-[48px] touch-manipulation">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <form wire:submit.prevent="store" class="flex-1 overflow-y-auto">
                                <div class="px-6 py-6 sm:px-8 space-y-6">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tipo
                                            de Vehículo *</label>
                                        <select wire:model="type_id"
                                            class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('type_id') border-red-500 @enderror">
                                            <option value="0">Selecciona un tipo</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_id')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                                    class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descripción
                                            *</label>
                                        <input type="text" wire:model="description"
                                            placeholder="Ej: Tarifa estándar por hora"
                                            class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('description') border-red-500 @enderror">
                                        @error('description')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                                    class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tipo
                                            de Tarifa *</label>
                                        <select wire:model.live="rate_type"
                                            class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('rate_type') border-red-500 @enderror">
                                            @foreach ($rateTypes as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('rate_type')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                                    class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Precio
                                            *</label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-5 top-1/2 transform -translate-y-1/2 text-slate-500 text-lg font-bold">$</span>
                                            <input type="number" step="0.01" min="0.01" wire:model="price"
                                                placeholder="0.00"
                                                class="w-full pl-10 pr-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('price') border-red-500 @enderror">
                                        </div>
                                        @error('price')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                                    class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label
                                                class="block text-sm font-bold text-slate-700 dark:text-slate-300">Tiempo
                                                (minutos)</label>
                                            <button wire:click.prevent="setDefaultTime" type="button"
                                                class="text-xs text-theme-primary hover:text-theme-primary-dark font-medium transition-colors">Usar
                                                tiempo por defecto</button>
                                        </div>
                                        <input type="number" step="1" min="1" wire:model="time"
                                            placeholder="60"
                                            class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('time') border-red-500 @enderror">
                                        @if ($time)
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Equivale a:
                                                {{ $this->formatMinutes($time) }}</p>
                                        @endif
                                        @error('time')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                                    class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input wire:model="active" id="active" type="checkbox"
                                                class="h-5 w-5 rounded border-slate-300 text-theme-primary focus:ring-theme-primary focus:ring-4 focus:ring-theme-primary/30 transition-all">
                                        </div>
                                        <div class="ml-3">
                                            <label for="active"
                                                class="text-sm font-bold text-slate-700 dark:text-slate-300">Tarifa
                                                activa</label>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Esta tarifa estará
                                                disponible para cálculos de renta</p>
                                        </div>
                                    </div>

                                    @if ($price && $time)
                                        <div
                                            class="rounded-xl bg-blue-50 dark:bg-blue-900/20 p-4 border-2 border-blue-200 dark:border-blue-800">
                                            <div class="flex">
                                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                                        Vista Previa de Cálculo</p>
                                                    <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">Precio por
                                                        minuto: <strong
                                                            class="font-bold">${{ number_format($price / $time, 4) }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div
                                    class="border-t border-slate-200 dark:border-slate-700 px-6 py-6 sm:px-8 bg-slate-50 dark:bg-slate-800/50 flex-shrink-0">
                                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                        <button type="button" wire:click="closeSlideOver"
                                            class="w-full sm:w-auto px-6 py-4 bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95 min-h-[56px] touch-manipulation">Cancelar</button>
                                        <button type="submit"
                                            class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 min-h-[56px] touch-manipulation"
                                            wire:loading.attr="disabled" wire:target="store">
                                            <span wire:loading.remove
                                                wire:target="store">{{ $isEditMode ? '💾 Actualizar' : '✨ Crear Tarifa' }}</span>
                                            <span wire:loading wire:target="store"
                                                class="flex items-center justify-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                Guardando...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Livewire.on('notify', (data) => {
                            const type = data[0].type || 'info';
                            const message = data[0].message || 'Notificación';
                            if (type === 'success') {
                                showToast('✅ ' + message, 'success');
                            } else if (type === 'error') {
                                showToast('❌ ' + message, 'error');
                            }
                        });

                        function showToast(message, type) {
                            const toast = document.createElement('div');
                            toast.className =
                                `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl text-white font-bold transform transition-all duration-300 ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'}`;
                            toast.textContent = message;
                            document.body.appendChild(toast);
                            setTimeout(() => {
                                toast.style.transform = 'translateX(400px)';
                                setTimeout(() => toast.remove(), 300);
                            }, 3000);
                        }
                    });
                </script>
            @endpush
        </div>

        <style>
            .theme-red {
                --theme-primary: #ef4444;
                --theme-primary-dark: #dc2626;
                --theme-secondary: #991b1b;
                --theme-secondary-dark: #7f1d1d;
            }

            .theme-red .from-theme-primary {
                background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important;
                --tw-gradient-from: #ef4444;
                --tw-gradient-to: rgb(239 68 68 / 0);
                --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
            }

            .theme-red .to-theme-primary-dark {
                --tw-gradient-to: #dc2626;
            }

            .theme-red .from-theme-secondary {
                --tw-gradient-from: #991b1b;
            }

            .theme-red .to-theme-secondary-dark {
                --tw-gradient-to: #7f1d1d;
            }

            .theme-red .via-theme-primary {
                --tw-gradient-to: rgb(239 68 68 / 0);
                --tw-gradient-stops: var(--tw-gradient-from), #ef4444, var(--tw-gradient-to);
            }

            .theme-red .via-theme-secondary {
                --tw-gradient-stops: var(--tw-gradient-from), #991b1b, var(--tw-gradient-to);
            }

            .theme-red .text-theme-primary {
                color: #ef4444 !important;
            }

            .theme-red .bg-theme-primary {
                background-color: #ef4444 !important;
            }

            .theme-red .border-theme-primary {
                border-color: #ef4444 !important;
            }

            .theme-red .ring-theme-primary {
                --tw-ring-color: #ef4444 !important;
            }

            .theme-red .hover\:text-theme-primary:hover {
                color: #ef4444 !important;
            }

            .theme-red .hover\:bg-theme-primary:hover {
                background-color: #ef4444 !important;
            }

            .theme-red .hover\:bg-theme-primary-dark:hover {
                background-color: #dc2626 !important;
            }

            .theme-red .focus\:ring-theme-primary:focus {
                --tw-ring-color: #ef4444 !important;
            }

            .theme-red .focus\:border-theme-primary:focus {
                border-color: #ef4444 !important;
            }

            .theme-red .hover\:text-theme-primary-dark:hover {
                color: #dc2626 !important;
            }

            .theme-orange {
                --theme-primary: #f97316;
                --theme-primary-dark: #ea580c;
                --theme-secondary: #c2410c;
                --theme-secondary-dark: #9a3412;
            }

            .theme-orange .from-theme-primary {
                background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important;
                --tw-gradient-from: #f97316;
                --tw-gradient-to: rgb(249 115 22 / 0);
                --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
            }

            .theme-orange .to-theme-primary-dark {
                --tw-gradient-to: #ea580c;
            }

            .theme-orange .from-theme-secondary {
                --tw-gradient-from: #c2410c;
            }

            .theme-orange .to-theme-secondary-dark {
                --tw-gradient-to: #9a3412;
            }

            .theme-orange .via-theme-primary {
                --tw-gradient-to: rgb(249 115 22 / 0);
                --tw-gradient-stops: var(--tw-gradient-from), #f97316, var(--tw-gradient-to);
            }

            .theme-orange .via-theme-secondary {
                --tw-gradient-stops: var(--tw-gradient-from), #c2410c, var(--tw-gradient-to);
            }

            .theme-orange .text-theme-primary {
                color: #f97316 !important;
            }

            .theme-orange .bg-theme-primary {
                background-color: #f97316 !important;
            }

            .theme-orange .border-theme-primary {
                border-color: #f97316 !important;
            }

            .theme-orange .ring-theme-primary {
                --tw-ring-color: #f97316 !important;
            }

            .theme-orange .hover\:text-theme-primary:hover {
                color: #f97316 !important;
            }

            .theme-orange .hover\:bg-theme-primary:hover {
                background-color: #f97316 !important;
            }

            .theme-orange .hover\:bg-theme-primary-dark:hover {
                background-color: #ea580c !important;
            }

            .theme-orange .focus\:ring-theme-primary:focus {
                --tw-ring-color: #f97316 !important;
            }

            .theme-orange .focus\:border-theme-primary:focus {
                border-color: #f97316 !important;
            }

            .theme-orange .hover\:text-theme-primary-dark:hover {
                color: #ea580c !important;
            }

            .theme-blue {
                --theme-primary: #3b82f6;
                --theme-primary-dark: #2563eb;
                --theme-secondary: #1e40af;
                --theme-secondary-dark: #1e3a8a;
            }

            .theme-blue .from-theme-primary {
                background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important;
                --tw-gradient-from: #3b82f6;
                --tw-gradient-to: rgb(59 130 246 / 0);
                --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
            }

            .theme-blue .to-theme-primary-dark {
                --tw-gradient-to: #2563eb;
            }

            .theme-blue .from-theme-secondary {
                --tw-gradient-from: #1e40af;
            }

            .theme-blue .to-theme-secondary-dark {
                --tw-gradient-to: #1e3a8a;
            }

            .theme-blue .via-theme-primary {
                --tw-gradient-to: rgb(59 130 246 / 0);
                --tw-gradient-stops: var(--tw-gradient-from), #3b82f6, var(--tw-gradient-to);
            }

            .theme-blue .via-theme-secondary {
                --tw-gradient-stops: var(--tw-gradient-from), #1e40af, var(--tw-gradient-to);
            }

            .theme-blue .text-theme-primary {
                color: #3b82f6 !important;
            }

            .theme-blue .bg-theme-primary {
                background-color: #3b82f6 !important;
            }

            .theme-blue .border-theme-primary {
                border-color: #3b82f6 !important;
            }

            .theme-blue .ring-theme-primary {
                --tw-ring-color: #3b82f6 !important;
            }

            .theme-blue .hover\:text-theme-primary:hover {
                color: #3b82f6 !important;
            }

            .theme-blue .hover\:bg-theme-primary:hover {
                background-color: #3b82f6 !important;
            }

            .theme-blue .hover\:bg-theme-primary-dark:hover {
                background-color: #2563eb !important;
            }

            .theme-blue .focus\:ring-theme-primary:focus {
                --tw-ring-color: #3b82f6 !important;
            }

            .theme-blue .focus\:border-theme-primary:focus {
                border-color: #3b82f6 !important;
            }

            .theme-blue .hover\:text-theme-primary-dark:hover {
                color: #2563eb !important;
            }
        </style>
    </div>
</div>
