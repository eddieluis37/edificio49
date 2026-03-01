{{-- SLIDE-OVER PANEL FORMULARIO --}}
<div x-data="{ open: @entangle('slideOverOpen') }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-hidden"
    @keydown.escape.window="open = false">
    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false">
    </div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full" class="w-screen max-w-md sm:max-w-lg lg:max-w-xl">
            <div class="flex h-full flex-col bg-white dark:bg-slate-900 shadow-2xl overflow-y-auto">
                <div
                    class="bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary px-6 py-6 sm:px-8 sm:py-8 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">
                            {{ $isEditMode ? '✏️ Editar Usuario' : '➕ Nuevo Usuario' }}</h2>
                        <button wire:click="closeSlideOver"
                            class="text-white hover:text-slate-200 transition-colors p-2 hover:bg-white/20 rounded-xl min-h-[48px] min-w-[48px] touch-manipulation">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="store" class="flex-1 overflow-y-auto">
                    <div class="px-6 py-6 sm:px-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nombre
                                Completo *</label>
                            <input type="text" wire:model="name" placeholder="Ej: Juan Pérez García"
                                class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                        class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email
                                *</label>
                            <input type="email" wire:model="email" placeholder="usuario@parki.com"
                                class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                        class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Rol del
                                Usuario *</label>
                            <select wire:model="role"
                                class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('role') border-red-500 @enderror">
                                @foreach ($roles as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                        class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">💡 Administrador: acceso total •
                                Cajero: gestión diaria • Visor: solo lectura</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Contraseña
                                {{ $isEditMode ? '(Dejar vacío para no cambiar)' : '*' }}</label>
                            <input type="password" wire:model="password" placeholder="Mínimo 8 caracteres"
                                class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                        class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Confirmar
                                Contraseña {{ $isEditMode ? '' : '*' }}</label>
                            <input type="password" wire:model="password_confirmation" placeholder="Repetir contraseña"
                                class="w-full px-5 py-4 text-lg bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 min-h-[56px] touch-manipulation">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Foto de
                                Perfil {{ $isEditMode ? '(Opcional)' : '*' }}</label>

                            @if ($isEditMode && $selected_id)
                                @php $currentUser = \App\Models\User::find($selected_id); @endphp
                                @if ($currentUser && $currentUser->profile_photo)
                                    <div class="mb-4 flex justify-center">
                                        <div class="relative group">
                                            <img src="{{ $currentUser->profile_photo_url }}" alt="Foto actual"
                                                class="w-32 h-32 rounded-full object-cover border-4 border-theme-primary/50 shadow-2xl">
                                            <div
                                                class="absolute inset-0 bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                <p class="text-white text-sm font-medium">Foto actual</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="relative">
                                <input type="file" wire:model="photo" id="photo-input" accept="image/*"
                                    class="hidden">
                                <label for="photo-input"
                                    class="flex flex-col items-center justify-center w-full h-48 border-3 border-dashed border-theme-primary/30 rounded-2xl cursor-pointer bg-slate-50 dark:bg-slate-800 hover:bg-theme-primary/5 transition-all duration-300 group min-h-[192px] touch-manipulation">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        @if ($photo)
                                            <svg class="w-16 h-16 text-green-500 mb-3 animate-bounce" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-lg font-bold text-green-600 dark:text-green-400">Imagen
                                                seleccionada</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                {{ $photo->getClientOriginalName() }}</p>
                                        @else
                                            <svg class="w-16 h-16 text-theme-primary mb-3 group-hover:scale-110 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="text-base font-bold text-slate-700 dark:text-slate-300 mb-1">Toca
                                                para subir foto</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">PNG, JPG, GIF (Max.
                                                2MB)</p>
                                        @endif
                                    </div>
                                </label>
                            </div>

                            @error('photo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg
                                        class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>{{ $message }}</p>
                            @enderror

                            @if ($photo)
                                <div class="mt-4 flex justify-center">
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                        class="w-32 h-32 rounded-full object-cover border-4 border-theme-primary/50 shadow-2xl">
                                </div>
                            @endif
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model="active" id="active" type="checkbox"
                                    class="h-5 w-5 rounded border-slate-300 text-theme-primary focus:ring-theme-primary focus:ring-4 focus:ring-theme-primary/30 transition-all">
                            </div>
                            <div class="ml-3">
                                <label for="active"
                                    class="text-sm font-bold text-slate-700 dark:text-slate-300">Usuario activo</label>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Si está desactivado, el usuario
                                    no podrá iniciar sesión</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="border-t border-slate-200 dark:border-slate-700 px-6 py-6 sm:px-8 bg-slate-50 dark:bg-slate-800/50 flex-shrink-0">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button type="button" wire:click="closeSlideOver"
                                class="w-full sm:w-auto px-6 py-4 bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95 min-h-[56px] touch-manipulation">Cancelar</button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 min-h-[56px] touch-manipulation"
                                wire:loading.attr="disabled" wire:target="store">
                                <span wire:loading.remove
                                    wire:target="store">{{ $isEditMode ? '💾 Actualizar' : '✨ Crear Usuario' }}</span>
                                <span wire:loading wire:target="store" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
