<div>
    <div>
        <div x-data="{ theme: $persist('orange').as('usersTheme') }"
            :class="{
                'theme-red': theme === 'red',
                'theme-orange': theme === 'orange',
                'theme-blue': theme === 'blue'
            }"
            class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-slate-100 dark:from-slate-900 dark:via-slate-900 dark:to-black">

            {{-- HEADER CON ESTADÍSTICAS Y THEME SWITCHER --}}
            <div
                class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <div>
                            <h1
                                class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-slate-800 via-theme-primary to-theme-secondary bg-clip-text text-transparent dark:from-slate-100">
                                👥 Gestión de Usuarios
                            </h1>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Administra el equipo de tu estacionamiento
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Theme Switcher --}}
                            <div
                                class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5 shadow-inner">
                                <button @click="theme = 'red'"
                                    :class="theme === 'red' ? 'bg-red-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-red-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                                    title="Tema Rojo">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="theme = 'orange'"
                                    :class="theme === 'orange' ? 'bg-orange-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-orange-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                                    title="Tema Naranja">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                                <button @click="theme = 'blue'"
                                    :class="theme === 'blue' ? 'bg-blue-600 text-white shadow-lg scale-110' :
                                        'text-slate-600 dark:text-slate-400 hover:text-blue-600'"
                                    class="p-2 rounded-xl transition-all duration-300 min-h-[44px] min-w-[44px] touch-manipulation"
                                    title="Tema Azul">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <button wire:click="create"
                                class="group relative inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 font-bold text-white transition-all duration-300 ease-out bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary rounded-2xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 touch-manipulation min-h-[56px]">
                                <span
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-theme-secondary via-theme-primary to-slate-900 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                <svg class="w-6 h-6 mr-2 relative z-10" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="relative z-10 text-base sm:text-lg">Nuevo Usuario</span>
                            </button>
                        </div>
                    </div>

                    {{-- Estadísticas Grid --}}
                    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4">
                        <div
                            class="col-span-2 bg-gradient-to-br from-theme-primary to-theme-primary-dark rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white/80 text-xs sm:text-sm font-medium">Total Usuarios</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['total_users'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-xs sm:text-sm font-medium">Admins</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['admins'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-xs sm:text-sm font-medium">Cajeros</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['cashiers'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-xs sm:text-sm font-medium">Visores</p>
                                    <p class="text-white text-2xl sm:text-3xl font-bold mt-1">
                                        {{ $this->stats['viewers'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-xl sm:rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-amber-100 text-xs sm:text-sm font-medium">Activos</p>
                                    <p class="text-white text-lg sm:text-xl font-bold mt-1">
                                        {{ $this->stats['active_users'] }}/{{ $this->stats['total_users'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-xl p-2 sm:p-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Búsqueda y Filtros --}}
                    <div class="mt-4 space-y-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="🔍 Buscar por nombre o email..."
                                class="w-full px-6 py-4 sm:py-5 pl-14 pr-12 text-base sm:text-lg bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 shadow-lg hover:shadow-xl min-h-[56px] touch-manipulation">
                            <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-theme-primary"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            @if ($search)
                                <button wire:click="clearSearch"
                                    class="absolute right-5 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <select wire:model.live="filter_role"
                                class="px-5 py-4 text-base bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 shadow-lg min-h-[56px] touch-manipulation">
                                <option value="all">👥 Todos los roles</option>
                                <option value="admin">👑 Administradores</option>
                                <option value="cashier">💰 Cajeros</option>
                                <option value="viewer">👁️ Visores</option>
                            </select>

                            <select wire:model.live="filter_status"
                                class="px-5 py-4 text-base bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-theme-primary/30 focus:border-theme-primary transition-all duration-300 shadow-lg min-h-[56px] touch-manipulation">
                                <option value="all">🔄 Todos los estados</option>
                                <option value="active">✅ Activos</option>
                                <option value="inactive">❌ Inactivos</option>
                            </select>
                        </div>

                        @if ($search || $filter_role !== 'all' || $filter_status !== 'all')
                            <button wire:click="clearFilters"
                                class="text-sm text-theme-primary hover:text-theme-primary-dark font-medium transition-colors">🗑️
                                Limpiar filtros</button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- GRID DE USUARIOS --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                @if ($users->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($users as $user)
                            <div wire:key="user-{{ $user->id }}"
                                class="group relative bg-white dark:bg-slate-800 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-slate-200 dark:border-slate-700 hover:border-theme-primary/50 hover:-translate-y-2">
                                <div
                                    class="relative h-32 bg-gradient-to-br from-theme-primary via-theme-secondary to-slate-900 p-4">
                                    <div class="absolute top-3 left-3">
                                        <button wire:click="toggleActive({{ $user->id }})"
                                            class="flex items-center gap-1.5 px-3 py-1.5 {{ $user->active ? 'bg-green-500/90' : 'bg-red-500/90' }} hover:scale-105 text-white rounded-full text-xs font-bold shadow-lg transition-all">
                                            @if ($user->active)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Activo
                                            @else
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Inactivo
                                            @endif
                                        </button>
                                    </div>

                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white rounded-full text-xs sm:text-sm font-bold shadow-lg">
                                            {{ $user->role_icon }} {{ $user->role_name }}
                                        </span>
                                    </div>

                                    <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
                                        <div class="relative">
                                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                                onerror="this.src='{{ asset('images/default-avatar.png') }}'"
                                                class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-2xl group-hover:scale-110 transition-transform duration-300"
                                                loading="lazy">
                                            @if (!$user->profile_photo)
                                                <div
                                                    class="absolute inset-0 w-24 h-24 rounded-full bg-gradient-to-br from-theme-primary to-theme-secondary flex items-center justify-center text-white text-2xl font-bold border-4 border-white dark:border-slate-800 shadow-2xl">
                                                    {{ $user->initials }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-16 pb-5 px-5">
                                    <h3
                                        class="text-xl font-bold text-slate-800 dark:text-white text-center mb-1 group-hover:text-theme-primary transition-colors truncate">
                                        {{ $user->name }}
                                    </h3>

                                    <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-4 truncate">
                                        {{ $user->email }}
                                    </p>

                                    <div class="flex items-center justify-center text-xs text-slate-400 mb-5 gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Registro: {{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="flex items-center justify-center gap-2 px-4 py-3 bg-theme-primary hover:bg-theme-primary-dark text-white rounded-xl transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg min-h-[48px] touch-manipulation"
                                            title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-bold">Editar</span>
                                        </button>

                                        @if (auth()->id() !== $user->id)
                                            <button wire:click="delete({{ $user->id }})"
                                                wire:confirm="¿Estás seguro de eliminar este usuario?"
                                                class="flex items-center justify-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg min-h-[48px] touch-manipulation"
                                                title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                <span class="text-sm font-bold">Eliminar</span>
                                            </button>
                                        @else
                                            <div class="flex items-center justify-center gap-2 px-4 py-3 bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-xl opacity-50 cursor-not-allowed min-h-[48px]"
                                                title="No puedes eliminarte">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                    </path>
                                                </svg>
                                                <span class="text-sm font-bold">Bloqueado</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">{{ $users->links() }}</div>
                @else
                    <div class="text-center py-16 sm:py-20">
                        <div
                            class="inline-block p-8 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-full mb-6">
                            <svg class="w-20 h-20 sm:w-24 sm:h-24 text-theme-primary" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-white mb-3">
                            @if ($search || $filter_role !== 'all' || $filter_status !== 'all')
                                No se encontraron usuarios
                            @else
                                No hay usuarios registrados
                            @endif
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6 text-base sm:text-lg">
                            @if ($search || $filter_role !== 'all' || $filter_status !== 'all')
                                Intenta con otros filtros de búsqueda
                            @else
                                Comienza creando tu primer usuario
                            @endif
                        </p>
                        @if (!$search && $filter_role === 'all' && $filter_status === 'all')
                            <button wire:click="create"
                                class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-2xl shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 min-h-[56px] touch-manipulation">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Primer Usuario
                            </button>
                        @endif
                    </div>
                @endif
            </div>


            @include('livewire.users.form-slideover')

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Livewire.on('notify', (data) => {
                            const type = data[0].type || 'info';
                            const message = data[0].message || 'Notificación';
                            showToast(type === 'success' ? '✅ ' + message : '❌ ' + message, type);
                        });

                        function showToast(message, type) {
                            const toast = document.createElement('div');
                            toast.className =
                                `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl text-white font-bold transform transition-all duration-300 ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'}`;
                            toast.textContent = message;
                            document.body.appendChild(toast);
                            setTimeout(() => {
                                toast.style.transform = 'translateX(400px)';
                                setTimeout(() => toast.remove(), 300);
                            }, 3000);
                        }
                    });
                </script>
            @endpush
        </div>

        @include('livewire.users.theme-styles')
    </div>
</div>
