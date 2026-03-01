{{-- COMPARATIVA SPLIT --}}
@if ($show_comparison && !empty($comparison))
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
            <span class="text-3xl">⚔️</span>
            <span class="bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Comparativa vs
                Período Anterior</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Comparación Ingresos --}}
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-slate-300">💰 Ingresos</span>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-bold {{ $comparison['revenue_trend'] === 'up' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                        {{ $comparison['revenue_trend'] === 'up' ? '↗' : '↘' }}
                        {{ number_format(abs($comparison['revenue_change']), 1) }}%
                    </span>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-xs text-slate-400">Actual</p>
                        <p class="text-2xl font-bold text-white">${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Anterior</p>
                        <p class="text-xl text-slate-400">${{ number_format($comparison['prev_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Comparación Rentas --}}
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-slate-300">🎫 Rentas</span>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-bold {{ $comparison['count_trend'] === 'up' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                        {{ $comparison['count_trend'] === 'up' ? '↗' : '↘' }}
                        {{ number_format(abs($comparison['count_change']), 1) }}%
                    </span>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-xs text-slate-400">Actual</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['total_rentals']) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Anterior</p>
                        <p class="text-xl text-slate-400">{{ number_format($comparison['prev_count']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
