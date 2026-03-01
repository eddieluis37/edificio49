{{-- GRÁFICA DINÁMICA --}}
<div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
    <h3 class="text-xl font-bold mb-6 text-slate-200">
        📈
        {{ $active_tab === 'sales' ? 'Evolución de Ventas' : ($active_tab === 'closures' ? 'Evolución de Cortes' : 'Vista Unificada') }}
    </h3>
    <div class="h-64">
        <canvas id="commandChart"></canvas>
    </div>
    {{-- <div wire:ignore>
        <canvas id="commandChart" class="w-full" style="height: 300px;"></canvas>
    </div> --}}
</div>
