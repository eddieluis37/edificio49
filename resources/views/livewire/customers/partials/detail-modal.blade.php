{{-- MODAL DE DETALLES --}}
@if ($showDetailModal && $customer_id)
    @php
        $customer = $this->getCurrentCustomer();
    @endphp

    @if ($customer)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDetailModal') }" x-show="show" x-cloak>
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-show="show"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeDetailModal">
            </div>

            {{-- Modal Content --}}
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                    x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95">

                    {{-- Header --}}
                    <div
                        class="px-8 py-6 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-bold mb-1">{{ $customer->name }}</h2>
                                <p class="text-sm opacity-90">Cliente #{{ $customer->id }}</p>
                            </div>
                            <button wire:click="closeDetailModal"
                                class="p-2 rounded-xl hover:bg-white/10 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-8 overflow-y-auto max-h-[calc(90vh-140px)]">

                        {{-- Información del Cliente --}}
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-theme-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información de Contacto
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($customer->email)
                                    <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl">
                                        <svg class="w-5 h-5 text-theme-primary flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Email</p>
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ $customer->email }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($customer->phone)
                                    <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl">
                                        <svg class="w-5 h-5 text-theme-primary flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Teléfono</p>
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ $customer->phone }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($customer->mobile)
                                    <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl">
                                        <svg class="w-5 h-5 text-theme-primary flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Móvil</p>
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ $customer->mobile }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($customer->full_address !== 'N/A')
                                    <div
                                        class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl md:col-span-2">
                                        <svg class="w-5 h-5 text-theme-primary flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Dirección</p>
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ $customer->full_address }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($customer->notes)
                                <div
                                    class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                    <p class="text-xs text-blue-700 dark:text-blue-400 font-medium mb-1">Notas</p>
                                    <p class="text-sm text-blue-900 dark:text-blue-300">{{ $customer->notes }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Vehículos del Cliente --}}
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-theme-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                    </path>
                                </svg>
                                Vehículos ({{ $customer->vehicles_count }})
                            </h3>

                            @if ($customer->vehicles->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($customer->vehicles as $vehicle)
                                        <div
                                            class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl border border-purple-200 dark:border-purple-800">
                                            <div class="flex items-start gap-3">
                                                <div class="text-3xl">{{ $vehicle->type->getIconEmoji() }}</div>
                                                <div class="flex-1">
                                                    <p class="font-bold text-purple-900 dark:text-purple-100">
                                                        {{ $vehicle->plate }}</p>
                                                    <p class="text-sm text-purple-700 dark:text-purple-300">
                                                        {{ $vehicle->full_description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-8 text-center bg-slate-50 dark:bg-slate-800 rounded-xl">
                                    <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                        </path>
                                    </svg>
                                    <p class="text-slate-500 dark:text-slate-400">Sin vehículos registrados</p>
                                </div>
                            @endif
                        </div>

                        {{-- Historial de Rentas --}}
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-theme-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                    </path>
                                </svg>
                                Historial de Rentas ({{ $customer->rentals_count }})
                            </h3>

                            @if ($customer->rentals->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($customer->rentals->take(5) as $rental)
                                        <div
                                            class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl hover:shadow-md transition-all">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="px-3 py-1 {{ $rental->status === 'open' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' }} text-xs font-medium rounded-full">
                                                        {{ $rental->status === 'open' ? 'Abierta' : 'Cerrada' }}
                                                    </span>
                                                    <span
                                                        class="text-sm font-mono text-slate-500 dark:text-slate-400">#{{ $rental->barcode }}</span>
                                                </div>
                                                <span
                                                    class="text-lg font-bold text-theme-primary">{{ $rental->formatted_amount }}</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                                                <span>📍 {{ $rental->space->code }}</span>
                                                <span>⏱️ {{ $rental->formatted_time }}</span>
                                                <span>📅 {{ $rental->check_in->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($customer->rentals_count > 5)
                                        <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                                            Mostrando 5 de {{ $customer->rentals_count }} rentas
                                        </p>
                                    @endif
                                </div>
                            @else
                                <div class="p-8 text-center bg-slate-50 dark:bg-slate-800 rounded-xl">
                                    <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                        </path>
                                    </svg>
                                    <p class="text-slate-500 dark:text-slate-400">Sin rentas registradas</p>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div
                        class="px-8 py-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
                        <button wire:click="closeDetailModal"
                            class="w-full px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-all">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endif
