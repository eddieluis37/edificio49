{{-- MODAL DE FORMULARIO (Crear/Editar) --}}
@if ($showModal)
    <div class="fixed inset-0 z-50 overflow-hidden" x-data="{ show: @entangle('showModal') }" x-show="show" x-cloak>
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-show="show"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeModal"></div>

        {{-- Slide-over Panel --}}
        <div class="fixed inset-y-0 right-0 max-w-full flex" x-show="show"
            x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div class="w-screen max-w-2xl">
                <form wire:submit="save" class="h-full flex flex-col bg-white dark:bg-slate-900 shadow-2xl">

                    {{-- Header --}}
                    <div
                        class="px-6 py-6 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h2 class="text-2xl font-bold">{{ $isEditing ? 'Editar Colaborador' : 'Nuevo Colaborador' }}
                                </h2>
                            </div>
                            <button type="button" wire:click="closeModal"
                                class="p-2 rounded-xl hover:bg-white/10 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body (Scrollable) --}}
                    <div class="flex-1 overflow-y-auto p-6 space-y-6">

                        {{-- Información Personal --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-theme-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información Personal
                            </h3>

                            {{-- Nombre * --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 @error('name') border-red-500 @enderror"
                                    placeholder="Juan Pérez García">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="flex items-center gap-3 p-4 bg-slate-100 dark:bg-slate-800 rounded-xl">
                                <input type="checkbox" wire:model="is_active" id="is_active"
                                    class="w-5 h-5 text-theme-primary border-2 border-slate-300 rounded focus:ring-4 focus:ring-theme-primary/30">
                                <label for="is_active"
                                    class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                                    Colaborador activo
                                </label>
                            </div>
                        </div>

                        {{-- Contacto --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-theme-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Contacto
                            </h3>

                            {{-- Email --}}
                            <div>
                                <label
                                    class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 @error('email') border-red-500 @enderror"
                                    placeholder="cliente@example.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Teléfonos --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Teléfono</label>
                                    <input type="text" wire:model="phone"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                        placeholder="443-123-4567">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Móvil</label>
                                    <input type="text" wire:model="mobile"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                        placeholder="443-987-6543">
                                </div>
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-theme-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Dirección
                            </h3>

                            {{-- Dirección --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Calle y
                                    Número</label>
                                <input type="text" wire:model="address"
                                    class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                    placeholder="Av. Madero 123">
                            </div>

                            {{-- Ciudad y Estado --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Ciudad</label>
                                    <input type="text" wire:model="city"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                        placeholder="Morelia">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                                    <input type="text" wire:model="state"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                        placeholder="Michoacán">
                                </div>
                            </div>

                            {{-- CP y País --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Código
                                        Postal</label>
                                    <input type="text" wire:model="zip_code"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                        placeholder="58000">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">País</label>
                                    <input type="text" wire:model="country"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900"
                                        placeholder="México">
                                </div>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div>
                            <label
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Notas</label>
                            <textarea wire:model="notes" rows="3"
                                class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary bg-slate-50 dark:bg-slate-900 resize-none"
                                placeholder="Notas adicionales sobre el Colaborador..."></textarea>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex gap-3">
                            <button type="button" wire:click="closeModal"
                                class="flex-1 px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-xl transition-all disabled:opacity-50"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $isEditing ? 'Actualizar' : 'Crear' }} Colaborador</span>
                                <span wire:loading>Guardando...</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif
