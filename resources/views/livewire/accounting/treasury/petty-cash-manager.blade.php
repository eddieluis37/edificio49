<div class="space-y-6">
    <!-- Header/Control Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/50 backdrop-blur-md dark:bg-gray-800/50 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Administración de Caja Menor</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Control de gastos menores y reembolsos de caja.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openModal" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg hover:shadow-primary-500/30 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nuevo Movimiento
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-500/10 to-blue-500/10 dark:from-indigo-500/20 dark:to-blue-500/20 p-6 rounded-3xl border border-blue-100/50 dark:border-blue-700/50 shadow-sm flex flex-col items-center">
            <div class="w-12 h-12 bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-gray-500 dark:text-gray-400 font-medium text-sm">Saldo Actual en Caja</span>
            <span class="text-4xl font-black text-gray-900 dark:text-white mt-1">$ {{ number_format($balance, 0, ',', '.') }}</span>
        </div>
        
        <div class="bg-gradient-to-br from-red-500/10 to-orange-500/10 dark:from-red-500/20 dark:to-orange-500/20 p-6 rounded-3xl border border-red-100/50 dark:border-red-700/50 shadow-sm flex flex-col items-center">
             <div class="w-12 h-12 bg-red-500/20 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            </div>
            <span class="text-gray-500 dark:text-gray-400 font-medium text-sm">Gastos (Este Mes)</span>
            <span class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">$ {{ number_format($movements->where('type','petty_cash_out')->sum('amount') ?? 0, 0, ',', '.') }}</span>
        </div>

        <div class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 dark:from-green-500/20 dark:to-emerald-500/20 p-6 rounded-3xl border border-green-100/50 dark:border-green-700/50 shadow-sm flex flex-col items-center">
             <div class="w-12 h-12 bg-green-500/20 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <span class="text-gray-500 dark:text-gray-400 font-medium text-sm">Reembolsos (Recibidos)</span>
            <span class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1 text-center">Control de Flujo</span>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100/80 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100/80 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search and Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <div class="relative w-full md:w-96">
                <input wire:model.live="search" type="text" placeholder="Buscar movimientos..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Descripción</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Cuenta Contrapartida</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($movements as $m)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600 dark:text-gray-300">{{ $m->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $m->description }}</div>
                            @if($m->reference_doc) <div class="text-xs text-gray-400 italic">Ref: {{ $m->reference_doc }}</div> @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $m->counterpart->code }} - {{ $m->counterpart->name }}
                        </td>
                        <td class="px-6 py-4">
                            @if($m->type === 'petty_cash_out')
                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-full text-xs font-bold border border-red-200 dark:border-red-800">EGRESO</span>
                            @else
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 rounded-full text-xs font-bold border border-green-200 dark:border-green-800">INGRESO / REEMBOLSO</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-lg font-bold {{ $m->type === 'petty_cash_out' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                {{ $m->type === 'petty_cash_out' ? '-' : '+' }}$ {{ number_format($m->amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No se encontraron movimientos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4">
            {{ $movements->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-white dark:from-gray-700 dark:to-gray-800 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Registrar Movimiento de Caja</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-8 space-y-6">
                 <!-- TIPO SELECTOR -->
                <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-2xl">
                    <button wire:click="$set('type', 'petty_cash_out')" class="flex-1 py-2.5 rounded-xl font-bold transition-all {{ $type === 'petty_cash_out' ? 'bg-white dark:bg-gray-600 text-red-600 dark:text-red-400 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">EGRESO (Gasto)</button>
                    <button wire:click="$set('type', 'petty_cash_in')" class="flex-1 py-2.5 rounded-xl font-bold transition-all {{ $type === 'petty_cash_in' ? 'bg-white dark:bg-gray-600 text-green-600 dark:text-green-400 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">INGRESO (Reembolso)</button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Fecha</label>
                        <input type="date" wire:model="date" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                        @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Monto</label>
                        <input type="number" wire:model="amount" placeholder="0" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Cuenta Contrapartida (Afecta)</label>
                    <select wire:model="counterpart_account_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                        <option value="">Seleccione cuenta...</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                    @error('counterpart_account_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">{{ $type === 'petty_cash_out' ? 'Cuenta de GASTO que se debita' : 'Cuenta de ORIGEN (Ej: Banco) que se acredita' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Descripción / Concepto</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 transition-all"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Referencia (Opcional)</label>
                    <input type="text" wire:model="reference_doc" placeholder="Factura #, Recibo, etc." class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
            </div>

            <div class="p-6 bg-gray-50/50 dark:bg-gray-700/30 flex gap-4">
                <button wire:click="$set('showModal', false)" class="flex-1 py-3 px-6 rounded-xl font-bold bg-white dark:bg-gray-600 text-gray-600 dark:text-gray-200 border border-gray-200 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 transition-all">Cancelar</button>
                <button wire:click="save" class="flex-1 py-3 px-6 rounded-xl font-bold bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-500/20 active:scale-95 transition-all">Registrar Movimiento</button>
            </div>
        </div>
    </div>
    @endif
</div>
