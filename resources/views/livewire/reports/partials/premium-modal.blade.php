<div>
    <div x-show="showPremiumModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto"
        @keydown.escape.window="showPremiumModal = false">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showPremiumModal = false"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"></div>

            <div class="relative bg-gradient-to-br from-slate-900 via-purple-900/50 to-slate-900 rounded-3xl p-8 max-w-2xl w-full border-2 border-purple-500/50 shadow-2xl shadow-purple-500/20"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                {{-- Confetti Background --}}
                <div class="absolute inset-0 overflow-hidden rounded-3xl">
                    <div class="absolute top-0 left-1/4 w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>
                    <div class="absolute top-10 right-1/4 w-2 h-2 bg-pink-400 rounded-full animate-ping"
                        style="animation-delay: 0.5s"></div>
                    <div class="absolute bottom-10 left-1/3 w-2 h-2 bg-cyan-400 rounded-full animate-ping"
                        style="animation-delay: 1s"></div>
                </div>

                <div class="relative z-10">
                    {{-- Header --}}
                    <div class="text-center mb-8">
                        <div
                            class="inline-block p-6 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 mb-4 animate-pulse">
                            <span class="text-6xl">💎</span>
                        </div>
                        <h3
                            class="text-3xl font-black bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 bg-clip-text text-transparent mb-2">
                            ¡Desbloquea el Poder Premium!
                        </h3>
                        <p class="text-slate-300">Exporta tus reportes en <span
                                x-text="exportType === 'pdf' ? 'PDF' : 'Excel'"
                                class="font-bold text-purple-400"></span></p>
                    </div>

                    {{-- Features --}}
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                            <span class="text-2xl">📊</span>
                            <p class="text-slate-200">Exporta reportes en PDF y Excel</p>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                            <span class="text-2xl">📈</span>
                            <p class="text-slate-200">Gráficas de alta resolución</p>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                            <span class="text-2xl">🎨</span>
                            <p class="text-slate-200">Personalización de branding</p>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                            <span class="text-2xl">⚡</span>
                            <p class="text-slate-200">Reportes automáticos programados</p>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                            <span class="text-2xl">🔮</span>
                            <p class="text-slate-200">Análisis predictivo con IA avanzada</p>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="text-center">
                        <button
                            class="px-8 py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 text-white font-bold text-lg rounded-2xl hover:shadow-2xl hover:shadow-purple-500/50 transition-all hover:scale-105 mb-4 w-full">
                            ✨ Actualizar a Premium
                        </button>
                        <button @click="showPremiumModal = false"
                            class="text-slate-400 hover:text-white transition-colors">
                            Tal vez después
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
