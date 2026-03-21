<div class="px-4 pb-12 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- HEADER -->
    <div class="sm:flex sm:items-center justify-between mb-8 pb-5 border-b border-gray-200 dark:border-gray-700">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                <svg class="w-8 h-8 mr-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Libro Mayor y Flujo de Caja
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Analiza el estado financiero, ingresos versus egresos reales y conoce el fondo neto que queda a favor de la copropiedad durante los cierres mensuales.</p>
        </div>

        <div class="mt-4 sm:mt-0 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-sm border border-gray-200 dark:border-gray-700 flex space-x-2">
            <div>
                <label class="sr-only">Mes</label>
                <select wire:model.live="month" class="block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm font-bold dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                    <option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option>
                    <option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option>
                    <option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option>
                    <option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option>
                </select>
            </div>
            <div>
                <label class="sr-only">Año</label>
                <select wire:model.live="year" class="block w-24 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm font-bold dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                    <option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option>
                </select>
            </div>
        </div>
    </div>

    <!-- BALANCE GENERAL CARDS -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-8 overflow-hidden relative group">
        <!-- Abstract gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-emerald-50 dark:to-emerald-900/10 opacity-50 z-0"></div>
        
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-gray-700">
            <!-- Ingresos -->
            <div class="px-4 py-3 flex flex-col justify-center">
                <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 flex justify-center items-center">
                    <svg class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    INGRESOS ({{ \Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM YYYY') }})
                </p>
                <p class="text-3xl font-black text-gray-900 dark:text-white">${{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            
            <!-- Egresos -->
            <div class="px-4 py-3 flex flex-col justify-center">
                <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 flex justify-center items-center">
                    <svg class="h-4 w-4 mr-1 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                    EGRESOS ({{ \Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM YYYY') }})
                </p>
                <p class="text-3xl font-black text-gray-900 dark:text-white">${{ number_format($totalExpenses, 0, ',', '.') }}</p>
            </div>
            
            <!-- BALANCE -->
            <div class="px-4 py-3 pb-0 md:py-3 flex flex-col justify-center">
                <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 flex justify-center items-center">BALANCE NETO {{ \Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM') }}</p>
                @if($balance >= 0)
                    <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400">${{ number_format($balance, 0, ',', '.') }}</p>
                    <p class="text-xs text-emerald-500 mt-1 font-bold">+ Superávit de Operación</p>
                @else
                    <p class="text-4xl font-black text-rose-600 dark:text-rose-400">-${{ number_format(abs($balance), 0, ',', '.') }}</p>
                    <p class="text-xs text-rose-500 mt-1 font-bold">⚠️ Déficit (Salidas Superan Ingresos)</p>
                @endif
            </div>
        </div>
    </div>

    <!-- TIMELINE OF MOVEMENTS (LIBRO DIARIO) -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6 md:p-8">
        <h3 class="text-lg font-extrabold text-gray-900 dark:text-white mb-6 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-3">Libro Diario Transaccional</h3>
        
        @if(count($movements) > 0)
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @foreach($movements as $idx => $m)
                <li>
                    <div class="relative pb-8">
                        @if($idx !== count($movements) - 1)
                            <span class="absolute left-6 top-8 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex items-center space-x-5">
                            <div>
                                @if($m['type'] == 'income')
                                    <span class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/40 border-2 border-green-200 dark:border-green-800 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                @else
                                    <span class="h-12 w-12 rounded-full bg-rose-100 dark:bg-rose-900/40 border-2 border-rose-200 dark:border-rose-800 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                        <svg class="h-6 w-6 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </span>
                                @endif
                            </div>
                            <!-- Card Body -->
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5 p-4 rounded-xl {{ $m['type'] == 'income' ? 'bg-green-50/50 dark:bg-green-900/10 border border-green-100 dark:border-green-800/30' : 'bg-rose-50/50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800/30' }}">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $m['concept'] }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-lg">{{ $m['desc'] }}</p>
                                    <p class="mt-2 text-xs font-bold text-gray-400">Registrado el: {{ \Carbon\Carbon::parse($m['date'])->format('d M, Y') }}</p>
                                </div>
                                <div class="whitespace-nowrap text-right flex flex-col justify-center">
                                    <span class="text-xl font-black {{ $m['type'] == 'income' ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $m['type'] == 'income' ? '+' : '-' }} ${{ number_format($m['amount'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @else
            <div class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" /></svg>
                <h3 class="mt-2 text-sm font-bold text-gray-900 dark:text-white">Cierre Limpio</h3>
                <p class="mt-1 text-sm text-gray-500">No hay movimientos financieros generados para el corte de {{ \Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM') }} {{ $year }}.</p>
            </div>
        @endif
    </div>
</div>
