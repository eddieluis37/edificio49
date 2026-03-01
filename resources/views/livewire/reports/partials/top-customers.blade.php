{{-- TOP CLIENTES (Resto) --}}
@if (count($topCustomers) > 3)
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
            <span class="text-3xl">⭐</span>
            <span class="bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">Otros Clientes
                Destacados</span>
        </h3>

        <div class="space-y-3">
            @foreach (array_slice($topCustomers, 3) as $index => $customer)
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-cyan-500 transition-all flex items-center justify-between group"
                    data-aos="fade-left" data-aos-delay="{{ $index * 50 }}">
                    <div class="flex items-center gap-4">
                        <span
                            class="text-2xl font-bold text-slate-500 group-hover:text-cyan-400 transition-colors">#{{ $index + 4 }}</span>
                        <div>
                            <p class="font-bold text-white">{{ $customer['customer']->name }}</p>
                            <p class="text-sm text-slate-400">{{ $customer['visits'] }} visitas • Prom:
                                ${{ number_format($customer['avg_ticket'], 2) }}</p>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-cyan-400">${{ number_format($customer['revenue'], 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif
