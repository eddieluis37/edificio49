{{-- MODAL DE CORTE DE CAJA --}}
@if ($showClosureModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showClosureModal') }" x-show="show" x-cloak>
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-show="show"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeClosureModal">
        </div>

        {{-- Modal Content --}}
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden"
                x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                {{-- Header --}}
                <div class="px-8 py-6 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold mb-1">💰 Corte de Caja</h2>
                            <p class="text-sm opacity-90">{{ $this->getPeriodName() }}</p>
                        </div>
                        <button wire:click="closeClosureModal" class="p-2 rounded-xl hover:bg-white/10 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <form wire:submit="confirmClosure" class="p-8 overflow-y-auto max-h-[calc(90vh-140px)]">

                    {{-- Alerta de Tickets Abiertos --}}
                    @if ($open_tickets > 0)
                        <div
                            class="mb-6 p-6 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex items-start gap-4">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-lg font-bold text-red-900 dark:text-red-100 mb-2">⚠️ Hay
                                        {{ $open_tickets }} ticket(s) abierto(s)</p>
                                    <p class="text-sm text-red-700 dark:text-red-300 mb-3">Se recomienda cerrar todos
                                        los tickets antes de hacer el corte de caja.</p>

                                    @if (isset($closure_data['open_tickets']) && count($closure_data['open_tickets']) > 0)
                                        <div class="space-y-2">
                                            <p class="text-xs font-medium text-red-800 dark:text-red-200">Tickets
                                                abiertos:</p>
                                            @foreach ($closure_data['open_tickets'] as $ticket)
                                                <div
                                                    class="flex items-center gap-2 text-sm text-red-700 dark:text-red-300">
                                                    <span class="font-mono">{{ $ticket->barcode }}</span>
                                                    <span>-</span>
                                                    <span>{{ $ticket->space->code }}</span>
                                                    <span>-</span>
                                                    <span>{{ $ticket->formatted_time }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Resumen del Período --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">Transacciones</p>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $total_rentals }}</p>
                        </div>

                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                            <p class="text-xs text-green-600 dark:text-green-400 mb-1">Efectivo Esperado</p>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                                ${{ number_format($expected_cash, 2) }}</p>
                        </div>

                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                            <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">Promedio</p>
                            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                ${{ $total_rentals > 0 ? number_format($expected_cash / $total_rentals, 2) : '0.00' }}
                            </p>
                        </div>
                    </div>

                    {{-- Input: Efectivo Real --}}
                    <div class="mb-6">
                        <label class="block text-lg font-bold text-slate-700 dark:text-slate-300 mb-3">
                            💵 Efectivo Real Contado
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-6 top-1/2 transform -translate-y-1/2 text-3xl text-slate-500">$</span>
                            <input type="number" step="0.01" wire:model.live="real_cash"
                                class="w-full pl-16 pr-6 py-6 text-3xl font-bold border-4 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 @error('real_cash') border-red-500 @enderror"
                                placeholder="0.00" autofocus>
                        </div>
                        @error('real_cash')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Ingresa el monto físico que contaste
                            en la caja</p>
                    </div>

                    {{-- Diferencia Calculada --}}
                    @if ($real_cash > 0)
                        <div
                            class="mb-6 p-6 rounded-2xl {{ $difference >= 0 ? 'bg-green-50 dark:bg-green-900/20 border-2 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-sm {{ $difference >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }} mb-1">
                                        {{ $difference >= 0 ? '✅ Sobrante' : '⚠️ Faltante' }}
                                    </p>
                                    <p
                                        class="text-4xl font-bold {{ $difference >= 0 ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }}">
                                        ${{ number_format(abs($difference), 2) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-xs {{ $difference >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        Real</p>
                                    <p
                                        class="text-2xl font-bold {{ $difference >= 0 ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }}">
                                        ${{ number_format($real_cash, 2) }}</p>
                                    <p
                                        class="text-xs {{ $difference >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                                        vs</p>
                                    <p
                                        class="text-lg font-medium {{ $difference >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                        ${{ number_format($expected_cash, 2) }} esperado</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Notas/Observaciones --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            📝 Notas u Observaciones
                        </label>
                        <textarea wire:model="notes" rows="3"
                            class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 resize-none"
                            placeholder="Agrega notas sobre el corte (opcional)..."></textarea>
                    </div>

                    {{-- Info Premium --}}
                    <div
                        class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800 mb-6">
                        <p class="text-sm text-amber-800 dark:text-amber-200 flex items-center gap-2">
                            <span class="text-lg">💎</span>
                            <span><strong>Funciones Premium próximamente:</strong> Efectivo inicial, métodos de pago
                                múltiples, gastos/retiros, roles y permisos.</span>
                        </p>
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-4">
                        <button type="button" wire:click="closeClosureModal"
                            class="flex-1 px-6 py-4 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-xl transition-all disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>✅ Confirmar Corte</span>
                            <span wire:loading>Procesando...</span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endif
