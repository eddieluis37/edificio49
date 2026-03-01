{{-- VISTA RESUMEN --}}
@if($active_tab === 'sales' || $active_tab === 'unified')
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-xl font-bold mb-6 text-slate-200">💰 Ventas por Usuario</h3>
        <div class="space-y-3">
            @forelse($salesByUser as $index => $sale)
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-cyan-500 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl font-bold text-slate-500">{{ $index + 1 }}</span>
                        <div>
                            <p class="font-bold text-white">{{ $sale['user']->name }}</p>
                            <p class="text-sm text-slate-400">{{ $sale['total_rentals'] }} rentas • Prom: ${{ number_format($sale['avg_ticket'], 2) }}</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-cyan-400">${{ number_format($sale['total_revenue'], 2) }}</p>
                </div>
            @empty
                <p class="text-center text-slate-400 py-8">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>
@endif

@if($active_tab === 'closures' || $active_tab === 'unified')
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-xl font-bold mb-6 text-slate-200">🧾 Cortes por Usuario</h3>
        <div class="space-y-3">
            @forelse($closuresByUser as $index => $closure)
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-purple-500 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl font-bold text-slate-500">{{ $index + 1 }}</span>
                        <div>
                            <p class="font-bold text-white">{{ $closure['user']->name }}</p>
                            <p class="text-sm text-slate-400">{{ $closure['total_closures'] }} cortes • Esperado: ${{ number_format($closure['total_expected'], 2) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-purple-400">${{ number_format($closure['total_real'], 2) }}</p>
                        <p class="text-sm {{ $closure['total_difference'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $closure['total_difference'] >= 0 ? '+' : '' }}${{ number_format($closure['total_difference'], 2) }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-400 py-8">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>
@endif
