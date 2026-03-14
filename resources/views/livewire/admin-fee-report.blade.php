<div class="p-3 bg-white rounded-lg shadow">
    <div class="flex flex-wrap gap-2 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-600">Año</label>
            <input type="number" wire:model="year" class="border rounded px-2 py-1 w-24" />
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600">Mes</label>
            <input type="number" wire:model="month" class="border rounded px-2 py-1 w-20" />
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
        <div class="bg-blue-50 p-3 rounded border border-blue-100">
            <div class="text-xs text-gray-500 uppercase">Total Emitido</div>
            <div class="text-xl font-bold text-blue-700">${{ number_format($totalIssued, 2) }}</div>
        </div>
        <div class="bg-green-50 p-3 rounded border border-green-100">
            <div class="text-xs text-gray-500 uppercase">Total Cobrado</div>
            <div class="text-xl font-bold text-green-700">${{ number_format($totalCollected, 2) }}</div>
        </div>
        <div class="bg-yellow-50 p-3 rounded border border-yellow-100">
            <div class="text-xs text-gray-500 uppercase">Saldo Pendiente</div>
            <div class="text-xl font-bold text-yellow-700">${{ number_format($totalBalance, 2) }}</div>
        </div>
        <div class="bg-gray-50 p-3 rounded border border-gray-100">
            <div class="text-xs text-gray-500 uppercase">Facturas Nuevas</div>
            <div class="text-xl font-bold text-gray-700">{{ $newInvoices }}</div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="bg-white p-3 rounded border border-gray-200">
            <div class="text-xs text-gray-500 uppercase">Facturas pagadas</div>
            <div class="text-2xl font-bold">{{ $paidInvoicesCount }}</div>
        </div>
        <div class="bg-white p-3 rounded border border-gray-200">
            <div class="text-xs text-gray-500 uppercase">Periodo</div>
            <div class="text-lg font-bold">{{ $month }}/{{ $year }}</div>
        </div>
    </div>

    <div class="mt-4 bg-white p-3 rounded border border-gray-200">
        <div class="text-sm font-semibold text-gray-700">Top de deudores</div>
        <div class="mt-2 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase text-gray-500">
                        <th class="py-1">Propietario</th>
                        <th class="py-1">Saldo</th>
                        <th class="py-1">Facturas pendientes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDebtors as $owner)
                        <tr class="border-t border-gray-100">
                            <td class="py-1">{{ $owner['name'] }}</td>
                            <td class="py-1">${{ number_format($owner['balance'], 2) }}</td>
                            <td class="py-1">{{ $owner['pending_invoices'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-2 text-gray-500">No hay deudores en este periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
