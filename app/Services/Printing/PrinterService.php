<?php

namespace App\Services\Printing;

use App\Models\Rental;
use App\Models\PrinterConfiguration;
use App\Services\Printing\Drivers\EscposPrinter;
use App\Services\Printing\Drivers\BrowserPrinter;
use App\Services\Printing\Drivers\PdfPrinter;
use App\Services\Printing\Templates\EntryReceipt;
use App\Services\Printing\Templates\ExitReceipt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * ========================================
 * PRINTER SERVICE
 * ========================================
 * 
 * Servicio principal para gestionar impresiones de recibos
 * Implementa Strategy Pattern para diferentes drivers
 * 
 */
class PrinterService
{
    protected ?PrinterConfiguration $printer = null;
    protected array $drivers = [];

    public function __construct()
    {
        $this->registerDrivers();
        $this->loadDefaultPrinter();
    }

    // ========================================
    // DRIVER MANAGEMENT
    // ========================================

    /**
     * Registrar drivers disponibles
     */
    private function registerDrivers(): void
    {
        $this->drivers = [
            'escpos' => EscposPrinter::class,
            'browser' => BrowserPrinter::class,
            'pdf' => PdfPrinter::class,
        ];
    }

    /**
     * Cargar impresora predeterminada
     */
    private function loadDefaultPrinter(): void
    {
        $this->printer = Cache::remember('default_printer', 3600, function () {
            return PrinterConfiguration::getDefault();
        });
    }

    /**
     * Establecer impresora específica
     */
    public function usePrinter(PrinterConfiguration $printer): self
    {
        $this->printer = $printer;
        return $this;
    }

    /**
     * Obtener instancia del driver actual
     */
    private function getDriver()
    {
        if (!$this->printer) {
            throw new \Exception('No printer configuration found. Please configure a printer first.');
        }

        $driverClass = $this->drivers[$this->printer->driver] ?? null;

        if (!$driverClass) {
            throw new \Exception("Driver '{$this->printer->driver}' not found.");
        }

        return new $driverClass($this->printer);
    }

    // ========================================
    // PRINT METHODS
    // ========================================

    /**
     * Imprimir recibo de entrada
     */
    public function printEntryReceipt(Rental $rental, bool $isCopy = false): bool
    {
        try {
            $template = new EntryReceipt($rental, $this->printer, $isCopy);
            $data = $template->getData();

            $driver = $this->getDriver();
            $result = $driver->print($data);

            if ($result) {
                Log::info('Entry receipt printed successfully', [
                    'rental_id' => $rental->id,
                    'printer' => $this->printer->name,
                    'is_copy' => $isCopy
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error printing entry receipt', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Imprimir recibo de salida/pago
     */
    public function printExitReceipt(Rental $rental, bool $isCopy = false): bool
    {
        try {
            $template = new ExitReceipt($rental, $this->printer, $isCopy);
            $data = $template->getData();

            $driver = $this->getDriver();
            $result = $driver->print($data);

            if ($result) {
                Log::info('Exit receipt printed successfully', [
                    'rental_id' => $rental->id,
                    'printer' => $this->printer->name,
                    'is_copy' => $isCopy
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error printing exit receipt', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Reimprimir recibo (detecta tipo automáticamente)
     */
    public function reprint(Rental $rental): bool
    {
        // Detectar tipo según estado
        if ($rental->check_out) {
            return $this->printExitReceipt($rental, true); // true = is_copy
        }

        return $this->printEntryReceipt($rental, true);
    }

    
    // ========================================
    // PREVIEW METHODS
    // ========================================

    /**
     * Obtener vista previa de recibo de entrada
     */
    public function previewEntryReceipt(Rental $rental, bool $isCopy = false): string
    {
        $template = new EntryReceipt($rental, $this->printer, $isCopy);
        $data = $template->getData();

        $driver = $this->getDriver();
        return $driver->preview($data);
    }

    /**
     * Obtener vista previa de recibo de salida
     */
    public function previewExitReceipt(Rental $rental, bool $isCopy = false): string
    {
        $template = new ExitReceipt($rental, $this->printer, $isCopy);
        $data = $template->getData();

        $driver = $this->getDriver();
        return $driver->preview($data);
    }

    // ========================================
    // UTILITY METHODS
    // ========================================

    /**
     * Verificar disponibilidad de impresora
     */
    public function checkPrinterStatus(): bool
    {
        try {
            $driver = $this->getDriver();
            return $driver->isAvailable();
        } catch (\Exception $e) {
            Log::warning('Printer not available', [
                'printer' => $this->printer?->name,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Limpiar caché de configuración
     */
    public function clearCache(): void
    {
        Cache::forget('default_printer');
        $this->loadDefaultPrinter();
    }

    /**
     * Obtener configuración actual
     */
    public function getConfiguration(): ?PrinterConfiguration
    {
        return $this->printer;
    }

    /**
     * Obtener todas las impresoras disponibles
     */
    public function getAvailablePrinters(): array
    {
        return PrinterConfiguration::active()->get()->toArray();
    }
}
