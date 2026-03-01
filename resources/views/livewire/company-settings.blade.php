<div>
    <div>

        <div x-data="{ theme: $persist('red').as('companyTheme') }"
            :class="{
                'theme-red': theme === 'red',
                'theme-orange': theme === 'orange',
                'theme-blue': theme === 'blue'
            }"
            class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-slate-100 dark:from-slate-900 dark:via-slate-900 dark:to-black">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-600 to-gray-400 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración de Empresa</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Datos que aparecen en los recibos</p>
                        </div>
                    </div>



                </div>

                <form wire:submit.prevent="save" class="space-y-6">

                    {{-- Logo Section --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Logo de la Empresa</span>
                        </h2>

                        <p class="text-xs text-red-500">
    {{ $current_logo_url }}
</p>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            {{-- Preview --}}
                            <div class="flex-shrink-0">
                                @if ($current_logo_url)
                                    <div class="relative group">
                                        <img src="{{ $current_logo_url }}" alt="Logo"
                                            class="w-32 h-32 object-contain rounded-xl border-2 border-gray-200 dark:border-gray-700 p-2 bg-white">
                                        <button wire:click="deleteLogo" type="button"
                                            wire:confirm="¿Eliminar el logo actual?"
                                            class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @elseif($logo)
                                    <div
                                        class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                                        <img src="{{ $logo->temporaryUrl() }}" alt="Preview"
                                            class="max-w-full max-h-full object-contain p-2">
                                    </div>
                                @else
                                    <div
                                        class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Upload --}}
                            <div class="flex-1 space-y-3">
                                <input type="file" wire:model="logo" id="logo" accept="image/*" class="hidden">
                                <label for="logo"
                                    class="cursor-pointer inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    {{ $current_logo_url ? 'Cambiar Logo' : 'Subir Logo' }}
                                </label>
                                @error('logo')
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model="show_logo_on_receipt" id="show_logo"
                                        class="w-4 h-4 text-purple-600 rounded">
                                    <label for="show_logo" class="text-sm text-gray-700 dark:text-gray-300">Mostrar
                                        logo en recibos</label>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Formato: JPG, PNG, GIF. Tamaño máximo: 2MB. Recomendado: 200x200px
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Grid de campos --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- Información Básica --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información Básica</h2>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre
                                        Comercial *</label>
                                    <input type="text" wire:model="name"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Razón
                                        Social</label>
                                    <input type="text" wire:model="business_name"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">RFC</label>
                                    <input type="text" wire:model="rfc" maxlength="13"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white uppercase">
                                </div>
                            </div>
                        </div>

                        {{-- Contacto --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contacto</h2>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" wire:model="email"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                                    <input type="tel" wire:model="phone"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sitio
                                        Web</label>
                                    <input type="text" wire:model="website"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Dirección --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Dirección</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dirección</label>
                                <input type="text" wire:model="address"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Ciudad</label>
                                <input type="text" wire:model="city"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                                <input type="text" wire:model="state"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Código
                                    Postal</label>
                                <input type="text" wire:model="postal_code"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">País</label>
                                <input type="text" wire:model="country"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>

                    {{-- Configuración de Recibos --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Configuración de Recibos</h2>
                            <button wire:click="resetToDefaults" type="button"
                                class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                                Restaurar predeterminados
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pie de
                                    Página</label>
                                <textarea wire:model="receipt_footer" rows="2"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white text-sm"></textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Texto que aparece al final del
                                    recibo</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Términos
                                    y Condiciones</label>
                                <textarea wire:model="receipt_terms" rows="3"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-4">
                        <button type="button" wire:click="loadCompany"
                            class="px-8 py-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="px-8 py-4 bg-gradient-to-r from-red-600 to-red-600 hover:from-gray-700 hover:to-red-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl disabled:opacity-50"
                            wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">💾 Guardar Cambios</span>
                            <span wire:loading wire:target="save" class="flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
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

                </form>

            </div>

            {{-- Sistema de Notificaciones Toast --}}
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Escuchar evento notify
                        Livewire.on('notify', (data) => {
                            const type = data[0]?.type || data.type || 'info';
                            const message = data[0]?.message || data.message || 'Notificación';
                            showToast(message, type);
                        });

                        function showToast(message, type) {
                            // Colores según tipo
                            const colors = {
                                success: 'from-green-500 to-green-600',
                                error: 'from-red-500 to-red-600',
                                warning: 'from-amber-500 to-amber-600',
                                info: 'from-blue-500 to-blue-600'
                            };

                            // Iconos según tipo
                            const icons = {
                                success: '✅',
                                error: '❌',
                                warning: '⚠️',
                                info: 'ℹ️'
                            };

                            const toast = document.createElement('div');
                            toast.className =
                                `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl text-white font-bold transform transition-all duration-300 bg-gradient-to-r ${colors[type] || colors.info}`;
                            toast.style.transform = 'translateX(400px)';

                            toast.innerHTML = `
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">${icons[type] || icons.info}</span>
                            <span>${message}</span>
                        </div>
                    `;

                            document.body.appendChild(toast);

                            // Animar entrada
                            setTimeout(() => {
                                toast.style.transform = 'translateX(0)';
                            }, 10);

                            // Animar salida y eliminar
                            setTimeout(() => {
                                toast.style.transform = 'translateX(400px)';
                                setTimeout(() => toast.remove(), 300);
                            }, 3000);
                        }
                    });
                </script>
            @endpush

        </div>
    </div>


    <style>
        /* ========================================
       TEMA ROJO
       ======================================== */
        .theme-red {
            --theme-primary: rgb(239, 68, 68);
            --theme-primary-dark: rgb(220, 38, 38);
            --theme-secondary: rgb(153, 27, 27);
        }

        /* ========================================
       TEMA NARANJA
       ======================================== */
        .theme-orange {
            --theme-primary: rgb(249, 115, 22);
            --theme-primary-dark: rgb(234, 88, 12);
            --theme-secondary: rgb(194, 65, 12);
        }

        /* ========================================
       TEMA AZUL
       ======================================== */
        .theme-blue {
            --theme-primary: rgb(59, 130, 246);
            --theme-primary-dark: rgb(37, 99, 235);
            --theme-secondary: rgb(30, 64, 175);
        }

        /* ========================================
       CLASES GLOBALES DE TEMA
       ======================================== */

        /* Texto */
        [class*="theme-"] .text-theme-primary {
            color: var(--theme-primary) !important;
        }

        /* Fondo */
        [class*="theme-"] .bg-theme-primary {
            background-color: var(--theme-primary) !important;
        }

        [class*="theme-"] .bg-theme-primary-dark {
            background-color: var(--theme-primary-dark) !important;
        }

        /* Bordes */
        [class*="theme-"] .border-theme-primary {
            border-color: var(--theme-primary) !important;
        }

        /* Gradientes */
        [class*="theme-"] .from-theme-primary {
            --tw-gradient-from: var(--theme-primary);
            --tw-gradient-to: rgb(0 0 0 / 0);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }

        [class*="theme-"] .via-theme-primary {
            --tw-gradient-to: rgb(0 0 0 / 0);
            --tw-gradient-stops: var(--tw-gradient-from), var(--theme-primary), var(--tw-gradient-to);
        }

        [class*="theme-"] .to-theme-primary {
            --tw-gradient-to: var(--theme-primary);
        }

        [class*="theme-"] .to-theme-secondary {
            --tw-gradient-to: var(--theme-secondary);
        }

        /* Hover Estados */
        [class*="theme-"] .hover\:bg-theme-primary:hover {
            background-color: var(--theme-primary) !important;
        }

        [class*="theme-"] .hover\:text-theme-primary:hover {
            color: var(--theme-primary) !important;
        }

        /* Focus Estados */
        [class*="theme-"] .focus\:border-theme-primary:focus {
            border-color: var(--theme-primary) !important;
        }

        [class*="theme-"] .focus\:ring-theme-primary:focus {
            --tw-ring-color: var(--theme-primary) !important;
        }
    </style>
</div>
