{{-- MAPA DE CALOR DE ESPACIOS --}}
<div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
    <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
        <span class="text-3xl">🔥</span>
        <span class="bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent">Top 5 Espacios Más
            Rentables</span>
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach ($topSpaces as $space)
            <div class="heatmap-cell p-6 rounded-xl bg-gradient-to-br from-orange-500/{{ 40 - $loop->index * 8 }} to-red-500/{{ 40 - $loop->index * 8 }} border-2 border-orange-500/50 hover:scale-110 transition-all cursor-pointer group"
                data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="text-center">
                    <p class="text-5xl font-black text-white mb-2 group-hover:scale-125 transition-transform">
                        {{ $space['space']->code }}</p>
                    <p class="text-2xl font-bold text-orange-200">${{ number_format($space['revenue'], 0) }}</p>
                    <p class="text-sm text-orange-300 mt-1">{{ $space['count'] }} rentas</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
