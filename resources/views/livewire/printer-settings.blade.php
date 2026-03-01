<div>
    <x-themed-layout storage-key="parkiTheme">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-red-600 to-gray-400 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración de Impresoras</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Gestiona tus impresoras térmicas</p>
                    </div>
                </div>


                <x-button-primary wire:click="openForm">
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Nueva Impresora</span>
                    </span>
                </x-button-primary>
            </div>

            {{-- Lista de Impresoras --}}
            @if ($printers->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                    <div
                        class="w-20 h-20 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hay impresoras configuradas
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Agrega tu primera impresora para comenzar</p>
                    <button wire:click="openForm"
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-all">
                        Agregar Impresora
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($printers as $printer)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all">

                            {{-- Header del Card --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br {{ $printer->is_default ? 'from-green-500 to-emerald-600' : 'from-gray-400 to-gray-600' }} rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $printer->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($printer->driver) }} · {{ $printer->paper_width }}mm</p>
                                    </div>
                                </div>

                                @if ($printer->is_default)
                                    <span
                                        class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs font-bold rounded-full">
                                        Predeterminada
                                    </span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400 w-24">Conexión:</span>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($printer->connection_type) }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400 w-24">Ruta:</span>
                                    <span
                                        class="font-mono text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $printer->connection_string }}</span>
                                </div>
                            </div>

                            {{-- Settings --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if ($printer->getSetting('show_logo'))
                                    <span
                                        class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs rounded-full">Logo</span>
                                @endif
                                @if ($printer->getSetting('show_barcode'))
                                    <span
                                        class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 text-xs rounded-full">Código
                                        barras</span>
                                @endif
                                @if ($printer->getSetting('show_vehicle_info'))
                                    <span
                                        class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 text-xs rounded-full">Info
                                        vehículo</span>
                                @endif
                                <span
                                    class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded-full">{{ $printer->getSetting('copies', 1) }}
                                    copia(s)</span>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2">
                                <button wire:click="testPrinter({{ $printer->id }})"
                                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all">
                                    Probar
                                </button>

                                @if (!$printer->is_default)
                                    <button wire:click="setAsDefault({{ $printer->id }})"
                                        class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-all">
                                        Predeterminada
                                    </button>
                                @endif

                                <button wire:click="edit({{ $printer->id }})"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm font-semibold rounded-lg transition-all">
                                    Editar
                                </button>

                                @if (!$printer->is_default)
                                    <button wire:click="delete({{ $printer->id }})"
                                        wire:confirm="¿Eliminar esta impresora?"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Modal de Formulario --}}
            @if ($showForm)
                <div x-data="{ show: @entangle('showForm').live }" x-show="show" x-cloak @keydown.escape.window="$wire.closeForm()"
                    class="fixed inset-0 z-50 overflow-y-auto ">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="$wire.closeForm()"></div>

                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div x-show="show" x-transition
                            class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl"
                            @click.stop>
                            {{-- Header --}}
                            <div
                                class="bg-gradient-to-r from-slate-900 via-theme-primary to-theme-secondary text-white px-6 py-4 rounded-t-2xl">
                                <h3 class="text-xl font-bold text-white">
                                    {{ $editingId ? 'Editar Impresora' : 'Nueva Impresora' }}
                                </h3>
                            </div>

                            {{-- Body --}}
                            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

                                {{-- Nombre --}}
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                                    <input type="text" wire:model="name"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Driver --}}
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Driver</label>
                                    <select wire:model="driver"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                        <option value="escpos">ESC/POS (Térmica)</option>
                                        <option value="browser">Navegador</option>
                                    </select>
                                </div>

                                {{-- Conexión --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tipo
                                            de Conexión</label>
                                        <select wire:model.live="connection_type"
                                            class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                            <option value="usb">USB</option>
                                            <option value="network">Red</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Ancho
                                            de Papel</label>
                                        <select wire:model="paper_width"
                                            class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                            <option value="58">58mm</option>
                                            <option value="80">80mm</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Ruta/IP --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ $connection_type === 'usb' ? 'Nombre de Impresora' : 'IP y Puerto' }}
                                    </label>
                                    <input type="text" wire:model="connection_string"
                                        placeholder="{{ $connection_type === 'usb' ? 'TM20' : '192.168.1.100:9100' }}"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white font-mono text-sm">
                                    @error('connection_string')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Opciones de impresión --}}
                                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="show_logo"
                                            class="w-5 h-5 text-purple-600 rounded">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mostrar
                                            logo</span>
                                    </label>

                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="show_barcode"
                                            class="w-5 h-5 text-purple-600 rounded">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Código de
                                            barras</span>
                                    </label>

                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="show_vehicle_info"
                                            class="w-5 h-5 text-purple-600 rounded">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Info
                                            vehículo</span>
                                    </label>

                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="show_customer_info"
                                            class="w-5 h-5 text-purple-600 rounded">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Info
                                            cliente</span>
                                    </label>

                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="show_rate_info"
                                            class="w-5 h-5 text-purple-600 rounded">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Info
                                            tarifa</span>
                                    </label>

                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="is_default"
                                            class="w-5 h-5 text-green-600 rounded">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Predeterminada</span>
                                    </label>
                                </div>

                                {{-- Copias --}}
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Número
                                        de copias</label>
                                    <input type="number" wire:model="copies" min="1" max="5"
                                        class="w-full h-12 px-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white">
                                </div>

                                {{-- Footer text --}}
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Texto
                                        de pie de página</label>
                                    <textarea wire:model="footer_text" rows="2"
                                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900/30 transition-all text-gray-900 dark:text-white text-sm"></textarea>
                                </div>

                            </div>

                            {{-- Footer --}}
                            <div class="flex gap-3 px-6 pb-6">
                                <x-button-secondary wire:click="closeForm" class="flex-1">
                                    Cancelar
                                </x-button-secondary>


                                <button wire:click="save"
                                    class="px-6 py-3 bg-gradient-to-r flex-1 from-slate-900 via-theme-primary to-theme-secondary text-white font-bold rounded-xl hover:shadow-xl transition-all">
                                    {{ $editingId ? 'Actualizar' : 'Guardar' }}
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
            @endif
            @include('livewire.customers.partials.theme-styles')
        </div>
    </x-themed-layout>

</div>
