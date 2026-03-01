{{-- DASHBOARD: Vista principal tipo Kiosko --}}

{{-- ACCIONES PRINCIPALES --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">

    {{-- ENTRADA (Check-In) --}}
    <button wire:click="changeView('check-in')"
        class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 min-h-[200px] touch-manipulation">
        <div class="absolute top-0 right-0 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
            </svg>
        </div>
        <div class="relative z-10 text-white text-center">
            <div class="text-6xl mb-4">🚗</div>
            <h3 class="text-2xl font-bold mb-2">ENTRADA</h3>
            <p class="text-green-100">Registrar Check-In</p>
        </div>
    </button>

    {{-- SALIDA (Check-Out) --}}
    <button wire:click="changeView('check-out')"
        class="group relative overflow-hidden bg-gradient-to-br from-red-500 to-red-600 rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 min-h-[200px] touch-manipulation">
        <div class="absolute top-0 right-0 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 011.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 011.414-1.414L15 13.586V12a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div class="relative z-10 text-white text-center">
            <div class="text-6xl mb-4">🚪</div>
            <h3 class="text-2xl font-bold mb-2">SALIDA</h3>
            <p class="text-red-100">Registrar Check-Out</p>
        </div>
    </button>

    {{-- TICKET RÁPIDO --}}
    <button wire:click="quickTicket" wire:confirm="¿Generar ticket de entrada rápida?"
        class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 min-h-[200px] touch-manipulation">
        <div class="absolute top-0 right-0 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
            </svg>
        </div>
        <div class="relative z-10 text-white text-center">
            <div class="text-6xl mb-4">⚡</div>
            <h3 class="text-2xl font-bold mb-2">TICKET RÁPIDO</h3>
            <p class="text-blue-100">Entrada automática</p>
        </div>
    </button>

</div>

{{-- ESTADÍSTICAS --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Espacios Disponibles</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $this->stats['available_spaces'] }}</p>
            </div>
            <div class="bg-green-100 dark:bg-green-900/30 rounded-xl p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Espacios Ocupados</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $this->stats['occupied_spaces'] }}</p>
            </div>
            <div class="bg-red-100 dark:bg-red-900/30 rounded-xl p-3">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Ingresos Hoy</p>
                <p class="text-3xl font-bold text-theme-primary mt-1">
                    ${{ number_format($this->stats['today_income'], 2) }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-xl p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- BUSCADOR DE TICKET --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg mb-8">
    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">🔍 Buscar Ticket por Código de Barras</h3>
    <div class="flex gap-3">
        <input type="text" wire:model="barcode" wire:keydown.enter="searchByBarcode"
            placeholder="Escanea o ingresa el código..."
            class="flex-1 px-6 py-4 text-lg border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 min-h-[56px] touch-manipulation"
            autofocus>
        @if ($barcode)
            <button wire:click="clearBarcode"
                class="px-6 py-4 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition-all min-h-[56px] min-w-[56px] touch-manipulation">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        @endif
        <button wire:click="searchByBarcode"
            class="px-8 py-4 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-xl hover:scale-105 active:scale-95 transition-all min-h-[56px] touch-manipulation">
            Buscar
        </button>
    </div>
</div>

{{-- MAPA DE ESPACIOS --}}
{{-- MAPA DE ESPACIOS --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg" x-data="rentalTimer()">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">🅿️ Mapa de Espacios</h3>
        <div class="flex gap-2">
            <select wire:model.live="filter_status"
                class="px-4 py-2 border-2 border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900 text-sm">
                <option value="all">Todos</option>
                <option value="available">Disponibles</option>
                <option value="occupied">Ocupados</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @foreach ($this->spaces as $space)
            <button
                wire:click="{{ $space->status === 'available' ? 'openCheckIn(' . $space->id . ')' : 'checkOutFromSpace(' . $space->id . ')' }}"
                class="relative aspect-square rounded-2xl p-4 shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 touch-manipulation
                    {{ $space->status === 'available' ? 'bg-gradient-to-br from-green-400 to-green-500' : 'bg-gradient-to-br from-red-400 to-red-500' }}"
                x-data="spaceTimer(@js(
    $space->active_rental
        ? [
            'check_in' => $space->active_rental->check_in->timestamp,
            'rate_price' => $space->active_rental->rate->price,
        ]
        : null,
))" x-init="if (rental) startTimer()">

                <div class="absolute top-2 right-2">
                    @if ($space->status === 'available')
                        <span class="inline-block w-3 h-3 bg-green-200 rounded-full animate-pulse"></span>
                    @else
                        <span class="inline-block w-3 h-3 bg-red-200 rounded-full animate-pulse"></span>
                    @endif
                </div>

                <div class="flex flex-col items-center justify-center h-full text-white">
                    <div class="text-3xl mb-2">{{ $space->type->getIconEmoji() }}</div>
                    <div class="font-bold text-lg">{{ $space->code }}</div>

                    @if ($space->active_rental)
                        {{-- Tiempo dinámico --}}
                        <div class="text-xs mt-1 opacity-90 font-mono" x-text="time"></div>

                        {{-- Monto dinámico --}}
                        <div class="text-xs mt-0.5 font-bold" x-text="'$' + amount.toFixed(2)"></div>
                    @endif
                </div>
            </button>
        @endforeach
    </div>
</div>

{{-- Script Alpine.js para timers --}}
@push('scripts')
    <script>
        // Timer individual por espacio
        function spaceTimer(rentalData) {
            return {
                rental: rentalData,
                time: '00:00:00',
                amount: 0,
                interval: null,

                startTimer() {
                    if (!this.rental) return;

                    this.updateTimer();
                    this.interval = setInterval(() => {
                        this.updateTimer();
                    }, 1000);
                },

                updateTimer() {
                    const now = Math.floor(Date.now() / 1000);
                    const checkIn = this.rental.check_in;
                    const elapsed = now - checkIn;

                    // Calcular tiempo
                    const hours = Math.floor(elapsed / 3600);
                    const minutes = Math.floor((elapsed % 3600) / 60);
                    const seconds = elapsed % 60;

                    this.time =
                        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                    // Calcular monto
                    this.amount = this.calculateAmount(elapsed);
                },

                calculateAmount(totalMinutes) {
                    const minutes = Math.floor(totalMinutes / 60);
                    const rate = parseFloat(this.rental.rate_price);

                    // Primera hora
                    if (minutes <= 65) {
                        return rate;
                    }

                    const completeHours = Math.floor(minutes / 60);
                    const remainingMinutes = minutes % 60;

                    let fraction = 0;
                    if (remainingMinutes >= 6 && remainingMinutes <= 30) {
                        fraction = rate * 0.5;
                    } else if (remainingMinutes >= 31) {
                        fraction = rate;
                    }

                    return (completeHours * rate) + fraction;
                },

                destroy() {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                }
            }
        }

        // Timer global del componente
        function rentalTimer() {
            return {
                globalInterval: null,

                init() {
                    // Refrescar datos cada 60 segundos (para sincronizar con servidor)
                    this.globalInterval = setInterval(() => {
                        @this.call('refreshRentals');
                    }, 60000);
                },

                destroy() {
                    if (this.globalInterval) {
                        clearInterval(this.globalInterval);
                    }
                }
            }
        }
    </script>
@endpush
