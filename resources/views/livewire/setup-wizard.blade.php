<div>
    <div>
        {{-- 
    ========================================
    SETUP WIZARD VIEW - PARKI
    ========================================
    Instalador universal simplificado
    Mobile-first, Tailwind CSS 4
    ======================================== 
    --}}

        <div
            class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-blue-950 dark:to-indigo-950">

            {{-- Header Sticky con progreso --}}
            <div
                class="sticky top-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800 shadow-lg">
                <div class="max-w-3xl mx-auto px-4 py-4">

                    {{-- Logo y título --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Parki Setup</h1>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Installation Wizard</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $currentStep }}/{{ $totalSteps }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ round(($currentStep / $totalSteps) * 100) }}%</p>
                        </div>
                    </div>

                    {{-- Barra de progreso --}}
                    <div class="relative h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-full transition-all duration-500"
                            style="width: {{ ($currentStep / $totalSteps) * 100 }}%">
                            <div class="absolute inset-0 bg-white/30 animate-pulse"></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Contenido Principal --}}
            <div class="max-w-3xl mx-auto px-4 py-6 sm:py-8">

                {{-- ========================================
                STEP 1: REQUIREMENTS CHECK
            ======================================== --}}
                @if ($currentStep === 1)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-6">

                        <div class="text-center space-y-2">
                            <div
                                class="w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">System Requirements</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Checking server compatibility</p>
                        </div>

                        {{-- Lista de requisitos --}}
                        <div class="space-y-2">
                            @foreach ($requirements as $requirement)
                                <div
                                    class="flex items-center justify-between p-3 rounded-xl border transition-all {{ $requirement['met'] ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }}">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        @if ($requirement['met'])
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $requirement['name'] }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $requirement['current'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Resumen --}}
                        <div
                            class="p-4 rounded-xl {{ $requirementsMet ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }}">
                            @if ($requirementsMet)
                                <p class="text-sm font-bold text-green-900 dark:text-green-100 text-center">✅ All
                                    requirements met</p>
                            @else
                                <p class="text-sm font-bold text-red-900 dark:text-red-100 text-center">⚠️ Missing
                                    requirements</p>
                            @endif
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-3">
                            <button wire:click="checkRequirements"
                                class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-xl transition-all">
                                Recheck
                            </button>
                            <button wire:click="nextStep" @if (!$requirementsMet) disabled @endif
                                class="flex-1 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold rounded-xl transition-all disabled:cursor-not-allowed">
                                Continue
                            </button>
                        </div>

                    </div>
                @endif

                {{-- ========================================
                STEP 2: DATABASE CONFIGURATION
            ======================================== --}}
                @if ($currentStep === 2)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-6">

                        <div class="text-center space-y-2">
                            <div
                                class="w-16 h-16 mx-auto bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 1.79 4 4 4h8c2.21 0 4-1.79 4-4V7M4 7h16M4 7l1-2h14l1 2" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Database Setup</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Configure your MySQL database</p>
                        </div>

                        <div class="space-y-4">

                            {{-- App Settings --}}
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl space-y-4">
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Application</h3>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">App
                                        Name</label>
                                    <input type="text" wire:model="appName"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/30 transition-all text-gray-900 dark:text-white">
                                    @error('appName')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">App
                                        URL</label>
                                    <input type="url" wire:model="appUrl"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/30 transition-all text-gray-900 dark:text-white">
                                    @error('appUrl')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Database Settings --}}
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl space-y-4">
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">MySQL Connection</h3>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Host</label>
                                        <input type="text" wire:model="dbHost"
                                            class="w-full h-12 px-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900/30 transition-all text-sm font-mono text-gray-900 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Port</label>
                                        <input type="text" wire:model="dbPort"
                                            class="w-full h-12 px-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900/30 transition-all text-sm font-mono text-gray-900 dark:text-white">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Database
                                        Name</label>
                                    <input type="text" wire:model="dbName"
                                        class="w-full h-12 px-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900/30 transition-all text-sm font-mono text-gray-900 dark:text-white">
                                    @error('dbName')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Username</label>
                                        <input type="text" wire:model="dbUsername"
                                            class="w-full h-12 px-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900/30 transition-all text-sm font-mono text-gray-900 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                                        <input type="password" wire:model="dbPassword"
                                            class="w-full h-12 px-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900/30 transition-all text-sm font-mono text-gray-900 dark:text-white">
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-3">
                            <button wire:click="previousStep"
                                class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-xl transition-all">
                                Back
                            </button>
                            <button wire:click="testConnection"
                                class="flex-1 h-12 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all">
                                Test
                            </button>
                            <button wire:click="nextStep"
                                class="flex-1 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all">
                                Continue
                            </button>
                        </div>

                    </div>
                @endif

                {{-- ========================================
                STEP 3: ADMIN ACCOUNT
            ======================================== --}}
                @if ($currentStep === 3)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-6">

                        <div class="text-center space-y-2">
                            <div
                                class="w-16 h-16 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Account</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Create your administrator user</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full
                                    Name</label>
                                <input type="text" wire:model="adminName"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-800 border-2 {{ $errors->has('adminName') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                @error('adminName')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" wire:model="adminEmail"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-800 border-2 {{ $errors->has('adminEmail') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                @error('adminEmail')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                                <input type="password" wire:model="adminPassword"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-800 border-2 {{ $errors->has('adminPassword') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                @error('adminPassword')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mínimo 8 caracteres</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Confirm
                                    Password</label>
                                <input type="password" wire:model="adminPasswordConfirmation"
                                    class="w-full h-12 px-4 bg-white dark:bg-gray-800 border-2 {{ $errors->has('adminPasswordConfirmation') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                @error('adminPasswordConfirmation')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Indicador visual de validación --}}
                        @if ($adminPassword && $adminPasswordConfirmation)
                            @if ($adminPassword === $adminPasswordConfirmation && strlen($adminPassword) >= 8)
                                <div
                                    class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                                    <p class="text-sm text-green-800 dark:text-green-200 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        ✅ Las contraseñas coinciden
                                    </p>
                                </div>
                            @elseif($adminPassword !== $adminPasswordConfirmation)
                                <div
                                    class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                    <p class="text-sm text-red-800 dark:text-red-200 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        ⚠️ Las contraseñas no coinciden
                                    </p>
                                </div>
                            @endif
                        @endif

                        {{-- Botones --}}
                        <div class="flex gap-3">
                            <button wire:click="previousStep"
                                class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-xl transition-all">
                                Back
                            </button>
                            <button wire:click="nextStep"
                                class="flex-1 h-12 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all">
                                Continue
                            </button>
                        </div>

                    </div>
                @endif

                {{-- ========================================
                STEP 4: CONFIRMATION
            ======================================== --}}
                @if ($currentStep === 4)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-6">

                        <div class="text-center space-y-2">
                            <div
                                class="w-16 h-16 mx-auto bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ready to Install</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Review your configuration</p>
                        </div>

                        <div class="space-y-3">
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-3">
                                    Application</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $appName }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">URL:</span>
                                        <span
                                            class="font-mono text-xs font-semibold text-gray-900 dark:text-white truncate ml-2">{{ $appUrl }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-3">Database
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Host:</span>
                                        <span
                                            class="font-mono text-xs font-semibold text-gray-900 dark:text-white">{{ $dbHost }}:{{ $dbPort }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Database:</span>
                                        <span
                                            class="font-mono text-xs font-semibold text-gray-900 dark:text-white">{{ $dbName }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">User:</span>
                                        <span
                                            class="font-mono text-xs font-semibold text-gray-900 dark:text-white">{{ $dbUsername }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-3">Admin
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $adminName }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                        <span
                                            class="font-mono text-xs font-semibold text-gray-900 dark:text-white">{{ $adminEmail }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Warning --}}
                        <div
                            class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                            <p class="text-xs text-yellow-800 dark:text-yellow-200 text-center">
                                ⚠️ Installation will create database tables and insert sample data
                            </p>
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-3">
                            <button wire:click="previousStep"
                                class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-xl transition-all">
                                Back
                            </button>
                            <button wire:click="startInstallation"
                                class="flex-1 h-12 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg">
                                Install Parki
                            </button>
                        </div>

                    </div>
                @endif

                {{-- ========================================
                STEP 5: INSTALLATION PROGRESS
            ======================================== --}}
                @if ($currentStep === 5)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-6">

                        @if ($setupComplete)
                            {{-- Completado --}}
                            <div class="text-center space-y-4">
                                <div
                                    class="w-20 h-20 mx-auto bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center animate-bounce">
                                    <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">🎉 Installation Complete!
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Parki is ready to use</p>

                                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Redirecting to login in <span
                                            class="font-bold">3 seconds</span>...</p>
                                </div>

                                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">Login
                                        Credentials:</p>
                                    <div class="space-y-1 text-sm">
                                        <p class="text-gray-700 dark:text-gray-300">Email: <span
                                                class="font-mono font-bold">{{ $adminEmail }}</span></p>
                                        <p class="text-gray-700 dark:text-gray-300">Password: <span
                                                class="font-mono">••••••••</span></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Procesando --}}
                            <div class="space-y-6">
                                <div class="text-center space-y-2">
                                    <div
                                        class="w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Installing...</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $currentProcess }}</p>
                                </div>

                                {{-- Progress Bar --}}
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Progress</span>
                                        <span
                                            class="font-bold text-blue-600 dark:text-blue-400">{{ $progress }}%</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full transition-all duration-500"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                {{-- Logs --}}
                                @if (count($processLogs) > 0)
                                    <div class="p-4 bg-gray-900 rounded-xl max-h-80 overflow-y-auto">
                                        <div class="space-y-1 font-mono text-xs">
                                            @foreach ($processLogs as $log)
                                                <div
                                                    class="flex items-start space-x-2 {{ $log['type'] === 'success' ? 'text-green-400' : '' }} {{ $log['type'] === 'error' ? 'text-red-400' : '' }} {{ $log['type'] === 'warning' ? 'text-yellow-400' : '' }} {{ $log['type'] === 'info' ? 'text-blue-400' : '' }}">
                                                    <span class="text-gray-500">[{{ $log['timestamp'] }}]</span>
                                                    <span class="flex-1">{{ $log['message'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                @endif

            </div>

        </div>

        {{-- Scripts --}}
        @push('scripts')
            <script>
                document.addEventListener('livewire:init', () => {
                    // Redireccionar al login cuando la instalación termine
                    Livewire.on('setupComplete', () => {
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 3000);
                    });
                });
            </script>
        @endpush

    </div>
</div>
