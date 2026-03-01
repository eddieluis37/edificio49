<div>
    <div>
        <div x-data="{ theme: $persist('red').as('rentalsTheme') }"
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
                            @if ($currentView !== 'dashboard')
                                <button wire:click="backToDashboard"
                                    class="p-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all min-h-[56px] min-w-[56px] touch-manipulation">
                                    <svg class="w-6 h-6 text-slate-700 dark:text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </button>
                            @endif
                            <div>
                                <h1
                                    class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-slate-800 via-theme-primary to-theme-secondary bg-clip-text text-transparent dark:from-slate-100">
                                    🅿️
                                    {{ $currentView === 'dashboard' ? 'Sistema de Rentas' : ($currentView === 'check-in' ? 'Registrar Entrada' : ($currentView === 'check-out' ? 'Registrar Salida' : 'Ticket Rápido')) }}
                                </h1>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    {{ now()->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Theme Switcher --}}
                            <div
                                class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5 shadow-inner">
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
                    </div>
                </div>
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                @if ($currentView === 'dashboard')
                    {{-- VISTA DASHBOARD --}}
                    @include('livewire.rentals.dashboard')
                @elseif($currentView === 'check-in')
                    {{-- VISTA CHECK-IN --}}
                    @include('livewire.rentals.check-in')
                @elseif($currentView === 'check-out')
                    {{-- VISTA CHECK-OUT --}}
                    @include('livewire.rentals.check-out')
                @endif

            </div>





            {{-- SCRIPTS --}}
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Notificaciones
                        Livewire.on('notify', (data) => {
                            const type = data[0].type || 'info';
                            const message = data[0].message || 'Notificación';
                            showToast(message, type);
                        });

                        // Imprimir ticket
                        Livewire.on('print-ticket', (data) => {
                            const rentalId = data[0].rentalId;
                            // Implementar lógica de impresión
                            console.log('Imprimir ticket:', rentalId);
                        });

                        function showToast(message, type) {
                            const toast = document.createElement('div');
                            toast.className =
                                `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl text-white font-bold transform transition-all duration-300 ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-blue-500 to-blue-600'}`;
                            toast.innerHTML =
                                `<div class="flex items-center gap-3"><span>${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span><span>${message}</span></div>`;
                            document.body.appendChild(toast);
                            setTimeout(() => {
                                toast.style.transform = 'translateX(400px)';
                                setTimeout(() => toast.remove(), 300);
                            }, 3000);
                        }

                        // Scanner de códigos de barras (onScan.js)
                        try {
                            onScan.attachTo(document, {
                                suffixKeyCodes: [13],
                                onScan: function(barcode) {
                                    Livewire.dispatch('barcode-scanned', {
                                        barcode: barcode
                                    });
                                }
                            });
                        } catch (e) {
                            console.log('onScan no disponible');
                        }
                    });
                </script>
            @endpush

        </div>

        {{-- ESTILOS DEL TEMA --}}
        <style>
            .theme-red {
                --theme-primary: #ef4444;
                --theme-primary-dark: #dc2626;
                --theme-secondary: #991b1b;
            }

            .theme-red .from-theme-primary {
                --tw-gradient-from: #ef4444;
                --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(239 68 68 / 0));
            }

            .theme-red .to-theme-secondary {
                --tw-gradient-to: #991b1b;
            }

            .theme-red .via-theme-primary {
                --tw-gradient-stops: var(--tw-gradient-from), #ef4444, var(--tw-gradient-to, rgb(239 68 68 / 0));
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

            .theme-red .hover\:bg-theme-primary:hover {
                background-color: #ef4444 !important;
            }

            .theme-red .focus\:ring-theme-primary:focus {
                --tw-ring-color: #ef4444 !important;
            }

            .theme-red .focus\:border-theme-primary:focus {
                border-color: #ef4444 !important;
            }

            .theme-orange {
                --theme-primary: #f97316;
                --theme-primary-dark: #ea580c;
                --theme-secondary: #c2410c;
            }

            .theme-orange .from-theme-primary {
                --tw-gradient-from: #f97316;
                --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(249 115 22 / 0));
            }

            .theme-orange .to-theme-secondary {
                --tw-gradient-to: #c2410c;
            }

            .theme-orange .via-theme-primary {
                --tw-gradient-stops: var(--tw-gradient-from), #f97316, var(--tw-gradient-to, rgb(249 115 22 / 0));
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

            .theme-orange .hover\:bg-theme-primary:hover {
                background-color: #f97316 !important;
            }

            .theme-orange .focus\:ring-theme-primary:focus {
                --tw-ring-color: #f97316 !important;
            }

            .theme-orange .focus\:border-theme-primary:focus {
                border-color: #f97316 !important;
            }

            .theme-blue {
                --theme-primary: #3b82f6;
                --theme-primary-dark: #2563eb;
                --theme-secondary: #1e40af;
            }

            .theme-blue .from-theme-primary {
                --tw-gradient-from: #3b82f6;
                --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(59 130 246 / 0));
            }

            .theme-blue .to-theme-secondary {
                --tw-gradient-to: #1e40af;
            }

            .theme-blue .via-theme-primary {
                --tw-gradient-stops: var(--tw-gradient-from), #3b82f6, var(--tw-gradient-to, rgb(59 130 246 / 0));
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

            .theme-blue .hover\:bg-theme-primary:hover {
                background-color: #3b82f6 !important;
            }

            .theme-blue .focus\:ring-theme-primary:focus {
                --tw-ring-color: #3b82f6 !important;
            }

            .theme-blue .focus\:border-theme-primary:focus {
                border-color: #3b82f6 !important;
            }
        </style>
    </div>
</div>
