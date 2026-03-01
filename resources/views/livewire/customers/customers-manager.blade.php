<div>
    <div>
        <div x-data="{ theme: $persist('red').as('customersTheme') }"
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
                                    👥 Gestión de Colaboradores
                                </h1>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Administra base de
                                    colaborador</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Theme Switcher --}}
                            <div
                                class="hidden sm:flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5 shadow-inner">
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
                            </div>

                            {{-- Botón Crear --}}
                            <button wire:click="create"
                                class="px-6 py-3 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all min-h-[52px] touch-manipulation">
                                <span class="hidden sm:inline">+ Nuevo Colaborador</span>
                                <span class="sm:hidden">+</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                {{-- ESTADÍSTICAS --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Colaborador</p>
                                <p class="text-3xl font-bold text-theme-primary mt-1">
                                    {{ $this->stats['total_customers'] }}</p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-xl p-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Activos</p>
                                <p class="text-3xl font-bold text-green-600 mt-1">{{ $this->stats['active_customers'] }}
                                </p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900/30 rounded-xl p-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Con Vehículos</p>
                                <p class="text-3xl font-bold text-purple-600 mt-1">
                                    {{ $this->stats['customers_with_vehicles'] }}</p>
                            </div>
                            <div class="bg-purple-100 dark:bg-purple-900/30 rounded-xl p-3">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Con Rentas</p>
                                <p class="text-3xl font-bold text-orange-600 mt-1">
                                    {{ $this->stats['customers_with_rentals'] }}</p>
                            </div>
                            <div class="bg-orange-100 dark:bg-orange-900/30 rounded-xl p-3">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FILTROS Y BÚSQUEDA --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-6 shadow-lg mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- Búsqueda --}}
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    placeholder="Buscar por nombre, email o teléfono..."
                                    class="w-full pl-12 pr-12 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 min-h-[52px] touch-manipulation">
                                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                @if ($search)
                                    <button wire:click="clearSearch"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Filtro Estado --}}
                        <select wire:model.live="filter_status"
                            class="px-6 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900 focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary min-h-[52px] touch-manipulation">
                            <option value="all">Todos los estados</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>
                    </div>
                </div>

                {{-- GRID DE CARDS --}}
                @if ($this->customers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach ($this->customers as $customer)
                            <div
                                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group">
                                {{-- Card Header --}}
                                <div
                                    class="bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary p-6 text-white">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold mb-1">{{ $customer->name }}</h3>
                                            <p class="text-sm opacity-90">Colaborador #{{ $customer->id }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if ($customer->is_active)
                                                <span
                                                    class="px-3 py-1 bg-green-500/20 text-green-100 text-xs font-medium rounded-full border border-green-400/30">Activo</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-red-500/20 text-red-100 text-xs font-medium rounded-full border border-red-400/30">Inactivo</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="p-6 space-y-4">
                                    {{-- Información de contacto --}}
                                    @if ($customer->email)
                                        <div class="flex items-center gap-3 text-sm">
                                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-slate-700 dark:text-slate-300 truncate">{{ $customer->email }}</span>
                                        </div>
                                    @endif

                                    @if ($customer->phone || $customer->mobile)
                                        <div class="flex items-center gap-3 text-sm">
                                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-slate-700 dark:text-slate-300">{{ $customer->mobile ?: $customer->phone }}</span>
                                        </div>
                                    @endif

                                    @if ($customer->city)
                                        <div class="flex items-center gap-3 text-sm">
                                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span
                                                class="text-slate-700 dark:text-slate-300 truncate">{{ $customer->city }}{{ $customer->state ? ', ' . $customer->state : '' }}</span>
                                        </div>
                                    @endif

                                    {{-- Estadísticas del colaborador --}}
                                    <div
                                        class="flex items-center gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $customer->vehicles_count }}
                                                vehículo(s)</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $customer->rentals_count }}
                                                renta(s)</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Footer --}}
                                <div
                                    class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center justify-between gap-2">
                                        <button wire:click="viewDetails({{ $customer->id }})"
                                            class="flex-1 px-4 py-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-all text-sm touch-manipulation">
                                            Ver Detalles
                                        </button>
                                        <button wire:click="edit({{ $customer->id }})"
                                            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all text-sm touch-manipulation">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $customer->id }})"
                                            wire:confirm="¿Estás seguro de eliminar este colaborador?"
                                            class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all text-sm touch-manipulation">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="mt-6">
                        {{ $this->customers->links() }}
                    </div>
                @else
                    {{-- Estado vacío --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center shadow-lg">
                        <svg class="w-24 h-24 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-2">No hay clientes
                            registrados</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-6">Comienza creando tu primer colaborador</p>
                        <button wire:click="create"
                            class="px-6 py-3 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-xl transition-all">
                            + Crear Primer Colaborador
                        </button>
                    </div>
                @endif

            </div>

            {{-- MODALES --}}
            @include('livewire.customers.partials.form-modal')
            @include('livewire.customers.partials.detail-modal')

        </div>

        {{-- ESTILOS DEL TEMA --}}
        @include('livewire.customers.partials.theme-styles')
    </div>
</div>
