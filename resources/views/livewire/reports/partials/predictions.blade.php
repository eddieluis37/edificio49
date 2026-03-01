{{-- PREDICCIONES --}}
<div class="glassmorphism rounded-2xl p-8 border border-slate-700/50 relative overflow-hidden" data-aos="fade-up">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-pink-500/5 to-cyan-500/5"></div>

    <div class="relative z-10">
        <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
            <span class="text-3xl">🔮</span>
            <span
                class="bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 bg-clip-text text-transparent">Proyección
                IA</span>
        </h3>

        <div class="text-center py-8">
            <p class="text-slate-300 mb-4">Basado en tendencias actuales, en los próximos 7 días:</p>
            <p
                class="text-6xl font-black bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent mb-2 number-3d">
                ${{ number_format($predictions['next_7_days'] ?? 0, 0) }}
            </p>
            <p class="text-slate-400">Promedio diario: ${{ number_format($predictions['daily_avg'] ?? 0, 2) }}</p>

            <div
                class="mt-8 p-6 rounded-xl bg-gradient-to-r from-purple-500/10 to-cyan-500/10 border border-purple-500/30">
                <p class="text-sm text-slate-300">💡 <strong>Insight:</strong>
                    @if (isset($predictions['trend']))
                        {{ $predictions['trend'] === 'growing' ? 'Tendencia de crecimiento positiva' : ($predictions['trend'] === 'insufficient_data' ? 'Datos insuficientes para predicción' : 'Mantén el ritmo actual') }}
                    @else
                        Datos insuficientes para predicción
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
