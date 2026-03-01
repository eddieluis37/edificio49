<?php

namespace App\Services\Printing\Contracts;

/**
 * ========================================
 * PRINTABLE INTERFACE
 * ========================================
 * 
 * Contrato que deben implementar todos los drivers de impresión
 * 
 */
interface PrintableInterface
{
    /**
     * Imprimir el recibo
     * 
     * @param array $data Datos del recibo
     * @return bool
     */
    public function print(array $data): bool;

    /**
     * Obtener vista previa del recibo
     * 
     * @param array $data Datos del recibo
     * @return string HTML del recibo
     */
    public function preview(array $data): string;

    /**
     * Validar que la impresora esté disponible
     * 
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * Obtener nombre del driver
     * 
     * @return string
     */
    public function getName(): string;
}
