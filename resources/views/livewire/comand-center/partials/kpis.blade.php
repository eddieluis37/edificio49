{{-- KPIs DINÁMICOS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4" data-aos="fade-up">
    @if($active_tab === 'sales')
        {{-- KPIs Ventas --}}
        <div class="glassmorphism p-6 rounded-xl border border-emerald-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-emerald-300 mb-2">💰 Ingresos</p>
            <p class="text-3xl font-black text-white">${{ number_format($kpis['total_revenue'], 2) }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-blue-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-blue-300 mb-2">🎫 Rentas</p>
            <p class="text-3xl font-black text-white">{{ $kpis['total_rentals'] }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-purple-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-purple-300 mb-2">📊 Promedio</p>
            <p class="text-3xl font-black text-white">${{ number_format($kpis['avg_ticket'], 2) }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-cyan-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-cyan-300 mb-2">👥 Usuarios</p>
            <p class="text-3xl font-black text-white">{{ $kpis['unique_users'] }}</p>
        </div>
    @elseif($active_tab === 'closures')
        {{-- KPIs Cortes --}}
        <div class="glassmorphism p-6 rounded-xl border border-purple-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-purple-300 mb-2">🧾 Cortes</p>
            <p class="text-3xl font-black text-white">{{ $kpis['total_closures'] }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-blue-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-blue-300 mb-2">💵 Esperado</p>
            <p class="text-3xl font-black text-white">${{ number_format($kpis['total_expected'], 2) }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-emerald-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-emerald-300 mb-2">💰 Real</p>
            <p class="text-3xl font-black text-white">${{ number_format($kpis['total_real'], 2) }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-{{ $kpis['total_difference'] >= 0 ? 'emerald' : 'red' }}-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-{{ $kpis['total_difference'] >= 0 ? 'emerald' : 'red' }}-300 mb-2">{{ $kpis['total_difference'] >= 0 ? '✅' : '⚠️' }} Diferencia</p>
            <p class="text-3xl font-black text-white">{{ $kpis['total_difference'] >= 0 ? '+' : '' }}${{ number_format($kpis['total_difference'], 2) }}</p>
        </div>
    @else
        {{-- KPIs Unificados --}}
        <div class="glassmorphism p-6 rounded-xl border border-emerald-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-emerald-300 mb-2">💰 Ingresos</p>
            <p class="text-3xl font-black text-white">${{ number_format($kpis['total_revenue'], 2) }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-blue-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-blue-300 mb-2">🎫 Rentas</p>
            <p class="text-3xl font-black text-white">{{ $kpis['total_rentals'] }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-purple-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-purple-300 mb-2">🧾 Cortes</p>
            <p class="text-3xl font-black text-white">{{ $kpis['total_closures'] }}</p>
        </div>
        <div class="glassmorphism p-6 rounded-xl border border-cyan-500/30 hover:scale-105 transition-all cursor-pointer group">
            <p class="text-sm text-cyan-300 mb-2">🎯 Precisión</p>
            <p class="text-3xl font-black text-white">{{ number_format($kpis['accuracy'], 1) }}%</p>
        </div>
    @endif
</div>
