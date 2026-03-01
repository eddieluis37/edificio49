{{-- MODE SELECTOR --}}
<div class="flex items-center justify-center gap-2 glassmorphism rounded-2xl p-2 border border-slate-700/50 max-w-md mx-auto" data-aos="zoom-in">
    <button 
        wire:click="setViewMode('summary')"
        class="flex-1 px-6 py-3 rounded-xl font-bold text-sm transition-all {{ $view_mode === 'summary' ? 'bg-gradient-to-r from-cyan-500 to-purple-500 text-white shadow-lg' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
        <div class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span>Resumen</span>
        </div>
    </button>
    <button 
        wire:click="setViewMode('detail')"
        class="flex-1 px-6 py-3 rounded-xl font-bold text-sm transition-all {{ $view_mode === 'detail' ? 'bg-gradient-to-r from-cyan-500 to-purple-500 text-white shadow-lg' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
        <div class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <span>Detalle</span>
        </div>
    </button>
</div>
