<div class="p-2 bg-gray-800 rounded shadow">
    <div class="flex gap-2 items-end">
        <div>
            <label class="block text-sm">Año</label>
            <input type="number" wire:model="year" class="border px-2 py-1 w-28 text-black">
        </div>
        <div>
            <label class="block text-sm">Mes</label>
            <input type="number" wire:model="month" class="border px-2 py-1 w-20 text-black">
        </div>
        <div>
            <button wire:click="generate" class="px-3 py-1 bg-green-600 text-white rounded">Generar cuotas mes</button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mt-2 text-sm text-green-300">{{ session('message') }}</div>
    @endif
</div>