<div class="px-4 pb-8 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Apartamentos y Propietarios</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Administra los apartamentos del edificio, sus coeficientes bases para cuotas y el propietario responsable.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="create()" class="block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">
                + Añadir Apartamento
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-4 shadow dark:bg-green-900/30">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('message') }}</h3>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg dark:ring-gray-700 bg-white dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th scope="col" class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Unidad</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Propietario / Responsable</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Garages</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Coeficiente</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Cuota Base Estimada</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Estado</th>
                                <th scope="col" class="relative py-4 pl-3 pr-4 sm:pr-6 md:pr-0 text-center"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($apartments as $apt)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $apt->code }}</span>
                                        <!-- Floor & Area details -->
                                        @if($apt->floor || $apt->area)
                                        <span class="text-xs text-gray-500">Piso {{ $apt->floor }} | {{ $apt->area }} m²</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    @if($apt->owner)
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $apt->owner->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $apt->owner->document_type }} {{ $apt->owner->document_number }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No asignado</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    @if($apt->garages->count() > 0)
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                            {{ $apt->garages->count() }} Garage(s)
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $apt->share_fraction }}%</span>
                                        <!-- Global coefficient insight -->
                                        @if($apt->garages->count() > 0)
                                            <span class="text-xs text-blue-600 dark:text-blue-400" title="Apartamento + Garajes">Total: {{ $apt->share_fraction + $apt->garages->sum('share_fraction') }}%</span>
                                        @endif
                                    </div>
                                </td>
                                @php
                                    $totalCoef = $apt->share_fraction + $apt->garages->sum('share_fraction');
                                    // Make sure it calculates percentage correctly:
                                    $coefMultiplier = $totalCoef > 1 ? ($totalCoef / 100) : $totalCoef;
                                    $fee = round($baseBudget * $coefMultiplier, 0);
                                @endphp
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-green-600 dark:text-green-400 text-lg">${{ number_format($fee, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-500 uppercase">Sin Honorarios</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $apt->status == 'occupied' ? 'bg-red-50 text-red-700 ring-red-600/20' : 'bg-green-50 text-green-700 ring-green-600/20' }}">
                                        {{ $apt->status == 'occupied' ? 'Ocupado' : 'Disponible' }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 md:pr-4 flex space-x-2 items-center justify-end">
                                    <button wire:click="edit({{ $apt->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Editar</button>
                                    <button wire:click="delete({{ $apt->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="confirm('ATENCIÓN: Se eliminará este apartamento y desvinculará propeitarios/garages e historial de facturas en caso de tener.\n\n¿Estás seguro de que quieres eliminar este Apartamento?') || event.stopImmediatePropagation()">Eliminar</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800">No se encontraron apartamentos creados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background element -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <div class="relative inline-block transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6 sm:align-middle">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3" id="modal-title">
                        {{ $apartment_id ? 'Editar Apartamento' : 'Registrar Nuevo Apartamento' }}
                    </h3>
                    
                    <div class="mt-4">
                        <form wire:submit.prevent="store">
                            
                            <!-- Sección Datos Inmueble -->
                            <div class="mb-5 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-md shadow-sm border border-gray-100 dark:border-gray-600">
                                <h4 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                    </svg>
                                    Detalles del Apartamento
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="code" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Código / Unidad <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="code" id="code" placeholder="Ej: Apt-101" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="share_fraction" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Coeficiente Base (%) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.0001" wire:model.defer="share_fraction" id="share_fraction" placeholder="Ej: 9.60" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                        @error('share_fraction') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-3">
                                    <div>
                                        <label for="floor" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Piso</label>
                                        <input type="number" wire:model.defer="floor" id="floor" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Nro.</label>
                                        <input type="number" wire:model.defer="number" id="number" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="area" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Área (m²)</label>
                                        <input type="number" step="0.01" wire:model.defer="area" id="area" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Estado <span class="text-red-500">*</span></label>
                                        <select wire:model.defer="status" id="status" class="mt-2 block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                            <option value="occupied">Ocupado</option>
                                            <option value="available">Disponible</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección Datos Propietario -->
                            <div class="mb-4 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-md shadow-sm border border-gray-100 dark:border-gray-600">
                                <div class="flex items-center justify-between mb-3 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <h4 class="text-md font-bold text-gray-800 dark:text-gray-200 flex items-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        Propietario / Dueño
                                    </h4>
                                    
                                    <div class="flex items-center h-5">
                                        <input wire:model.live="has_owner" id="has_owner" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-600 transition-colors">
                                        <label for="has_owner" class="ml-2 text-sm text-gray-900 dark:text-gray-300 break-words cursor-pointer">Vincular Propietario</label>
                                    </div>
                                </div>

                                @if($has_owner)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                    <div class="sm:col-span-2">
                                        <label for="owner_name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Nombres Completos o Razón Social <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="owner_name" id="owner_name" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                        @error('owner_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="owner_document_type" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Tipo Doc.</label>
                                        <select wire:model.defer="owner_document_type" id="owner_document_type" class="mt-2 block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                            <option value="CC">CC - Cédula</option>
                                            <option value="NIT">NIT - Empresa</option>
                                            <option value="CE">CE - Extranjería</option>
                                            <option value="PASSPORT">Pasaporte</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="owner_document_number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Número de Documento <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="owner_document_number" id="owner_document_number" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                        @error('owner_document_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="owner_email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Correo Electrónico</label>
                                        <input type="email" wire:model.defer="owner_email" id="owner_email" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                        @error('owner_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="owner_phone" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Teléfono</label>
                                        <input type="text" wire:model.defer="owner_phone" id="owner_phone" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-center italic py-2">
                                    No se asignará ningún propietario a este apartamento ahora.
                                </p>
                                @endif
                            </div>

                            <!-- Buttons -->
                            <div class="mt-6 flex items-center justify-end gap-x-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <button type="button" wire:click="closeModal()" class="inline-flex justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Cancelar</button>
                                <button type="submit" class="inline-flex justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Guardar Registro</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
