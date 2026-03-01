{{-- FILTROS --}}
<div class="glassmorphism rounded-2xl p-6 border border-slate-700/50 shadow-2xl" data-aos="fade-down">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Período --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">📅 Período</label>
            <select wire:model.live="filter_period"
                class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white">
                <option value="today">Hoy</option>
                <option value="week">Esta Semana</option>
                <option value="month">Este Mes</option>
                <option value="year">Este Año</option>
                <option value="custom">Personalizado</option>
            </select>
        </div>

        {{-- Usuario --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">👤 Usuario</label>
            <select wire:model.live="filter_user_id"
                class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-white">
                <option value="">Todos</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Comparación --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">📊 Comparar con</label>
            <select wire:model.live="comparison_period"
                class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent text-white">
                <option value="previous">Período Anterior</option>
                <option value="last_year">Año Pasado</option>
            </select>
        </div>
    </div>

    @if ($filter_period === 'custom')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Desde</label>
                <input type="datetime-local" wire:model.live="date_from"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-cyan-500 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Hasta</label>
                <input type="datetime-local" wire:model.live="date_to"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-cyan-500 text-white">
            </div>
        </div>
    @endif
</div>
