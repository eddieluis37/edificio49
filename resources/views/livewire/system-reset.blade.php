<div>
    <div>
        {{-- 
    ========================================
    SYSTEM RESET VIEW - PARKI
    ========================================
    Vista para restablecer el sistema con datos de prueba
    Diseño FUDI Enterprise adaptado a Parki
    ======================================== 
    --}}

        <div
            class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8 px-4 sm:px-6 lg:px-8">

            <div class="max-w-6xl mx-auto">

                {{-- ========================================
                HEADER SECTION
            ======================================== --}}
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        {{-- Título y descripción --}}
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-red-500 via-orange-500 to-yellow-500 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-105 transition-all duration-300">
                                    <svg class="w-10 h-10 text-white animate-spin-slow" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </div>
                                <div
                                    class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                                    Reset del Sistema
                                </h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-medium">
                                    Restablecer Parki con datos de demostración
                                </p>
                            </div>
                        </div>

                        {{-- Usuario actual protegido --}}
                        <div
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl px-5 py-3 border border-green-200 dark:border-green-800 shadow-lg">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                                        Usuario Protegido</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================
                PANEL DE ADVERTENCIA
            ======================================== --}}
                <div class="mb-8">
                    <div
                        class="relative overflow-hidden bg-gradient-to-r from-red-50 via-orange-50 to-yellow-50 dark:from-red-900/20 dark:via-orange-900/20 dark:to-yellow-900/20 rounded-2xl lg:rounded-3xl border-2 border-red-200 dark:border-red-800 shadow-2xl">

                        {{-- Patrón de fondo decorativo --}}
                        <div class="absolute inset-0 opacity-5">
                            <div class="absolute inset-0"
                                style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, currentColor 10px, currentColor 20px);">
                            </div>
                        </div>

                        <div class="relative p-4 sm:p-6 lg:p-8">
                            {{-- Contenedor flex responsive --}}
                            <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">

                                {{-- Icono animado - centrado en móvil, fijo en desktop --}}
                                <div class="flex-shrink-0 w-full sm:w-auto flex justify-center sm:justify-start">
                                    <div
                                        class="w-14 h-14 sm:w-16 sm:h-16 bg-red-500 rounded-2xl flex items-center justify-center shadow-xl animate-pulse">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Contenido --}}
                                <div class="flex-1 w-full">
                                    {{-- Título responsive --}}
                                    <div class="mb-4">
                                        <h3
                                            class="text-lg sm:text-xl lg:text-2xl font-black text-red-900 dark:text-red-100 mb-2 sm:mb-3 text-center sm:text-left">
                                            ⚠️ ZONA DE PELIGRO
                                        </h3>
                                        <div class="flex justify-center sm:justify-start">
                                            <span
                                                class="inline-flex px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                                Irreversible
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Grid de alertas - 100% responsive --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">

                                        {{-- Alerta 1: Eliminación Total --}}
                                        <div
                                            class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-3 sm:p-4 border border-red-200 dark:border-red-800">
                                            <div class="flex items-start space-x-2 sm:space-x-3">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="font-bold text-red-900 dark:text-red-100 text-xs sm:text-sm mb-1">
                                                        Eliminación Total</p>
                                                    <p class="text-xs text-red-700 dark:text-red-300 leading-tight">
                                                        Todos los datos actuales serán borrados permanentemente</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Alerta 2: Datos de Prueba --}}
                                        <div
                                            class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-3 sm:p-4 border border-orange-200 dark:border-orange-800">
                                            <div class="flex items-start space-x-2 sm:space-x-3">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                                                    <path
                                                        d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                                                    <path
                                                        d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="font-bold text-orange-900 dark:text-orange-100 text-xs sm:text-sm mb-1">
                                                        Datos de Prueba</p>
                                                    <p
                                                        class="text-xs text-orange-700 dark:text-orange-300 leading-tight">
                                                        Se insertarán datos demo para demostración</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Alerta 3: Usuario Seguro --}}
                                        <div
                                            class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-3 sm:p-4 border border-green-200 dark:border-green-800">
                                            <div class="flex items-start space-x-2 sm:space-x-3">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="font-bold text-green-900 dark:text-green-100 text-xs sm:text-sm mb-1">
                                                        Usuario Seguro</p>
                                                    <p class="text-xs text-green-700 dark:text-green-300 leading-tight">
                                                        Tu cuenta permanecerá intacta</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Alerta 4: Sin Retorno --}}
                                        <div
                                            class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-3 sm:p-4 border border-purple-200 dark:border-purple-800">
                                            <div class="flex items-start space-x-2 sm:space-x-3">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="font-bold text-purple-900 dark:text-purple-100 text-xs sm:text-sm mb-1">
                                                        Sin Retorno</p>
                                                    <p
                                                        class="text-xs text-purple-700 dark:text-purple-300 leading-tight">
                                                        Esta acción NO se puede deshacer</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================
                ESTADÍSTICAS DEL RESET
            ======================================== --}}

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Datos actuales en el sistema
                    </h2>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                        {{-- Tipos de Vehículos --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Tipos</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $typesCount }}</p>
                            </div>
                        </div>

                        {{-- Tarifas --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Tarifas</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $ratesCount }}</p>
                            </div>
                        </div>

                        {{-- Espacios --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Espacios</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $spacesCount }}</p>
                            </div>
                        </div>

                        {{-- Clientes --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-pink-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Clientes</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $customersCount }}</p>
                            </div>
                        </div>

                        {{-- Vehículos --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Vehículos</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $vehiclesCount }}</p>
                            </div>
                        </div>

                        {{-- Rentas --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Rentas</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $rentalsCount }}</p>
                            </div>
                        </div>

                        {{-- Cortes --}}
                        <div
                            class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-teal-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-teal-400 to-teal-600 rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Cortes</p>
                                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $closuresCount }}</p>
                            </div>
                        </div>

                        {{-- Total Registros --}}
                        <div
                            class="group relative bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-2xl p-6 shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-4 right-4 opacity-20">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-white/30 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-md mb-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-white/80 uppercase tracking-wider mb-1">Total</p>
                                <p class="text-4xl font-black text-white">
                                    {{ $typesCount + $ratesCount + $spacesCount + $customersCount + $vehiclesCount + $rentalsCount + $closuresCount }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ========================================
                ÁREA DE ACCIÓN
            ======================================== --}}
                <div
                    class="relative overflow-hidden bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl border border-gray-200 dark:border-gray-700 shadow-2xl">

                    {{-- Patrón decorativo --}}
                    <div class="absolute inset-0 opacity-5 pointer-events-none">
                        <div class="absolute inset-0"
                            style="background-image: radial-gradient(circle at 2px 2px, currentColor 1px, transparent 0); background-size: 32px 32px;">
                        </div>
                    </div>

                    <div class="relative p-8">
                        @if (!$isResetting)
                            {{-- Estado: Listo para resetear --}}
                            <div class="text-center">
                                <div class="max-w-md mx-auto mb-8">
                                    <div class="flex justify-center mb-6">
                                        <div class="relative">
                                            <div
                                                class="w-24 h-24 bg-gradient-to-br from-red-500 via-orange-500 to-yellow-500 rounded-3xl flex items-center justify-center shadow-2xl animate-pulse">
                                                <svg class="w-14 h-14 text-white" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </div>
                                            <div
                                                class="absolute -bottom-2 -right-2 w-10 h-10 bg-red-600 rounded-2xl flex items-center justify-center shadow-xl">
                                                <svg class="w-6 h-6 text-white animate-bounce" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                                        ¿Estás completamente seguro?
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                                        Esta operación eliminará <strong
                                            class="text-red-600 dark:text-red-400">TODOS</strong> los datos actuales
                                        del sistema Parki.
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500">
                                        Se insertarán datos de demostración para pruebas.
                                    </p>
                                </div>

                                <button wire:click="openConfirmModal"
                                    class="group relative px-10 py-5 bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 hover:from-red-700 hover:via-orange-700 hover:to-yellow-700 text-white font-black text-lg rounded-2xl shadow-2xl hover:shadow-3xl transform hover:scale-105 active:scale-95 transition-all duration-200 inline-flex items-center space-x-4 overflow-hidden">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000">
                                    </div>
                                    <svg class="w-8 h-8 animate-spin-slow relative z-10" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span class="relative z-10">INICIAR RESET DEL SISTEMA</span>
                                </button>

                                <div
                                    class="mt-6 flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Se solicitará confirmación antes de proceder</span>
                                </div>
                            </div>
                        @else
                            {{-- Estado: Procesando reset --}}
                            <div class="space-y-8">

                                {{-- Barra de progreso principal --}}
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <span
                                                class="text-lg font-bold text-gray-900 dark:text-white">{{ $currentStep }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ $progress }}%</span>
                                        </div>
                                    </div>

                                    {{-- Barra animada --}}
                                    <div
                                        class="relative w-full h-6 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full transition-all duration-500 ease-out relative"
                                            style="width: {{ $progress }}%">
                                            <div class="absolute inset-0 bg-white/30 animate-pulse"></div>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Spinner central --}}
                                <div class="flex flex-col items-center justify-center py-6">
                                    <div class="relative">
                                        <div
                                            class="w-20 h-20 border-8 border-indigo-200 dark:border-indigo-900 rounded-full">
                                        </div>
                                        <div
                                            class="absolute inset-0 w-20 h-20 border-8 border-t-indigo-600 border-r-purple-600 border-b-pink-600 border-l-transparent rounded-full animate-spin">
                                        </div>
                                        <div
                                            class="absolute inset-2 w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/50 dark:to-purple-900/50 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 animate-pulse"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-4 text-sm font-semibold text-gray-600 dark:text-gray-400 animate-pulse">
                                        Procesando datos...</p>
                                </div>

                                {{-- Lista de pasos con estados --}}
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                                    <h4
                                        class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        Proceso de Reset
                                    </h4>
                                    <ul class="space-y-3">
                                        @foreach ($steps as $index => $step)
                                            @php
                                                $stepProgress = ($index + 1) * (100 / count($steps));
                                                $isCompleted = $progress >= $stepProgress;
                                                $isCurrent =
                                                    $progress < $stepProgress &&
                                                    $progress >= $index * (100 / count($steps));
                                            @endphp
                                            <li
                                                class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-300 {{ $isCurrent ? 'bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800' : '' }}">
                                                @if ($isCompleted)
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                                                        <svg class="w-5 h-5 text-white" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <span
                                                        class="font-semibold text-green-700 dark:text-green-400">{{ $step }}</span>
                                                @elseif($isCurrent)
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-xl animate-spin">
                                                    </div>
                                                    <span
                                                        class="font-bold text-indigo-700 dark:text-indigo-300">{{ $step }}</span>
                                                @else
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <span
                                                        class="text-gray-500 dark:text-gray-400">{{ $step }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Mensaje informativo --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-sm text-blue-800 dark:text-blue-200">
                                            Por favor, no cierres esta ventana hasta que el proceso termine.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ========================================
            MODAL DE CONFIRMACIÓN
        ======================================== --}}
            @if ($showConfirmModal)
                <div x-data="{ show: @entangle('showConfirmModal').live }" x-show="show" x-cloak
                    @keydown.escape.window="$wire.closeConfirmModal()"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[9999] overflow-y-auto">
                    {{-- Backdrop oscuro con blur --}}
                    <div class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="$wire.closeConfirmModal()"></div>

                    {{-- Modal centrado --}}
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div x-show="show" x-transition:enter="transform transition ease-out duration-300"
                            x-transition:enter-start="scale-90 opacity-0"
                            x-transition:enter-end="scale-100 opacity-100"
                            x-transition:leave="transform transition ease-in duration-200"
                            x-transition:leave-start="scale-100 opacity-100"
                            x-transition:leave-end="scale-90 opacity-0"
                            class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden"
                            @click.stop>
                            {{-- Header con gradiente --}}
                            <div class="bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 px-8 py-6">
                                <div class="flex items-center space-x-4 text-white">
                                    <div
                                        class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-black">Confirmación Requerida</h3>
                                        <p class="text-sm text-white/80 mt-1">Acción crítica del sistema</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="p-8 space-y-6">
                                {{-- Alerta --}}
                                <div
                                    class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-2xl p-5">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-red-900 dark:text-red-100 mb-2">
                                                Esta acción es completamente IRREVERSIBLE
                                            </p>
                                            <p class="text-xs text-red-700 dark:text-red-300">
                                                Se eliminarán todos los datos existentes en el sistema de gestión de
                                                estacionamiento Parki. Esta operación no se puede deshacer.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Input de confirmación --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3">
                                        Para confirmar, escribe
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-gray-900 dark:bg-gray-700 text-white font-mono text-xs rounded-lg mx-1">
                                            RESET
                                        </span>
                                        en el campo:
                                    </label>
                                    <input type="text" wire:model.live="confirmText"
                                        placeholder="Escribe RESET aquí..."
                                        class="w-full px-5 py-4 border-2 {{ $canReset ? 'border-green-500 ring-4 ring-green-200 dark:ring-green-900/30' : 'border-gray-300 dark:border-gray-600' }} bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl focus:border-red-500 focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900/30 transition-all duration-200 text-lg font-mono uppercase placeholder:normal-case placeholder:text-gray-400"
                                        autocomplete="off">

                                    @if ($confirmText && !$canReset)
                                        <div
                                            class="mt-3 flex items-center space-x-2 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-4 py-2 rounded-xl">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-semibold">Debe escribir exactamente
                                                "RESET"</span>
                                        </div>
                                    @endif

                                    @if ($canReset)
                                        <div
                                            class="mt-3 flex items-center space-x-2 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-xl">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-semibold">✓ Correcto. Ya puedes continuar.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer con botones --}}
                            <div class="flex gap-4 px-8 pb-8">
                                <button wire:click="closeConfirmModal"
                                    class="flex-1 h-14 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-bold rounded-2xl transition-all duration-200 transform hover:scale-105 active:scale-95">
                                    Cancelar
                                </button>
                                <button wire:click="startReset" :disabled="!@js($canReset)"
                                    class="flex-1 h-14 bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 hover:from-red-700 hover:via-orange-700 hover:to-yellow-700 text-white font-bold rounded-2xl transition-all duration-200 transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:from-red-600 disabled:hover:via-orange-600 disabled:hover:to-yellow-600 shadow-lg hover:shadow-2xl">
                                    Confirmar Reset
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ========================================
        SCRIPTS Y ESTILOS ADICIONALES
    ======================================== --}}
        @push('styles')
            <style>
                /* Animación de spin lento para el icono principal */
                @keyframes spin-slow {
                    from {
                        transform: rotate(0deg);
                    }

                    to {
                        transform: rotate(360deg);
                    }
                }

                .animate-spin-slow {
                    animation: spin-slow 4s linear infinite;
                }

                /* Animación shimmer para la barra de progreso */
                @keyframes shimmer {
                    0% {
                        transform: translateX(-100%);
                    }

                    100% {
                        transform: translateX(100%);
                    }
                }

                .animate-shimmer {
                    animation: shimmer 2s infinite;
                }

                /* Ocultar elementos con x-cloak */
                [x-cloak] {
                    display: none !important;
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('livewire:init', () => {
                    // Escuchar el evento de reset completado
                    Livewire.on('resetComplete', () => {
                        console.log('✅ Reset del sistema completado exitosamente');

                        // Recargar la página después de 2 segundos
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    });

                    // Escuchar actualizaciones de progreso (opcional para logs)
                    Livewire.on('progressUpdate', (data) => {
                        console.log(`Progreso: ${data[0].progress}% - ${data[0].step}`);
                    });
                });
            </script>
        @endpush

    </div>
</div>
