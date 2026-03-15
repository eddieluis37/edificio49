<div class="px-4 pb-8 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lista de Garages</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Administra todos los garages vinculados o no a apartamentos, y sus coeficientes base.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="create()" class="block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">
                + Añadir Garage
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
                                <th scope="col" class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Código</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Apartamento</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Propietario</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Coeficiente</th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Estado</th>
                                <th scope="col" class="relative py-4 pl-3 pr-4 sm:pr-6 md:pr-0 text-center"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($garages as $garage)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    {{ $garage->code }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ optional($garage->apartment)->code ?? 'No asignado' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ optional($garage->owner)->name ?? 'No asignado' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $garage->share_fraction }}%
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $garage->status == 'occupied' ? 'bg-red-50 text-red-700 ring-red-600/20' : 'bg-green-50 text-green-700 ring-green-600/20' }}">
                                        {{ $garage->status == 'occupied' ? 'Ocupado' : 'Disponible' }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 md:pr-4 flex space-x-2 items-center justify-end">
                                    <button wire:click="edit({{ $garage->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Editar</button>
                                    <button wire:click="delete({{ $garage->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="confirm('¿Estás seguro de que quieres eliminar este garage?') || event.stopImmediatePropagation()">Eliminar</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800">No se encontraron garages.</td>
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

            <div class="relative inline-block transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl sm:p-6 sm:align-middle">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                        {{ $garage_id ? 'Editar Garage' : 'Crear Garage' }}
                    </h3>
                    <div class="mt-4">
                        <form wire:submit.prevent="store">
                            
                            <!-- Garage Code -->
                            <div class="mb-4">
                                <label for="code" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Código Del Garage</label>
                                <input type="text" wire:model.defer="code" id="code" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Apartment List -->
                                <div class="mb-4">
                                    <label for="apartment_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Apartamento Vinculado</label>
                                    <select wire:model.defer="apartment_id" id="apartment_id" class="mt-2 block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                        <option value="">-- Ninguno --</option>
                                        @foreach($apartments as $apt)
                                            <option value="{{ $apt->id }}">{{ $apt->code }}</option>
                                        @endforeach
                                    </select>
                                    @error('apartment_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Owner List -->
                                <div class="mb-4">
                                    <label for="owner_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Propietario</label>
                                    <select wire:model.defer="owner_id" id="owner_id" class="mt-2 block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                        <option value="">-- Ninguno --</option>
                                        @foreach($owners as $owner)
                                            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('owner_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Coeficiente (Share fraction) -->
                                <div class="mb-4">
                                    <label for="share_fraction" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Coeficiente (%)</label>
                                    <input type="number" step="0.0001" wire:model.defer="share_fraction" id="share_fraction" placeholder="Ej: 1.99" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6">
                                    @error('share_fraction') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Estado</label>
                                    <select wire:model.defer="status" id="status" class="mt-2 block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:bg-gray-700 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm">
                                        <option value="occupied">Ocupado</option>
                                        <option value="available">Disponible</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="mt-6 flex items-center justify-end gap-x-3">
                                <button type="button" wire:click="closeModal()" class="inline-flex justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Cancelar</button>
                                <button type="submit" class="inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
