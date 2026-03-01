{{-- CHECK-OUT: Vista de registro de salida --}}

@if ($current_rental)
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-8 text-white text-center">
                <div class="text-6xl mb-4">🚪</div>
                <h2 class="text-3xl font-bold mb-2">Registrar Salida</h2>
                <p class="text-red-100">Ticket: {{ $current_rental->barcode }}</p>
            </div>

            {{-- Información de la renta --}}
            <div class="p-8 space-y-6">

                {{-- Detalles del espacio y tiempo --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl p-6">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Espacio</p>
                        <div class="flex items-center gap-3">
                            <span class="text-4xl">{{ $current_rental->space->type->getIconEmoji() }}</span>
                            <div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">
                                    {{ $current_rental->space->code }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ $current_rental->space->type->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl p-6">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Tiempo Transcurrido</p>
                        <div class="text-4xl font-bold text-slate-900 dark:text-white font-mono">
                            {{ $current_rental->formatted_time }}</div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Entrada:
                            {{ $current_rental->check_in->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                {{-- Cálculo de pago --}}
                <div
                    class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border-2 border-blue-200 dark:border-blue-700">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-medium text-slate-700 dark:text-slate-300">Total a Pagar:</span>
                            <span
                                class="text-4xl font-bold text-blue-600">${{ number_format($calculated_amount, 2) }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Monto
                                Pagado</label>
                            <div class="relative">
                                <span
                                    class="absolute left-5 top-1/2 transform -translate-y-1/2 text-2xl text-slate-500">$</span>
                                <input type="number" step="0.01" wire:model.live="paid_amount"
                                    class="w-full pl-12 pr-5 py-5 text-2xl font-bold bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 transition-all min-h-[72px] touch-manipulation"
                                    placeholder="0.00">
                            </div>
                        </div>

                        @if ($change_amount > 0)
                            <div
                                class="flex justify-between items-center pt-4 border-t-2 border-blue-200 dark:border-blue-700">
                                <span class="text-lg font-medium text-slate-700 dark:text-slate-300">Cambio:</span>
                                <span
                                    class="text-3xl font-bold text-green-600">${{ number_format($change_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Descripción del vehículo --}}
                @if ($current_rental->description)
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Descripción:</p>
                        <p class="text-slate-900 dark:text-white">{{ $current_rental->description }}</p>
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="flex gap-4 pt-4">
                    <button type="button" wire:click="backToDashboard"
                        class="flex-1 px-8 py-5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition-all shadow-lg active:scale-95 min-h-[64px] touch-manipulation">
                        Cancelar
                    </button>
                    <button wire:click="confirmCheckOut"
                        class="flex-1 px-8 py-5 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all min-h-[64px] touch-manipulation disabled:opacity-50"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>✅ Confirmar Salida</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
@else
    {{-- Vista de búsqueda si no hay renta cargada --}}
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 text-center">
            <div class="text-6xl mb-6">🔍</div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Buscar Ticket</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-8">Escanea o ingresa el código de barras del ticket</p>

            <div class="flex gap-3">
                <input type="text" wire:model="barcode" wire:keydown.enter="searchByBarcode"
                    placeholder="Código de barras..."
                    class="flex-1 px-6 py-5 text-xl border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 min-h-[64px] touch-manipulation"
                    autofocus>
                <button wire:click="searchByBarcode"
                    class="px-8 py-5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl hover:shadow-xl hover:scale-105 active:scale-95 transition-all min-h-[64px] touch-manipulation">
                    Buscar
                </button>
            </div>
        </div>
    </div>
@endif
