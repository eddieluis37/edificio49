<?php

namespace App\Services\Printing\Drivers;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\Log;
use App\Models\PrinterConfiguration;
use App\Services\Printing\Contracts\PrintableInterface;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/**
 * ========================================
 * ESCPOS PRINTER DRIVER 
 * ========================================
 * 
 * Driver para impresoras térmicas ESC/POS
 * Confirma en mis redes sociales que te imprime el logo 200ok 👍🏻
 * 
 */
class EscposPrinter implements PrintableInterface
{
    protected PrinterConfiguration $config;
    protected ?Printer $printer = null;

    public function __construct(PrinterConfiguration $config)
    {
        $this->config = $config;
    }

    // ========================================
    // IMPLEMENTATION
    // ========================================

    /**
     * Imprimir recibo
     */
    public function print(array $data): bool
    {
        try {
            $this->connect();

            // Header con logo y empresa
            $this->printHeader($data);

            // Contenido del recibo
            $this->printBody($data);

            // Footer con código de barras
            $this->printFooter($data);

            // Finalizar
            $copies = $this->config->getSetting('copies', 1);
            for ($i = 0; $i < $copies; $i++) {
                $this->printer->feed(3);
                $this->printer->cut();
            }

            $this->printer->close();

            return true;
        } catch (\Exception $e) {
            Log::error('ESC/POS Print Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($this->printer) {
                $this->printer->close();
            }

            throw $e;
        }
    }

    /**
     * Vista previa (retorna HTML)
     */
    public function preview(array $data): string
    {
        return view('printing.receipt-preview', [
            'data' => $data,
            'config' => $this->config
        ])->render();
    }

    /**
     * Verificar disponibilidad
     */
    public function isAvailable(): bool
    {
        try {
            $this->connect();
            $this->printer->close();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Nombre del driver
     */
    public function getName(): string
    {
        return 'ESC/POS Thermal Printer';
    }

    // ========================================
    // PRIVATE METHODS
    // ========================================

    /**
     * Conectar a la impresora
     */
    private function connect(): void
    {
        $connector = $this->getConnector();
        $this->printer = new Printer($connector);
    }

    /**
     * Obtener conector según tipo
     */
    private function getConnector()
    {
        return match ($this->config->connection_type) {
            'usb' => new WindowsPrintConnector($this->config->connection_string),
            'network' => NetworkPrintConnector::create($this->config->connection_string),
            default => throw new \Exception('Invalid connection type')
        };
    }

    /**
     * Imprimir header del recibo
     */
    private function printHeader(array $data): void
    {
        // Logo si está habilitado
        if ($this->config->getSetting('show_logo', false) && !empty($data['company']['logo_path'])) {
            $this->printLogo($data['company']['logo_path']);
        }

        // Nombre de la empresa
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->setTextSize(2, 2);
        $this->printer->text(strtoupper($data['company']['name']) . "\n");

        $this->printer->setTextSize(1, 1);
        $this->printer->text($data['company']['address'] . "\n");

        if (!empty($data['company']['phone'])) {
            $this->printer->text("Tel: " . $data['company']['phone'] . "\n");
        }

        if (!empty($data['company']['rfc'])) {
            $this->printer->text("RFC: " . $data['company']['rfc'] . "\n");
        }

        // Tipo de recibo
        $this->printer->text("\n** " . strtoupper($data['receipt']['type']) . " **\n\n");
    }

    /**
     * Imprimir logo (con corrección de errores)
     */
    private function printLogo(string $logoPath): void
    {
        try {
            // Verificar que el archivo existe
            if (!file_exists($logoPath)) {
                Log::warning('Logo file not found', ['path' => $logoPath]);
                return;
            }

            // Obtener información del archivo
            $imageInfo = getimagesize($logoPath);
            if (!$imageInfo) {
                Log::warning('Invalid image file', ['path' => $logoPath]);
                return;
            }

            // Convertir imagen si es necesario
            $processedPath = $this->processLogoForThermalPrinter($logoPath, $imageInfo);

            // Cargar imagen procesada
            // ✅ FIX: Usar el segundo parámetro en false para imágenes pequeñas
            $logo = EscposImage::load($processedPath, false);

            // Imprimir centrado
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->bitImage($logo); // ✅ FIX: Usar bitImage en lugar de graphics
            $this->printer->feed(1);

            // Limpiar archivo temporal si se creó uno
            if ($processedPath !== $logoPath) {
                @unlink($processedPath);
            }
        } catch (\Exception $e) {
            Log::warning('Could not print logo', [
                'error' => $e->getMessage(),
                'path' => $logoPath
            ]);
            // Continuar sin logo si falla
        }
    }

    /**
     * Procesar logo para impresora térmica
     *  Redimensionar y convertir a formato óptimo
     */
    private function processLogoForThermalPrinter(string $path, array $imageInfo): string
    {
        try {
            // Determinar ancho máximo según papel
            $maxWidth = $this->config->paper_width == '58' ? 200 : 384; // px
            $maxHeight = 150; // px

            [$width, $height, $type] = $imageInfo;

            // Si la imagen ya es del tamaño correcto y es PNG, no procesar
            if ($width <= $maxWidth && $height <= $maxHeight && $type == IMAGETYPE_PNG) {
                return $path;
            }

            // Calcular nuevas dimensiones manteniendo aspect ratio
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);

            // Crear imagen desde el archivo original
            $source = match ($type) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($path),
                IMAGETYPE_PNG => imagecreatefrompng($path),
                IMAGETYPE_GIF => imagecreatefromgif($path),
                default => throw new \Exception('Unsupported image type')
            };

            if (!$source) {
                throw new \Exception('Failed to create image resource');
            }

            // Crear nueva imagen
            $dest = imagecreatetruecolor($newWidth, $newHeight);

            // Fondo blanco (importante para impresoras térmicas)
            $white = imagecolorallocate($dest, 255, 255, 255);
            imagefill($dest, 0, 0, $white);

            // Redimensionar
            imagecopyresampled(
                $dest,
                $source,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );

            // Guardar como PNG temporal
            $tempPath = sys_get_temp_dir() . '/thermal_logo_' . uniqid() . '.png';
            imagepng($dest, $tempPath);

            // Liberar memoria
            imagedestroy($source);
            imagedestroy($dest);

            return $tempPath;
        } catch (\Exception $e) {
            Log::error('Error processing logo', [
                'error' => $e->getMessage()
            ]);
            return $path; // Retornar original si falla
        }
    }

    /**
     * Imprimir cuerpo del recibo
     */
    private function printBody(array $data): void
    {
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);

        // Separador
        $this->printSeparator();

        // Marca de agua si es copia
        if ($data['receipt']['is_copy']) {
            $this->printer->setEmphasis(true);
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("*** COPIA ***\n");
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->setEmphasis(false);
        }

        // Folio
        $this->printer->text("Folio: " . $data['receipt']['folio'] . "\n");

        // Fecha/hora entrada
        if (!empty($data['rental']['checkin_at'])) {
            $this->printer->text("Entrada: " . $data['rental']['checkin_at'] . "\n");
        }

        // Fecha/hora salida (si existe)
        if (!empty($data['rental']['checkout_at'])) {
            $this->printer->text("Salida: " . $data['rental']['checkout_at'] . "\n");
        }

        // Espacio asignado
        if (!empty($data['space']['code'])) {
            $this->printer->setEmphasis(true);
            $this->printer->text("Espacio: " . $data['space']['code'] . "\n");
            $this->printer->setEmphasis(false);
        }

        // Información de tarifa
        if ($this->config->getSetting('show_rate_info', true)) {
            $this->printer->text("\n");
            $this->printer->text("Tarifa: " . $data['rate']['description'] . "\n");
            $this->printer->text("Precio: $" . number_format($data['rate']['price'], 2) . "\n");

            if (!empty($data['rental']['minutes'])) {
                $hours = floor($data['rental']['minutes'] / 60);
                $mins = $data['rental']['minutes'] % 60;
                $this->printer->text("Tiempo: {$hours}h {$mins}m\n");
            }
        }

        // Total (si es salida)
        if (!empty($data['rental']['total'])) {
            $this->printer->text("\n");
            $this->printer->setTextSize(2, 1);
            $this->printer->setEmphasis(true);
            $this->printer->text("TOTAL: $" . number_format($data['rental']['total'], 2) . "\n");
            $this->printer->setEmphasis(false);
            $this->printer->setTextSize(1, 1);
        }

        // Información del vehículo
        if ($this->config->getSetting('show_vehicle_info', true) && !empty($data['vehicle'])) {
            $this->printer->text("\n");
            $vehicleInfo = [];
            if (!empty($data['vehicle']['plate'])) $vehicleInfo[] = "Placa: " . $data['vehicle']['plate'];
            if (!empty($data['vehicle']['brand'])) $vehicleInfo[] = "Marca: " . $data['vehicle']['brand'];
            if (!empty($data['vehicle']['color'])) $vehicleInfo[] = "Color: " . $data['vehicle']['color'];

            if (!empty($vehicleInfo)) {
                $this->printer->text(implode(" | ", $vehicleInfo) . "\n");
            }
        }

        // Información del cliente
        if ($this->config->getSetting('show_customer_info', false) && !empty($data['customer'])) {
            $this->printer->text("\n");
            if (!empty($data['customer']['name'])) {
                $this->printer->text("Cliente: " . $data['customer']['name'] . "\n");
            }
            if (!empty($data['customer']['phone'])) {
                $this->printer->text("Tel: " . $data['customer']['phone'] . "\n");
            }
        }

        // Separador final
        $this->printSeparator();
    }

    /**
     * Imprimir footer con código de barras
     */
    private function printFooter(array $data): void
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);

        // Mensaje personalizado (desde Company o config)
        $footerText = !empty($data['company']['footer'])
            ? $data['company']['footer']
            : $this->config->getSetting('footer_text', 'Por favor conserve su ticket');

        $this->printer->text("\n" . $footerText . "\n\n");

        // Código de barras
        if ($this->config->getSetting('show_barcode', true)) {
            try {
                $this->printer->selectPrintMode();
                $this->printer->setBarcodeHeight(60); //  Reducido de 80
                $this->printer->setBarcodeWidth(2);   //  Reducido de 3
                $this->printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);

                // Formato folio para código de barras
                $barcode = str_pad($data['receipt']['folio'], 8, "0", STR_PAD_LEFT);
                $this->printer->barcode($barcode, Printer::BARCODE_CODE39);
                $this->printer->feed(1);
            } catch (\Exception $e) {
                Log::warning('Could not print barcode', ['error' => $e->getMessage()]);
            }
        }

        // Mensaje de despedida
        $this->printer->text("\n¡Gracias por su preferencia!\n");

        if (!empty($data['company']['website'])) {
            $this->printer->text($data['company']['website'] . "\n");
        }

        // Fecha de impresión
        $this->printer->text("\nImpreso: " . date('d/m/Y H:i') . "\n");
    }

    /**
     * Imprimir separador
     */
    private function printSeparator(): void
    {
        $width = $this->config->paper_width == '58' ? 32 : 48;
        $this->printer->text(str_repeat('=', $width) . "\n");
    }
}
