{{-- PODIO TOP 3 --}}
@if (count($topCustomers) >= 3)
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-2xl font-bold mb-6 text-center flex items-center justify-center gap-3">
            <span class="text-3xl">🏆</span>
            <span
                class="bg-gradient-to-r from-yellow-400 via-amber-400 to-orange-400 bg-clip-text text-transparent">Podio
                de Clientes VIP</span>
        </h3>

        <div class="flex items-end justify-center gap-4 md:gap-8">
            {{-- 2do Lugar --}}
            <div class="podium-2 flex-1 max-w-xs" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center mb-4">
                    <div class="inline-block p-4 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 mb-3">
                        <span class="text-4xl">🥈</span>
                    </div>
                    <p class="text-lg font-bold text-slate-200">{{ $topCustomers[1]['customer']->name }}</p>
                    <p class="text-2xl font-black text-white mt-2">${{ number_format($topCustomers[1]['revenue'], 0) }}
                    </p>
                    <p class="text-sm text-slate-400">{{ $topCustomers[1]['visits'] }} visitas</p>
                </div>
                <div class="h-32 bg-gradient-to-t from-slate-600 to-slate-400 rounded-t-xl"></div>
            </div>

            {{-- 1er Lugar --}}
            <div class="podium-1 flex-1 max-w-xs" data-aos="fade-up">
                <div class="text-center mb-4">
                    <div
                        class="inline-block p-4 rounded-full bg-gradient-to-br from-yellow-400 to-amber-600 mb-3 animate-pulse">
                        <span class="text-5xl">🥇</span>
                    </div>
                    <p class="text-xl font-bold text-yellow-200">{{ $topCustomers[0]['customer']->name }}</p>
                    <p class="text-3xl font-black text-white mt-2">${{ number_format($topCustomers[0]['revenue'], 0) }}
                    </p>
                    <p class="text-sm text-yellow-300">{{ $topCustomers[0]['visits'] }} visitas</p>
                </div>
                <div class="h-40 bg-gradient-to-t from-amber-600 to-yellow-400 rounded-t-xl"></div>
            </div>

            {{-- 3er Lugar --}}
            <div class="podium-3 flex-1 max-w-xs" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center mb-4">
                    <div class="inline-block p-4 rounded-full bg-gradient-to-br from-amber-700 to-orange-800 mb-3">
                        <span class="text-4xl">🥉</span>
                    </div>
                    <p class="text-lg font-bold text-orange-200">{{ $topCustomers[2]['customer']->name }}</p>
                    <p class="text-2xl font-black text-white mt-2">${{ number_format($topCustomers[2]['revenue'], 0) }}
                    </p>
                    <p class="text-sm text-orange-400">{{ $topCustomers[2]['visits'] }} visitas</p>
                </div>
                <div class="h-24 bg-gradient-to-t from-orange-800 to-amber-700 rounded-t-xl"></div>
            </div>
        </div>
    </div>
@endif
