<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PrinterConfiguration;
use App\Services\Printing\PrinterService;

/**
 * ========================================
 * PRINTER SETTINGS COMPONENT
 * ========================================
 * 
 * Componente para gestionar configuracines de impresoras
 */
class PrinterSettings extends Component
{
    // Propiedades del formulario
    public $name = '';
    public $driver = 'escpos';
    public $connection_type = 'usb';
    public $connection_string = '';
    public $paper_width = '80';
    public $is_default = false;

    // Settings
    public $show_logo = false;
    public $show_barcode = true;
    public $show_rate_info = true;
    public $show_vehicle_info = true;
    public $show_customer_info = false;
    public $footer_text = 'Por favor conserve su ticket. En caso de extravío se pagará una multa de $50.00';
    public $copies = 1;

    // Estado
    public $editingId = null;
    public $showForm = false;
    public $testingPrinter = false;

    // ========================================
    // RULES
    // ========================================

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'driver' => 'required|in:escpos,browser,pdf',
            'connection_type' => 'required|in:usb,network,browser',
            'connection_string' => 'required_if:connection_type,usb,network|nullable|string',
            'paper_width' => 'required|in:58,80',
            'is_default' => 'boolean',
            'copies' => 'integer|min:1|max:5',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio',
        'connection_string.required_if' => 'Debes especificar la ruta o IP de la impresora',
    ];

    // ========================================
    // METHODS
    // ========================================

    public function mount()
    {
        // Cargar configuración predeterminada al abrir
        $this->loadDefaultPlaceholders();
    }

    public function loadDefaultPlaceholders()
    {
        if ($this->connection_type === 'usb') {
            $this->connection_string = 'TM20';
        } elseif ($this->connection_type === 'network') {
            $this->connection_string = '192.168.1.100:9100';
        }
    }

    public function updatedConnectionType()
    {
        $this->loadDefaultPlaceholders();
    }

    public function openForm()
    {
        $this->reset(['name', 'editingId']);
        $this->resetValidation();
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->reset();
    }

    public function edit($id)
    {
        $printer = PrinterConfiguration::findOrFail($id);

        $this->editingId = $printer->id;
        $this->name = $printer->name;
        $this->driver = $printer->driver;
        $this->connection_type = $printer->connection_type;
        $this->connection_string = $printer->connection_string;
        $this->paper_width = $printer->paper_width;
        $this->is_default = $printer->is_default;

        // Settings
        $this->show_logo = $printer->getSetting('show_logo', false);
        $this->show_barcode = $printer->getSetting('show_barcode', true);
        $this->show_rate_info = $printer->getSetting('show_rate_info', true);
        $this->show_vehicle_info = $printer->getSetting('show_vehicle_info', true);
        $this->show_customer_info = $printer->getSetting('show_customer_info', false);
        $this->footer_text = $printer->getSetting('footer_text', $this->footer_text);
        $this->copies = $printer->getSetting('copies', 1);

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $settings = [
            'show_logo' => $this->show_logo,
            'show_barcode' => $this->show_barcode,
            'show_rate_info' => $this->show_rate_info,
            'show_vehicle_info' => $this->show_vehicle_info,
            'show_customer_info' => $this->show_customer_info,
            'footer_text' => $this->footer_text,
            'copies' => $this->copies,
        ];

        $data = [
            'name' => $this->name,
            'driver' => $this->driver,
            'connection_type' => $this->connection_type,
            'connection_string' => $this->connection_string,
            'paper_width' => $this->paper_width,
            'is_default' => $this->is_default,
            'settings' => $settings,
            'is_active' => true,
        ];

        if ($this->editingId) {
            $printer = PrinterConfiguration::findOrFail($this->editingId);
            $printer->update($data);

            if ($this->is_default) {
                $printer->setAsDefault();
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '✅ Impresora actualizada correctamente'
            ]);
        } else {
            $printer = PrinterConfiguration::create($data);

            if ($this->is_default) {
                $printer->setAsDefault();
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '✅ Impresora agregada correctamente'
            ]);
        }

        // Limpiar caché
        app(PrinterService::class)->clearCache();

        $this->closeForm();
    }

    public function delete($id)
    {
        $printer = PrinterConfiguration::findOrFail($id);

        if ($printer->is_default) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ No puedes eliminar la impresora predeterminada'
            ]);
            return;
        }

        $printer->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => '✅ Impresora eliminada'
        ]);
    }

    public function setAsDefault($id)
    {
        $printer = PrinterConfiguration::findOrFail($id);
        $printer->setAsDefault();

        app(PrinterService::class)->clearCache();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => '✅ Impresora establecida como predeterminada'
        ]);
    }

    public function testPrinter($id)
    {
        $this->testingPrinter = true;

        try {
            $printer = PrinterConfiguration::findOrFail($id);
            $printerService = app(PrinterService::class)->usePrinter($printer);

            $isAvailable = $printerService->checkPrinterStatus();

            if ($isAvailable) {
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => '✅ Impresora disponible y lista'
                ]);
            } else {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => '❌ No se puede conectar a la impresora'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ Error: ' . $e->getMessage()
            ]);
        } finally {
            $this->testingPrinter = false;
        }
    }

    public function render()
    {
        $printers = PrinterConfiguration::orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        return view('livewire.printer-settings', [
            'printers' => $printers
        ]);
    }
}
