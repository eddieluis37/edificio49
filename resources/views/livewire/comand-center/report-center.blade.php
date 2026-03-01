<div>
    <div x-data="commandCenter()" x-init="init()">
        <div :class="{
            'theme-red': theme === 'red',
            'theme-orange': theme === 'orange',
            'theme-blue': theme === 'blue'
        }"
            class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black text-white">

            {{-- HEADER STICKY --}}
            <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-slate-700/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between gap-4">
                        {{-- Logo y Título --}}
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-purple-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                                    Command Center
                                </h1>
                                <p class="text-xs text-slate-400 hidden sm:block">Reporte Maestro</p>
                            </div>
                        </div>

                        {{-- Acciones Rápidas --}}
                        <div class="flex items-center gap-2">
                            {{-- Theme Switcher --}}
                            <div
                                class="hidden md:flex items-center gap-2 bg-slate-800/50 backdrop-blur rounded-xl p-1.5 border border-slate-700/50">
                                <button @click="theme = 'red'"
                                    :class="theme === 'red' ? 'bg-red-600 text-white shadow-lg shadow-red-500/50' :
                                        'text-slate-400'"
                                    class="p-2 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="theme = 'orange'"
                                    :class="theme === 'orange' ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/50' :
                                        'text-slate-400'"
                                    class="p-2 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                                <button @click="theme = 'blue'"
                                    :class="theme === 'blue' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/50' :
                                        'text-slate-400'"
                                    class="p-2 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Botones Export --}}
                            <button wire:click="exportPdf"
                                class="p-2.5 sm:px-4 sm:py-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 rounded-xl transition-all group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </button>
                            <button wire:click="exportExcel"
                                class="p-2.5 sm:px-4 sm:py-2 bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 rounded-xl transition-all group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </button>
                            <button wire:click="printReport"
                                class="hidden sm:block px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 rounded-xl transition-all group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                            </button>
                            <button wire:click="shareWhatsapp"
                                class="hidden sm:block px-4 py-2 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 rounded-xl transition-all group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="pt-24 pb-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                    {{-- TAB SWITCHER --}}
                    @include('livewire.comand-center.partials.tab-switcher')

                    {{-- FILTROS --}}
                    @include('livewire.comand-center.partials.filters')

                    {{-- KPIs --}}
                    @include('livewire.comand-center.partials.kpis')

                    {{-- MODE SELECTOR --}}
                    @include('livewire.comand-center.partials.mode-selector')

                    {{-- GRÁFICA --}}
                    @include('livewire.comand-center.partials.chart')

                    {{-- CONTENIDO DINÁMICO --}}
                    @if ($view_mode === 'summary')
                        @include('livewire.comand-center.partials.summary-view')
                    @else
                        @include('livewire.comand-center.partials.detail-view')
                    @endif

                    {{-- INSIGHTS AI --}}
                    @include('livewire.comand-center.partials.insights')

                </div>
            </div>

        </div>

        {{-- ESTILOS Y SCRIPTS --}}
        @include('livewire.comand-center.partials.styles-scripts')
    </div>
</div>
