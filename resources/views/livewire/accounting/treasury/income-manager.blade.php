<div class="space-y-6">
    <!-- Header/Control Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/50 backdrop-blur-md dark:bg-gray-800/50 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Ingresos Varios</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm uppercase tracking-wide">Tesorería Central - Recaudos No Operacionales</p>
        </div>
        <div class="flex items-center gap-3">
             <button wire:click="openModal" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg hover:shadow-emerald-500/30 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Registrar Ingreso
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-100/80 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/20">
            <div class="relative w-full md:w-96">
                <input wire:model.live="search" type="text" placeholder="Buscar por descripción o referencia..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-center shadow-inner">Fecha</th>
                        <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-center">Concepto / Entidad</th>
                        <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-center">Cuentas (D/C)</th>
                        <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-center">Método</th>
                        <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-right">Monto Recibido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($incomes as $i)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500 dark:text-gray-400 text-center">{{ $i->date->format('d/M/Y') }}</td>
                        <td class="px-6 py-4 shadow-inner">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $i->description }}</div>
                            @if($i->owner)
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-1 font-medium bg-emerald-100/50 dark:bg-emerald-900/20 w-fit px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">
                                     <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                     {{ $i->owner->name }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-center">
                            <div class="flex flex-col gap-1 items-center">
                                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded-md border border-blue-200/50 dark:border-blue-800/80 w-full text-center">D: {{ $i->account->code }} ({{ Str::limit($i->account->name, 12) }})</span>
                                <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-md border border-indigo-200/50 dark:border-indigo-800/80 w-full text-center">C: {{ $i->counterpart->code }} ({{ Str::limit($i->counterpart->name, 12) }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                             <span class="px-3 py-1 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-[10px] font-black border border-gray-200 dark:border-gray-600 shadow-sm uppercase tracking-tighter">{{ $i->payment_method }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                             <div class="text-lg font-black text-emerald-600 dark:text-emerald-400">$ {{ number_format($i->amount, 0, ',', '.') }}</div>
                             @if($i->reference_doc) <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Doc: {{ $i->reference_doc }}</div> @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">No se encontraron registros de ingresos varios.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $incomes->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in shadow-2xl">
        <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] overflow-hidden border border-emerald-100 dark:border-emerald-900/50">
            <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-white dark:from-gray-700 dark:to-gray-800 flex justify-between items-center">
                <div>
                     <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-tight">Registrar Recaudo Vario</h3>
                     <p class="text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest mt-1">Nuevo Ingreso a Tesorería</p>
                </div>
                <button wire:click="$set('showModal', false)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-gray-700 text-gray-400 hover:text-red-500 transition-all border border-gray-200 dark:border-gray-600 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Fecha Recaudo</label>
                        <input type="date" wire:model="date" class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all">
                        @error('date') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Monto Recibido</label>
                        <div class="relative">
                            <span class="absolute left-5 top-4 font-bold text-gray-400">$</span>
                            <input type="number" wire:model="amount" class="w-full pl-10 pr-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all text-xl font-black">
                        </div>
                        @error('amount') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                     <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Cuenta Destino (Asset)</label>
                     <select wire:model="account_id" class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all font-bold">
                        <option value="">Seleccione cuenta (Caja/Banco)...</option>
                        @foreach($assetAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }} (Balance: ${{ number_format($acc->balance, 0) }})</option>
                        @endforeach
                     </select>
                </div>

                <div class="space-y-2">
                     <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Cuenta Contrapartida (Ingreso)</label>
                     <select wire:model="counterpart_account_id" class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all font-bold">
                        <option value="">Seleccione cuenta de ingreso...</option>
                        @foreach($incomeAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                     </select>
                     <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Asignará este ingreso a la cuenta seleccionada (Contrapartida)</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Medio de Pago</label>
                        <select wire:model="payment_method" class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all font-bold">
                           <option value="Transferencia">Transferencia</option>
                           <option value="Consignación">Consignación</option>
                           <option value="Efectivo">Efectivo</option>
                           <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Pagador (Opcional)</label>
                        <select wire:model="owner_id" class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all font-bold">
                           <option value="">Seleccione propietario...</option>
                           @foreach($owners as $owner)
                               <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                           @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Concepto / Comentario</label>
                    <textarea wire:model="description" rows="2" placeholder="Especifique el origen del ingreso..." class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all font-medium"></textarea>
                    @error('description') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-emerald-900/50 dark:text-emerald-400/50 uppercase tracking-widest">Ref. Transacción</label>
                    <input type="text" wire:model="reference_doc" placeholder="# Voucher / # Transferencia" class="w-full px-5 py-3.5 rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-white outline-none focus:border-emerald-500 transition-all font-bold tracking-widest text-emerald-600 dark:text-emerald-400">
                </div>
            </div>

            <div class="p-8 bg-gray-50/50 dark:bg-gray-700/30 flex gap-4">
                <button wire:click="$set('showModal', false)" class="flex-1 py-4 px-6 rounded-2xl font-black bg-white dark:bg-gray-600 text-gray-500 hover:text-red-500 dark:text-gray-200 border-2 border-gray-100 dark:border-gray-500 hover:bg-gray-50 transition-all uppercase tracking-widest text-xs">Cancelar</button>
                <button wire:click="save" class="flex-1 py-4 px-6 rounded-2xl font-black bg-emerald-600 text-white hover:bg-emerald-700 shadow-xl shadow-emerald-500/30 active:scale-[0.98] transition-all uppercase tracking-widest text-xs">Confirmar Recaudo</button>
            </div>
        </div>
    </div>
    @endif
</div>
