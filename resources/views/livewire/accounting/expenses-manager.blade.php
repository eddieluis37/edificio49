<div class="px-4 pb-12 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- HEADER -->
    <div class="sm:flex sm:items-center justify-between mb-8 pb-5 border-b border-gray-200 dark:border-gray-700">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                <svg class="w-8 h-8 mr-3 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path></svg>
                Gestión de Egresos y Proveedores
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Controla las cuentas por pagar, facturas de servicios, nómina de conserjería, aseo y demás salidas del edificio.</p>
        </div>

        <div class="mt-4 sm:mt-0 flex flex-col gap-2 sm:flex-row sm:items-center">
            <button wire:click="openSupplierModal" class="inline-flex justify-center items-center rounded-lg bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v2H9V7zm0 4h1v2H9v-2zm0 4h1v2H9v-2zm-2-8h1v2H7V7zm0 4h1v2H7v-2zm0 4h1v2H7v-2zm6-8h1v2h-1V7zm0 4h1v2h-1v-2zm0 4h1v2h-1v-2z"></path></svg>
                + Proveedor
            </button>
            <button wire:click="openExpenseModal" class="inline-flex justify-center items-center rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 transition-colors">
                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Registrar Cuenta por Pagar
            </button>
        </div>
    </div>

    <!-- METRICS KIPs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pendiente de Pagar -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 rounded-xl p-6 relative">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 p-8 bg-rose-500/10 rounded-full">
                <svg class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Obligaciones Pendientes (Cuentas por Pagar)</dt>
            <dd class="mt-2 text-3xl font-extrabold tracking-tight text-rose-600 dark:text-rose-500">{{ $totalPendingStr }}</dd>
            <dd class="mt-2 text-xs font-semibold text-gray-400 uppercase tracking-widest">Requieren atención pronto</dd>
        </div>

        <!-- Gastado en el Año -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 rounded-xl p-6 relative">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 p-8 bg-blue-500/10 rounded-full">
                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
            </div>
            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Gastado y Pagado</dt>
            <dd class="mt-2 text-3xl font-extrabold tracking-tight text-blue-600 dark:text-blue-500">{{ $totalPaidStr }}</dd>
            <dd class="mt-2 text-xs font-semibold text-gray-400 uppercase tracking-widest">Ejecución de Egresos año actual</dd>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 shadow-sm dark:bg-green-900/30 dark:border-green-800">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <div class="ml-3"><h3 class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('message') }}</h3></div>
            </div>
        </div>
    @endif

    <!-- FILTROS -->
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col sm:flex-row gap-4 flex-grow max-w-3xl">
            <div class="relative rounded-lg shadow-sm flex-grow">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full rounded-lg border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600" placeholder="Buscar concepto, factura, proveedor...">
            </div>
            
            <select wire:model.live="statusFilter" class="block w-full sm:w-64 rounded-lg border-0 py-2.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600 font-medium">
                <option value="">✦ Todos los Movimientos</option>
                <option value="pending">⚠️ Cuentas por Pagar (Pendientes)</option>
                <option value="paid">✅ Archivo Pagado</option>
            </select>
        </div>
    </div>

    <!-- MAIN GRID TABLE -->
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 sm:rounded-2xl overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="py-4 pl-4 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 sm:pl-6 leading-tight">Tipo / Categoría</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Proveedor / Beneficiario</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Monto del Gasto</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Fechas (Fra / Vence)</th>
                        <th scope="col" class="px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Estado</th>
                        <th scope="col" class="relative py-4 pl-3 pr-4 sm:pr-6"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700/50">
                    @forelse($expenses as $e)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <!-- Category / Ref -->
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                            <div class="flex items-center gap-3">
                                <span class="h-9 w-9 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 ring-1 ring-inset ring-gray-200 dark:ring-gray-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v2H9V7zm0 4h1v2H9v-2zm0 4h1v2H9v-2zm-2-8h1v2H7V7zm0 4h1v2H7v-2zm0 4h1v2H7v-2zm6-8h1v2h-1V7zm0 4h1v2h-1v-2zm0 4h1v2h-1v-2z" /></svg>
                                </span>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $e->category }}</div>
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Ref: {{ $e->reference ?? 'S/R' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Supplier -->
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="font-bold text-gray-900 dark:text-indigo-300 text-sm">{{ optional($e->supplier)->name ?? 'Indefinido' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]" title="{{ $e->description }}">{{ $e->description ?? '-' }}</div>
                        </td>

                        <!-- Amount -->
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="text-base font-extrabold text-gray-900 dark:text-white">
                                ${{ number_format($e->amount, 0, ',', '.') }}
                            </div>
                        </td>
                        
                        <!-- Dates -->
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex flex-col gap-1">
                                <span class="font-medium">Fra: {{ $e->date ? $e->date->format('d M y') : '-' }}</span>
                                @if($e->status === 'pending' && $e->due_date)
                                    <span class="font-bold {{ \Carbon\Carbon::now()->gt($e->due_date) ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500' }}">Vto: {{ $e->due_date->format('d M y') }}</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Status Badge -->
                        <td class="whitespace-nowrap px-3 py-4 text-center">
                            @if($e->status == 'paid')
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-bold text-blue-800 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">Pagado</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-bold text-rose-800 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">Por Pagar</span>
                            @endif
                        </td>
                        
                        <!-- Actions -->
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-2">
                            @if($e->status == 'pending')
                                <button wire:click="processInstantPayment({{ $e->id }})" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 font-bold tooltiped" title="Marcar rápidamente como Pagado">✔ Pagar</button>
                            @endif
                            <button wire:click="openExpenseModal({{ $e->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold ml-2">Editar</button>
                            <!-- Delete button (optional, usually accounting is never deleted just reversed, but kept for ease) -->
                            <button wire:click="deleteExpense({{ $e->id }})" wire:confirm="¿Seguro que deseas eliminar este registro de egreso contable?" class="text-red-500 hover:text-red-700 ml-2 font-bold px-1">X</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 px-4">
                            <div class="mx-auto h-16 w-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sin Egresos</h3>
                            <p class="text-sm text-gray-500">No tienes cuentas por cobrar pendientes con los filtros actuales.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- MODAL DE EGRESO (NUEVO / EDITAR) -->
    @if($showExpenseModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closeExpenseModal()"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block transform w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl text-left align-middle shadow-xl transition-all sm:my-8 px-6 pt-6 pb-6">
                <!-- Modal header -->
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path></svg>
                        {{ $expense_id ? 'Editar Cuenta / Egreso' : 'Registrar Nuevo Egreso' }}
                    </h3>
                    <button wire:click="closeExpenseModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>

                <form wire:submit.prevent="saveExpense" class="space-y-4">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Categoría -->
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Categoría (PUC)</label>
                            <select wire:model.defer="e_category" class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-rose-600 sm:text-sm dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('e_category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Proveedor -->
                        <div class="sm:col-span-1">
                            <label class="flex justify-between text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">
                                Proveedor / Empresa
                                <a href="#" wire:click.prevent="openSupplierModal" class="text-rose-600 hover:underline text-xs">+ Nuevo</a>
                            </label>
                            <select wire:model.defer="e_supplier_id" class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-rose-600 sm:text-sm dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                                <option value="">Selecciona beneficiario...</option>
                                @foreach($suppliersList as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                            @error('e_supplier_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Referencia Fra -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Factura de Cobro Nro (Opcional)</label>
                            <input type="text" wire:model.defer="e_reference" class="mt-1 block w-full rounded-md shadow-sm border-0 py-2 px-3 text-gray-900 dark:bg-gray-700 dark:text-white ring-1 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-rose-600 sm:text-sm" placeholder="# EPM-2023...">
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-300">Monto Total a Pagar (COP) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-lg font-bold">$</span></div>
                                <input type="number" step="1" wire:model.defer="e_amount" class="block w-full rounded-md border-0 py-2 pl-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-rose-600 sm:text-lg dark:bg-gray-700 dark:text-white dark:ring-gray-600 font-extrabold text-rose-600">
                            </div>
                            @error('e_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Fecha de Radicación <span class="text-red-500">*</span></label>
                            <input type="date" wire:model.defer="e_date" class="mt-1 block w-full rounded-md shadow-sm border-0 py-2 px-3 text-gray-900 dark:bg-gray-700 dark:text-white ring-1 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-rose-600 sm:text-sm">
                            @error('e_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Fecha de Vencimiento de este Gasto</label>
                            <input type="date" wire:model.defer="e_due_date" class="mt-1 block w-full rounded-md shadow-sm border-0 py-2 px-3 text-gray-900 dark:bg-gray-700 dark:text-white ring-1 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-rose-600 sm:text-sm">
                        </div>
                    </div>

                    <!-- Descripcion -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Detalles o Descripción del servicio</label>
                        <textarea wire:model.defer="e_description" rows="2" class="mt-1 block w-full rounded-md shadow-sm border-0 py-2 px-3 text-gray-900 dark:bg-gray-700 dark:text-white ring-1 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-rose-600 sm:text-sm"></textarea>
                    </div>

                    <!-- Status -->
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Estado Inmediato del Pago</label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.defer="e_status" value="pending" class="h-4 w-4 text-rose-600 focus:ring-rose-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm font-bold text-gray-700 dark:text-gray-300">Dejar "Por Pagar" en el sistema</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.defer="e_status" value="paid" class="h-4 w-4 text-rose-600 focus:ring-rose-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm font-bold text-gray-700 dark:text-gray-300">Ya lo pagué (Registrar salida efectiva)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" wire:click="closeExpenseModal()" class="rounded-md bg-white dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancelar</button>
                        <button type="submit" class="rounded-md bg-rose-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-rose-500 transition-colors focus:ring-2 leading-6">Guardar Registro en Contabilidad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL PROVEEDOR (MINIVAL) -->
    @if($showSupplierModal)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="closeSupplierModal()"></div>
            <div class="relative transform bg-white dark:bg-gray-800 rounded-xl shadow-xl transition-all sm:w-full sm:max-w-md px-6 py-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Directorio: Archivar Proveedor</h3>
                
                @if(session()->has('sup_message'))
                    <div class="mb-4 text-sm text-green-600">{{ session('sup_message') }}</div>
                @endif
                
                <form wire:submit.prevent="saveSupplier" class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Razón Social / Nombre Empresa <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="supplier_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">NIT o Cédula</label>
                        <input type="text" wire:model.defer="supplier_nit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Teléfono</label>
                            <input type="text" wire:model.defer="supplier_phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Tipo de Servicio Ofrecido</label>
                            <input type="text" wire:model.defer="supplier_service" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ej. Aseo, Mantenimiento">
                        </div>
                    </div>
                    
                    <div class="mt-5 flex justify-end gap-2">
                        <button type="button" wire:click="closeSupplierModal()" class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 rounded-md text-gray-700 dark:text-gray-300">Cerrar</button>
                        <button type="submit" class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-500 font-bold">Inscribir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
