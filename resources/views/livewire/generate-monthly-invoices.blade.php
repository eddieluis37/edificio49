<!-- Sub-componente para generar recibos masivos encapsulado en un diseño armónico -->
<div class="bg-gray-100 dark:bg-gray-800 p-2 sm:p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 w-full sm:w-auto">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        
        <div class="flex items-center gap-2">
            <div>
                <label class="block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Periodo (AÑO)</label>
                <input type="number" wire:model="year" class="block w-20 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-gray-700 dark:text-white dark:ring-gray-600 font-mono text-center">
            </div>
            
            <div>
                <label class="block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Periodo (MES)</label>
                <input type="number" wire:model="month" min="1" max="12" class="block w-16 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-gray-700 dark:text-white dark:ring-gray-600 font-mono text-center">
            </div>
        </div>

        <div class="self-end pb-0 sm:pb-0.5">
            <button wire:click="generate" wire:loading.attr="disabled" class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors tooltiped" title="Al dar clic, creará recibos para los 10 Aptos con los cálculos de la Asamblea">
                <svg wire:loading.remove class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <svg wire:loading class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generar Recibos del Mes
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mt-2 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 text-xs py-1 px-2 rounded font-medium flex items-center">
            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif
</div>