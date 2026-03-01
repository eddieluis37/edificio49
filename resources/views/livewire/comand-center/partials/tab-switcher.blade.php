{{-- TAB SWITCHER --}}
<div class="glassmorphism rounded-2xl p-2 border border-slate-700/50" data-aos="fade-down">
    <div class="grid grid-cols-3 gap-2">
        {{-- Tab Ventas --}}
        <button 
            wire:click="setTab('sales')"
            class="relative px-6 py-4 rounded-xl font-bold text-sm sm:text-base transition-all group {{ $active_tab === 'sales' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="hidden sm:inline">Ventas</span>
            </div>
        </button>

        {{-- Tab Cortes --}}
        <button 
            wire:click="setTab('closures')"
            class="relative px-6 py-4 rounded-xl font-bold text-sm sm:text-base transition-all group {{ $active_tab === 'closures' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg shadow-purple-500/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span class="hidden sm:inline">Cortes</span>
            </div>
        </button>

        {{-- Tab Unificado --}}
        <button 
            wire:click="setTab('unified')"
            class="relative px-6 py-4 rounded-xl font-bold text-sm sm:text-base transition-all group {{ $active_tab === 'unified' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg shadow-orange-500/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                <span class="hidden sm:inline">Todo</span>
            </div>
        </button>
    </div>
</div>
