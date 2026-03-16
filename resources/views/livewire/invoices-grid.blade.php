<div class="px-4 pb-12 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- HEADER -->
    <div class="sm:flex sm:items-center justify-between mb-8 pb-5 border-b border-gray-200 dark:border-gray-700">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Gestión de Cuotas y Recaudos
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Control maestro de facturación. Consulta el estado del crédito de propietarios e injecta pagos y abonos interactivamente.</p>
        </div>

        <div class="mt-4 sm:mt-0 flex flex-col items-end">
            <!-- Embeber el generador automático de facturas con diseño optimizado -->
            <livewire:generate-monthly-invoices />
        </div>
    </div>

    <!-- METRICS KIPs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Pendiente -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 rounded-xl p-6 relative">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 p-8 bg-amber-500/10 rounded-full">
                <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Deuda (En Cartera)</dt>
            <dd class="mt-2 text-3xl font-extrabold tracking-tight text-amber-600 dark:text-amber-500">{{ $totalPendingStr }}</dd>
            <dd class="mt-2 text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ $pendingCount }} Recibos Atrasados</dd>
        </div>

        <!-- Recaudado -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 rounded-xl p-6 relative">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 p-8 bg-emerald-500/10 rounded-full">
                <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400">Dinero Total Recaudado (Histórico)</dt>
            <dd class="mt-2 text-3xl font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">{{ $totalCollectedStr }}</dd>
            <dd class="mt-2 text-xs font-semibold text-gray-400 uppercase tracking-widest">Fondos Seguros en Bancos</dd>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col sm:flex-row gap-4 flex-grow max-w-3xl">
            <div class="relative rounded-lg shadow-sm flex-grow">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full rounded-lg border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600" placeholder="Buscar unidad, cédula, nombre, recibo...">
            </div>
            
            <select wire:model.live="statusFilter" class="block w-full sm:w-64 rounded-lg border-0 py-2.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600 font-medium">
                <option value="">✦ Mostrar Todos los Recibos</option>
                <option value="pending">⚠️ Solo Con Deuda (Pendientes/Parciales)</option>
                <option value="paid">✅ Solo Pagados al 100%</option>
            </select>
        </div>
        
        <div class="text-sm font-medium text-gray-500 whitespace-nowrap">
            {{ $invoices->total() }} registros encontrados
        </div>
    </div>

    <!-- MAIN GRID TABLE -->
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 sm:rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="py-4 pl-4 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 sm:pl-6 leading-tight">Documento</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Unidad / Residente</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Fechas (Emisión y Límite)</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Balance Financiero</th>
                        <th scope="col" class="px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 leading-tight">Estado</th>
                        <th scope="col" class="relative py-4 pl-3 pr-4 sm:pr-6"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700/50">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition-colors group cursor-pointer" wire:click="viewDetails({{ $invoice->id }})">
                        <!-- Document -->
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                            <div class="flex items-center">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 ring-1 ring-inset ring-gray-200 dark:ring-gray-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 dark:group-hover:bg-indigo-900/30 dark:group-hover:text-indigo-400 transition-colors">
                                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </span>
                                <div class="ml-4">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $invoice->number }}</div>
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $invoice->service->name ?? 'Aportes Admon.' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Unit / Residente -->
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="font-bold text-gray-900 dark:text-indigo-300 text-sm">{{ $invoice->apartment->code ?? 'Indefinida' }}</div>
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate max-w-[200px]" title="{{ optional($invoice->owner)->name ?? 'No asignado' }}">
                                {{ optional($invoice->owner)->name ?? 'Sin Propietario' }}
                            </div>
                        </td>
                        
                        <!-- Dates -->
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex flex-col gap-1">
                                <span class="font-medium"><span class="text-gray-400 dark:text-gray-500 font-normal">Emitida:</span> {{ \Carbon\Carbon::parse($invoice->date)->format('d M, Y') }}</span>
                                <span class="font-bold {{ \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($invoice->due_date)) && $invoice->balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    <span class="text-gray-400 dark:text-gray-500 font-normal">Límite:</span> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Financial Balance -->
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5 line-through decoration-1 decoration-gray-300 dark:decoration-gray-600">Total: ${{ number_format($invoice->final_amount, 0, ',', '.') }}</span>
                                <span class="text-sm font-extrabold text-gray-900 dark:text-white">
                                    Debe: <span class="{{ $invoice->balance > 0 ? 'text-amber-600 dark:text-amber-500' : 'text-emerald-600 dark:text-emerald-500' }}">${{ number_format($invoice->balance, 0, ',', '.') }}</span>
                                </span>
                            </div>
                        </td>
                        
                        <!-- Status Badge -->
                        <td class="whitespace-nowrap px-3 py-4 text-center">
                            @if($invoice->status == 'paid')
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 hidden sm:block"></span> PAZ Y SALVO
                                </span>
                            @elseif($invoice->status == 'partially')
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 hidden sm:block"></span> PAGO PARCIAL
                                </span>
                            @else
                                @if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($invoice->due_date)))
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-800 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5 hidden sm:block"></span> VENCIDA
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 hidden sm:block"></span> PENDIENTE
                                    </span>
                                @endif
                            @endif
                        </td>
                        
                        <!-- Action -->
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <span class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold inline-flex items-center group-hover:underline">
                                Procesar / Ver
                                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 px-4">
                            <div class="mx-auto h-20 w-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="mt-2 text-lg font-bold text-gray-900 dark:text-white">Sin Recibos Almacenados</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">No se encontraron facturas o las que buscas aún no han sido generadas. Modifica tus filtros.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación con estilo -->
        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- MODAL (SLIDE-OVER / DETALLES DE FACTURA) -->
    @if($showPaymentModal && $selectedInvoice)
    <div class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closePayModal()"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                    <!-- Slide-over pannel -->
                    <div class="pointer-events-auto w-screen max-w-2xl transform transition-all">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-900 shadow-2xl">
                            
                            <!-- Header Slide -->
                            <div class="px-4 py-6 sm:px-6 bg-indigo-700 dark:bg-indigo-900 text-white">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-xl font-bold leading-6" id="slide-over-title">Centro de Cobros - #{{ $selectedInvoice->number }}</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" wire:click="closePayModal()" class="relative rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                            <span class="absolute -inset-2.5"></span>
                                            <span class="sr-only">Cerrar panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-indigo-200">
                                    Propiedad: <span class="font-bold text-white">{{ $selectedInvoice->apartment->code ?? 'N/A' }}</span> &middot; Propietario: <span class="text-white">{{ optional($selectedInvoice->owner)->name ?? 'Indefinido' }}</span>
                                </div>
                            </div>

                            <!-- Contenido Principal -->
                            <div class="relative flex-1 px-4 py-6 sm:px-6">
                                
                                @if(session()->has('success_payment'))
                                    <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 shadow-sm dark:bg-green-900/30 dark:border-green-800">
                                        <div class="flex">
                                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success_payment') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('error'))
                                    <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200 shadow-sm dark:bg-red-900/30 dark:border-red-800">
                                        <div class="flex">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Resumen Financiero -->
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-100 dark:border-gray-700/50 mb-8 space-y-4 shadow-sm">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-2 flex items-center">
                                        <svg class="h-4 w-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        Extracto del Análisis
                                    </h3>
                                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Inicial Cobrado</dt>
                                            <dd class="mt-1 text-lg font-bold text-gray-900 dark:text-white">${{ number_format($selectedInvoice->final_amount, 0, ',', '.') }}</dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado de Exigibilidad</dt>
                                            <dd class="mt-1 text-sm font-bold {{ \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($selectedInvoice->due_date)) && $selectedInvoice->balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                                Venció el {{ \Carbon\Carbon::parse($selectedInvoice->due_date)->format('d M y') }}
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <div class="w-full bg-indigo-50 dark:bg-indigo-900/30 rounded-lg p-4 border border-indigo-100 dark:border-indigo-800/50 flex justify-between items-center">
                                                <span class="text-sm font-semibold text-indigo-800 dark:text-indigo-300">Saldo Pendiente Real:</span>
                                                <span class="text-2xl font-black text-indigo-700 dark:text-indigo-400">${{ number_format($selectedInvoice->balance, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Registro de Historial de Pagos Anteriores -->
                                @if($selectedInvoice->payments->count() > 0)
                                <div class="mb-8">
                                    <h3 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Historial de Abonos Aplicados a este recibo
                                    </h3>
                                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 rounded-lg overflow-hidden">
                                        @foreach($selectedInvoice->payments as $payment)
                                            <li class="px-4 py-3 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/50 border border-green-200 dark:border-green-800">
                                                            <span class="text-xs font-bold text-green-700 dark:text-green-400">#{{ $payment->id }}</span>
                                                        </span>
                                                        <div class="flex flex-col">
                                                            <span class="font-bold text-gray-900 dark:text-white capitalize text-sm">{{ $payment->method }}</span>
                                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($payment->date)->format('d M, Y') }}</span>
                                                            @if($payment->reference)
                                                                <span class="text-xs text-gray-400 italic">Ref: {{ $payment->reference }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="font-black text-green-600 dark:text-green-400">
                                                        + ${{ number_format($payment->amount, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Formulario de Pagos / Abonos -->
                                @if($selectedInvoice->balance > 0)
                                    <h3 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Ingresar Nuevo Abono Bancario</h3>
                                    <form wire:submit.prevent="registerPayment" class="space-y-5 bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 p-5 rounded-xl border-l-4 border-indigo-500">
                                        
                                        <!-- Monto -->
                                        <div>
                                            <label for="payAmount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300 text-center">Cantidad Abono (COP) a restar a la deuda</label>
                                            <div class="relative mt-2 rounded-md shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-2xl font-bold">$</span>
                                                </div>
                                                <input type="number" step="1" wire:model.defer="payAmount" id="payAmount" class="block w-full rounded-md border-0 py-4 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-3xl font-black text-center dark:bg-gray-700 dark:text-white dark:ring-gray-600 bg-gray-50 transition-colors">
                                            </div>
                                            @error('payAmount') <span class="text-red-500 text-xs font-semibold mt-1 block text-center">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <!-- Fecha -->
                                            <div>
                                                <label for="payDate" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Fecha de Consignación <span class="text-red-500">*</span></label>
                                                <input type="date" wire:model.defer="payDate" id="payDate" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                                @error('payDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            <!-- Método de pago -->
                                            <div>
                                                <label for="payMethod" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Medio Entrante <span class="text-red-500">*</span></label>
                                                <select wire:model.defer="payMethod" id="payMethod" class="mt-2 block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium">
                                                    <option value="transferencia_bancolombia">Transferencia Bancolombia</option>
                                                    <option value="nequi">Abono Nequi</option>
                                                    <option value="daviplata">Abono Daviplata</option>
                                                    <option value="efectivo_caja">Efectivo (Entregado en Mano)</option>
                                                    <option value="efectivo_consignado">Consignación Bancaria Corresponsal</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="otro">Otro Método Especial</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Referencia -->
                                        <div>
                                            <label for="payReference" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Nro. de Comprobante (Voucher / Tirilla) y Notas Extras</label>
                                            <input type="text" wire:model.defer="payReference" id="payReference" placeholder="Ej: Transferencia app cuenta terminada en 424. Reporta el contador..." class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm italic">
                                        </div>

                                        <!-- Buttons Submit -->
                                        <div class="pt-4 flex flex-col gap-3 sm:flex-row sm:justify-end">
                                            <button type="submit" class="inline-flex w-full justify-center items-center rounded-md bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-indigo-500 sm:w-auto transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
                                                Aplicar Abono Irreversible
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="rounded-xl border-dashed border-2 border-emerald-300 dark:border-emerald-600/50 bg-emerald-50 dark:bg-emerald-900/10 p-8 text-center mt-6">
                                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 dark:bg-emerald-900">
                                            <svg class="h-10 w-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <h3 class="mt-4 text-xl font-bold text-emerald-900 dark:text-emerald-300">¡Factura Cancelada Exitosamente!</h3>
                                        <p class="mt-2 text-emerald-700 dark:text-emerald-400 font-medium">Este periodo registra cero ($0) en deuda de aportes.</p>
                                    </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>