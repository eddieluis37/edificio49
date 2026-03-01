{{-- CHECK-IN: Vista de registro de entrada --}}

<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-8 text-white text-center">
            <div class="text-6xl mb-4">🚗</div>
            <h2 class="text-3xl font-bold mb-2">Registrar Entrada</h2>
            <p class="text-green-100">Complete la información del vehículo</p>
        </div>

        {{-- Form --}}
        <form wire:submit="checkIn" class="p-8 space-y-6">

            {{-- Espacio seleccionado --}}
            @if ($selected_space_id)
                @php
                    $space = \App\Models\Space::with('type')->find($selected_space_id);
                @endphp
                <div
                    class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border-2 border-green-200 dark:border-green-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-700 dark:text-green-400">Espacio Seleccionado</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-4xl">{{ $space->type->getIconEmoji() }}</span>
                                <div>
                                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $space->code }}
                                    </p>
                                    <p class="text-sm text-green-700 dark:text-green-400">
                                        {{ $space->type->description }}</p>
                                </div>
                            </div>
                        </div>
                        <button type="button" wire:click="backToDashboard"
                            class="p-3 rounded-xl bg-white dark:bg-slate-700 hover:bg-slate-100 transition-all">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Descripción del vehículo (opcional) --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                    Descripción del Vehículo (Opcional)
                </label>
                <textarea wire:model="vehicle_description" rows="3" placeholder="Ej: Auto blanco, placas ABC-123..."
                    class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-green-500/30 focus:border-green-500 transition-all resize-none touch-manipulation @error('vehicle_description') border-red-500 @enderror"></textarea>
                @error('vehicle_description')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">💡 Ayuda al operador a identificar el
                    vehículo fácilmente</p>
            </div>

            {{-- Información adicional --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-medium mb-1">Información Importante:</p>
                        <ul class="space-y-1 text-xs">
                            <li>• Se generará un ticket con código de barras único</li>
                            <li>• El tiempo de estancia se calculará automáticamente</li>
                            <li>• El cliente debe conservar el ticket para la salida</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="flex gap-4 pt-4">
                <button type="button" wire:click="backToDashboard"
                    class="flex-1 px-8 py-5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition-all shadow-lg hover:shadow-xl active:scale-95 min-h-[64px] touch-manipulation">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex-1 px-8 py-5 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all min-h-[64px] touch-manipulation disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>✅ Registrar Entrada</span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Procesando...
                    </span>
                </button>
            </div>
            {{-- --otros --}}
            {{-- 
========================================
BOTÓN DUAL INTELIGENTE - CHECK-IN
========================================
Reemplaza el botón actual "Registrar Entrada" con este código
Versión Pro Enterprise
--}}

            <div class="flex gap-3">
                {{-- Botón Principal --}}
                <button wire:click="registerCheckIn(false)"
                    class="flex-1 h-16 px-8 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 flex items-center justify-center space-x-3 min-h-[64px] touch-manipulation disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled" wire:target="registerCheckIn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-lg">Registrar Entrada</span>

                    {{-- Loading spinner --}}
                    <svg wire:loading wire:target="registerCheckIn" class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>

                {{-- Botón Dropdown con Opciones --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="h-16 w-16 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 flex items-center justify-center min-h-[64px] min-w-[64px] touch-manipulation"
                        title="Más opciones">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 bottom-full mb-2 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border-2 border-gray-200 dark:border-gray-700 overflow-hidden z-50"
                        x-cloak>
                        {{-- Header del Dropdown --}}
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-3">
                            <h3 class="text-white font-bold text-sm">Opciones de Registro</h3>
                        </div>

                        <div class="p-2">
                            {{-- Opción 1: Registrar SIN Impresión --}}
                            <button wire:click="registerCheckIn(false)" @click="open = false"
                                class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all group">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-700 group-hover:bg-green-100 dark:group-hover:bg-green-900/30 rounded-xl flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">Registrar Entrada
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Solo registrar sin imprimir</p>
                                </div>
                            </button>

                            {{-- Opción 2: Registrar CON Impresión --}}
                            <button wire:click="registerCheckIn(true)" @click="open = false"
                                class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-green-50 dark:hover:bg-green-900/20 rounded-xl transition-all group mt-1">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 group-hover:bg-green-200 dark:group-hover:bg-green-900/50 rounded-xl flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Registrar e
                                            Imprimir</p>
                                        <span
                                            class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">Recomendado</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Registrar y imprimir ticket
                                        automáticamente</p>
                                </div>
                            </button>

                            {{-- Divider --}}
                            <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                            {{-- Opción 3: Configurar Impresora --}}
                            <a href="{{ route('logout') }}"
                                class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl transition-all group">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">Configurar Impresora
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Ajustar configuración de
                                        impresión</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Indicador de Estado de Impresora --}}
            <div class="mt-4 flex items-center justify-center space-x-2 text-sm">
                @if ($printerConfigured)
                    <div class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                        <div class="relative">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <div class="absolute inset-0 w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                        </div>
                        <span class="font-medium">Impresora lista</span>
                    </div>
                @else
                    <div class="flex items-center space-x-2 text-amber-600 dark:text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="font-medium">No hay impresora configurada</span>
                        <a href="{{ route('printing.conf') }}" class="underline hover:text-amber-700">Configurar
                            ahora</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
