<div>
    <div>
        <div x-data="{ theme: $persist('red').as('cashClosuresTheme') }"
            :class="{
                'theme-red': theme === 'red',
                'theme-orange': theme === 'orange',
                'theme-blue': theme === 'blue'
            }"
            class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-slate-100 dark:from-slate-900 dark:via-slate-900 dark:to-black">

            {{-- HEADER STICKY --}}
            <div
                class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div>
                                <h1
                                    class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-slate-800 via-theme-primary to-theme-secondary bg-clip-text text-transparent dark:from-slate-100">
                                    💰 Corte de Caja
                                </h1>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Arqueos y control de ingresos
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Theme Switcher --}}
                            <div
                                class="hidden sm:flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5 shadow-inner">
                                <button @click="theme = 'red'"
                                    :class="theme === 'red' ? 'bg-red-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-red-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px]">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="theme = 'orange'"
                                    :class="theme === 'orange' ? 'bg-orange-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-orange-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px]">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                                <button @click="theme = 'blue'"
                                    :class="theme === 'blue' ? 'bg-blue-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-blue-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px]">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Botón Exportar PDF (Premium) --}}
                            <button wire:click="exportPdf"
                                class="hidden sm:flex items-center gap-2 px-4 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>PDF 💎</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                {{-- FILTROS --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg mb-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        {{-- Usuario --}}
                        <div class="flex-1">
                            <label
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Usuario/Cajero</label>
                            <select wire:model.live="filter_user_id"
                                class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900">
                                <option value="">Todos los usuarios</option>
                                @foreach ($this->users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Período --}}
                        <div class="flex-1">
                            <label
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Período</label>
                            <select wire:model.live="filter_period"
                                class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900">
                                <option value="today">Hoy</option>
                                <option value="week">Esta Semana</option>
                                <option value="month">Este Mes</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>

                        @if ($filter_period === 'custom')
                            {{-- Fecha Desde --}}
                            <div class="flex-1">
                                <label
                                    class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Desde</label>
                                <input type="datetime-local" wire:model.live="date_from"
                                    class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900">
                            </div>

                            {{-- Fecha Hasta --}}
                            <div class="flex-1">
                                <label
                                    class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Hasta</label>
                                <input type="datetime-local" wire:model.live="date_to"
                                    class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900">
                            </div>
                        @endif

                        {{-- Botón Limpiar --}}
                        <div class="flex items-end">
                            <button wire:click="clearFilters"
                                class="px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-all">
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ESTADÍSTICAS DEL PERÍODO --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Total Ingresos --}}
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm opacity-90 mb-1">Total Ingresos</p>
                        <p class="text-4xl font-bold">${{ number_format($this->stats['total_income'], 2) }}</p>
                    </div>

                    {{-- Total Transacciones --}}
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm opacity-90 mb-1">Transacciones</p>
                        <p class="text-4xl font-bold">{{ $this->stats['total_rentals'] }}</p>
                    </div>

                    {{-- Promedio por Renta --}}
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm opacity-90 mb-1">Promedio por Renta</p>
                        <p class="text-4xl font-bold">${{ number_format($this->stats['average_per_rental'], 2) }}</p>
                    </div>

                    {{-- Tickets Abiertos (Alerta) --}}
                    <div
                        class="bg-gradient-to-br {{ $this->stats['has_open_tickets'] ? 'from-red-500 to-red-600' : 'from-slate-500 to-slate-600' }} rounded-2xl p-6 text-white shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm opacity-90 mb-1">Tickets Abiertos</p>
                        <p class="text-4xl font-bold">{{ $this->stats['open_tickets'] }}</p>
                        @if ($this->stats['has_open_tickets'])
                            <p class="text-xs mt-2 opacity-90">⚠️ Hay tickets sin cerrar</p>
                        @endif
                    </div>
                </div>

                {{-- BOTÓN HACER CORTE --}}
                <div class="mb-6">
                    <button wire:click="openClosureModal"
                        class="w-full py-8 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold text-2xl rounded-2xl shadow-2xl hover:shadow-3xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <div class="flex items-center justify-center gap-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                                </path>
                            </svg>
                            <span>💰 Hacer Corte de Caja</span>
                        </div>
                        <p class="text-sm opacity-90 mt-2">{{ $this->getPeriodName() }} -
                            {{ $filter_user_id ? $this->users->find($filter_user_id)->name : 'Todos los usuarios' }}</p>
                    </button>
                </div>

                {{-- HISTORIAL DE CORTES --}}
                @if ($this->recentClosures->count() > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-theme-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            Historial Reciente
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 dark:bg-slate-900">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Fecha</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Usuario</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Transacciones</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Total Ingresos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach ($this->recentClosures as $closure)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">
                                                {{ $closure['period'] }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">
                                                {{ $closure['user']->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-slate-700 dark:text-slate-300">
                                                {{ $closure['total_rentals'] }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">
                                                ${{ number_format($closure['total_income'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>

            {{-- MODAL DE CORTE --}}
            @include('livewire.closures.partials.closure-modal')

        </div>

        {{-- ESTILOS Y SCRIPTS --}}
        @include('livewire.closures.partials.theme-styles')
    </div>
</div>
