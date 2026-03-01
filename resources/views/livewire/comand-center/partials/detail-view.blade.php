{{-- VISTA DETALLE --}}
@if ($active_tab === 'sales' && $salesDetails)
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-xl font-bold mb-6 text-slate-200">📋 Detalle de Ventas</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400">Espacio</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400">Usuario</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400">Total</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach ($salesDetails as $rental)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-300 font-mono">{{ $rental->barcode }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $rental->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $rental->space->code ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $rental->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-right font-bold text-emerald-400">
                                ${{ number_format($rental->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-right text-slate-400">
                                {{ $rental->check_out->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $salesDetails->links() }}
        </div>
    </div>
@endif

@if ($active_tab === 'closures' && $closuresDetails)
    <div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
        <h3 class="text-xl font-bold mb-6 text-slate-200">📋 Detalle de Cortes</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400">#ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400">Cajero</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400">Esperado</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400">Real</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400">Diferencia</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach ($closuresDetails as $closure)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-300 font-mono">#{{ $closure->id }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">
                                {{ $closure->cashier ? $closure->cashier->name : 'General' }}</td>
                            <td class="px-4 py-3 text-sm text-right text-blue-400">
                                ${{ number_format($closure->expected_cash, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-right text-emerald-400">
                                ${{ number_format($closure->real_cash, 2) }}</td>
                            <td
                                class="px-4 py-3 text-sm text-right font-bold {{ $closure->difference >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $closure->difference >= 0 ? '+' : '' }}${{ number_format($closure->difference, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-slate-400">
                                {{ $closure->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $closuresDetails->links() }}
        </div>
    </div>
@endif
