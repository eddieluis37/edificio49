<?php

namespace App\Services\Printing\Drivers;

use App\Models\PrinterConfiguration;
use App\Services\Printing\Contracts\PrintableInterface;

/**
 * ========================================
 * BROWSER PRINTER DRIVER
 * ========================================
 * 
 * Driver para impresión desde navegador usando JavaScript
 * Genera HTML optimizado para window.print()
 * 
 */
class BrowserPrinter implements PrintableInterface
{
    protected PrinterConfiguration $config;

    public function __construct(PrinterConfiguration $config)
    {
        $this->config = $config;
    }

    // ========================================
    // IMPLEMENTATION
    // ========================================

    /**
     * Para navegador, print() retorna HTML que el frontend imprimirá
     */
    public function print(array $data): bool
    {
        // En el caso del navegador, la impresión real la hace JavaScript
        // Este método solo valida que los datos estén correctos
        return !empty($data['receipt']['folio']);
    }

    /**
     * Generar HTML para vista previa e impresión
     */
    public function preview(array $data): string
    {
        return view('printing.browser-receipt', [
            'data' => $data,
            'config' => $this->config,
            'paperWidth' => $this->config->paper_width
        ])->render();
    }

    /**
     * Browser siempre está disponible
     */
    public function isAvailable(): bool
    {
        return true;
    }

    /**
     * Nombre del driver
     */
    public function getName(): string
    {
        return 'Browser Print';
    }
}
