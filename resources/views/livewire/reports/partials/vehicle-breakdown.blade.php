@if (count($vehicleBreakdown) > 0) {{-- Validar si hay datos --}}
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
            <span class="text-3xl">🚗</span>
            <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Desglose por Tipo de
                Vehículo</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($vehicleBreakdown as $vehicle)
                <div class="p-6 rounded-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-slate-700 hover:border-purple-500 transition-all hover:scale-105 group cursor-pointer"
                    data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-center">
                        <p class="text-4xl mb-3 group-hover:scale-125 transition-transform">
                            @switch($vehicle['type'])
                                @case('car')
                                    🚗
                                @break

                                @case('motorcycle')
                                    🏍️
                                @break

                                @case('truck')
                                    🚚
                                @break

                                @default
                                    🚙
                            @endswitch
                        </p>
                        <p class="text-slate-300 text-sm mb-2">{{ $vehicle['label'] }}</p>
                        <p class="text-2xl font-bold text-white mb-1">${{ number_format($vehicle['revenue'], 2) }}</p>
                        <p class="text-sm text-slate-400">{{ $vehicle['count'] }} rentas</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    {{-- Estado vacío --}}
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50 text-center" data-aos="fade-up">
        <p class="text-slate-400">📊 No hay datos de vehículos en este período</p>
    </div>
@endif
