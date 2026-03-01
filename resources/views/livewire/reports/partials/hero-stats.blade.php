{{-- HERO HOLOGRÁFICO --}}
<div class="relative overflow-hidden rounded-3xl p-8 md:p-12" data-aos="zoom-in">
    {{-- Fondo animado --}}
    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 via-purple-500/10 to-pink-500/10"></div>
    <div class="absolute inset-0 backdrop-blur-3xl"></div>

    {{-- Partículas flotantes --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="relative z-10">
        <h2 class="text-3xl md:text-4xl font-black text-center mb-12 bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-400 bg-clip-text text-transparent"
            data-aos="fade-up">
            💫 Resumen del Período
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            {{-- Total Ingresos --}}
            <div class="stat-card group" data-aos="flip-left" data-aos-delay="100">
                <div
                    class="relative p-6 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-green-500/20 border border-emerald-500/30 hover:border-emerald-400 transition-all duration-300 hover:scale-105 cursor-pointer">
                    <div
                        class="absolute inset-0 bg-emerald-500/5 rounded-2xl blur-xl group-hover:blur-2xl transition-all">
                    </div>
                    <div class="relative">
                        <p class="text-sm text-emerald-300 mb-2">💰 Ingresos Totales</p>
                        <p class="text-4xl md:text-5xl font-black text-white number-3d">
                            ${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Rentas --}}
            <div class="stat-card group" data-aos="flip-left" data-aos-delay="200">
                <div
                    class="relative p-6 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 hover:border-blue-400 transition-all duration-300 hover:scale-105 cursor-pointer">
                    <div class="absolute inset-0 bg-blue-500/5 rounded-2xl blur-xl group-hover:blur-2xl transition-all">
                    </div>
                    <div class="relative">
                        <p class="text-sm text-blue-300 mb-2">🎫 Total Rentas</p>
                        <p class="text-4xl md:text-5xl font-black text-white number-3d">
                            {{ number_format($stats['total_rentals']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Ticket Promedio --}}
            <div class="stat-card group" data-aos="flip-left" data-aos-delay="300">
                <div
                    class="relative p-6 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 hover:border-purple-400 transition-all duration-300 hover:scale-105 cursor-pointer">
                    <div
                        class="absolute inset-0 bg-purple-500/5 rounded-2xl blur-xl group-hover:blur-2xl transition-all">
                    </div>
                    <div class="relative">
                        <p class="text-sm text-purple-300 mb-2">📊 Ticket Promedio</p>
                        <p class="text-4xl md:text-5xl font-black text-white number-3d">
                            ${{ number_format($stats['avg_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Ocupación --}}
            <div class="stat-card group" data-aos="flip-left" data-aos-delay="400">
                <div
                    class="relative p-6 rounded-2xl bg-gradient-to-br from-orange-500/20 to-red-500/20 border border-orange-500/30 hover:border-orange-400 transition-all duration-300 hover:scale-105 cursor-pointer">
                    <div
                        class="absolute inset-0 bg-orange-500/5 rounded-2xl blur-xl group-hover:blur-2xl transition-all">
                    </div>
                    <div class="relative">
                        <p class="text-sm text-orange-300 mb-2">📈 Ocupación</p>
                        <p class="text-4xl md:text-5xl font-black text-white number-3d">
                            {{ number_format($stats['avg_occupation'], 1) }}%</p>
                    </div>
                </div>
            </div>

            {{-- Tiempo Promedio --}}
            <div class="stat-card group" data-aos="flip-left" data-aos-delay="500">
                <div
                    class="relative p-6 rounded-2xl bg-gradient-to-br from-yellow-500/20 to-amber-500/20 border border-yellow-500/30 hover:border-yellow-400 transition-all duration-300 hover:scale-105 cursor-pointer">
                    <div
                        class="absolute inset-0 bg-yellow-500/5 rounded-2xl blur-xl group-hover:blur-2xl transition-all">
                    </div>
                    <div class="relative">
                        <p class="text-sm text-yellow-300 mb-2">⏱️ Estadía Prom.</p>
                        <p class="text-4xl md:text-5xl font-black text-white number-3d">{{ $stats['avg_stay_time'] }}h
                        </p>
                    </div>
                </div>
            </div>

            {{-- Clientes Únicos --}}
            <div class="stat-card group" data-aos="flip-left" data-aos-delay="600">
                <div
                    class="relative p-6 rounded-2xl bg-gradient-to-br from-teal-500/20 to-cyan-500/20 border border-teal-500/30 hover:border-teal-400 transition-all duration-300 hover:scale-105 cursor-pointer">
                    <div class="absolute inset-0 bg-teal-500/5 rounded-2xl blur-xl group-hover:blur-2xl transition-all">
                    </div>
                    <div class="relative">
                        <p class="text-sm text-teal-300 mb-2">👥 Clientes</p>
                        <p class="text-4xl md:text-5xl font-black text-white number-3d">
                            {{ number_format($stats['unique_customers']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
