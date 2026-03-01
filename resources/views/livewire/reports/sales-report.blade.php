<div>
    <div x-data="salesDashboard()" x-init="init()" @scroll.window="handleScroll">
        <div :class="{
            'theme-red': theme === 'red',
            'theme-orange': theme === 'orange',
            'theme-blue': theme === 'blue'
        }"
            class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black text-white overflow-x-hidden">

            {{-- NAVBAR STICKY --}}
            <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-slate-700/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <h1
                                class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                                📊 Dashboard
                            </h1>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Theme Switcher con glow --}}
                            <div
                                class="hidden sm:flex items-center gap-2 bg-slate-800/50 backdrop-blur rounded-2xl p-1.5 border border-slate-700/50">
                                <button @click="theme = 'red'"
                                    :class="theme === 'red' ?
                                        'bg-red-600 text-white shadow-lg shadow-red-500/50 scale-110' :
                                        'text-slate-400 hover:text-red-400'"
                                    class="p-2 rounded-xl transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="theme = 'orange'"
                                    :class="theme === 'orange' ?
                                        'bg-orange-600 text-white shadow-lg shadow-orange-500/50 scale-110' :
                                        'text-slate-400 hover:text-orange-400'"
                                    class="p-2 rounded-xl transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                                <button @click="theme = 'blue'"
                                    :class="theme === 'blue' ?
                                        'bg-blue-600 text-white shadow-lg shadow-blue-500/50 scale-110' :
                                        'text-slate-400 hover:text-blue-400'"
                                    class="p-2 rounded-xl transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Export Buttons --}}
                            <button wire:click="exportPdf"
                                class="px-4 py-2 bg-gradient-to-r from-red-500/20 to-pink-500/20 hover:from-red-500/30 hover:to-pink-500/30 border border-red-500/30 rounded-xl transition-all backdrop-blur flex items-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">PDF</span>
                            </button>
                            <button wire:click="exportExcel"
                                class="px-4 py-2 bg-gradient-to-r from-green-500/20 to-emerald-500/20 hover:from-green-500/30 hover:to-emerald-500/30 border border-green-500/30 rounded-xl transition-all backdrop-blur flex items-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Excel</span>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="pt-24 pb-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                    {{-- FILTROS --}}
                    @include('livewire.reports.partials.filters')

                    {{-- HERO HOLOGRÁFICO --}}
                    @include('livewire.reports.partials.hero-stats')

                    {{-- COMPARATIVA SPLIT --}}
                    @include('livewire.reports.partials.comparison')

                    {{-- GRÁFICA PRINCIPAL --}}
                    @include('livewire.reports.partials.main-chart')

                    {{-- BREAKDOWN POR VEHÍCULO --}}
                    @include('livewire.reports.partials.vehicle-breakdown')

                    {{-- MAPA DE CALOR DE ESPACIOS --}}
                    @include('livewire.reports.partials.heatmap')

                    {{-- TIMELINE HORARIOS PICO --}}
                    @include('livewire.reports.partials.peak-hours')

                    {{-- PODIO TOP 3 --}}
                    @include('livewire.reports.partials.podium')

                    {{-- TOP CLIENTES --}}
                    @include('livewire.reports.partials.top-customers')

                    {{-- PREDICCIONES --}}
                    @include('livewire.reports.partials.predictions')

                </div>
            </div>

            {{-- MODAL PREMIUM --}}
            @include('livewire.reports.partials.premium-modal')

        </div>

        {{-- ESTILOS Y SCRIPTS --}}
        @include('livewire.reports.partials.styles-and-scripts')
    </div>
</div>
