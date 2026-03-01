<?php

namespace App\Traits;

use App\Services\Printing\PrinterService;

/**
 * ========================================
 * HAS PRINTABLE RECEIPTS TRAIT
 * ========================================
 * 
 * Trait para agregar funcionalidad de impresión a modelos
 * Se crea principalmente para usarlo en el modelo Rental, pero podrías reusarlo donde desees
 */
trait HasPrintableReceipts
{
    /**
     * Imprimir recibo de entrada
     */
    public function printEntryReceipt(bool $isCopy = false): bool
    {
        $printerService = app(PrinterService::class);
        return $printerService->printEntryReceipt($this, $isCopy);
    }

    /**
     * Imprimir recibo de salida
     */
    public function printExitReceipt(bool $isCopy = false): bool
    {
        $printerService = app(PrinterService::class);
        return $printerService->printExitReceipt($this, $isCopy);
    }

    /**
     * Obtener vista previa de recibo de entrada
     */
    public function previewEntryReceipt(bool $isCopy = false): string
    {
        $printerService = app(PrinterService::class);
        return $printerService->previewEntryReceipt($this, $isCopy);
    }

    /**
     * Obtener vista previa de recibo de salida
     */
    public function previewExitReceipt(bool $isCopy = false): string
    {
        $printerService = app(PrinterService::class);
        return $printerService->previewExitReceipt($this, $isCopy);
    }

    /**
     * Reimprimir último recibo (detecta automáticamente el tipo)
     */
    public function reprintReceipt(): bool
    {
        $isCopy = true;

        // Si tiene checkout, imprimir recibo de salida
        if ($this->checkout_at) {
            return $this->printExitReceipt($isCopy);
        }

        // Si no, imprimir recibo de entrada
        return $this->printEntryReceipt($isCopy);
    }
}
