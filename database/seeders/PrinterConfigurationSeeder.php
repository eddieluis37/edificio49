<?php

namespace Database\Seeders;

use App\Models\PrinterConfiguration;
use Illuminate\Database\Seeder;

class PrinterConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        PrinterConfiguration::create([
            'name' => 'Impresora Térmica Principal',
            'driver' => 'escpos',
            'connection_type' => 'usb',
            'connection_string' => '/dev/usb/lp0',
            'paper_width' => '80',
            'is_default' => true,
            'settings' => [
                'cuts_per_print' => 1,
                'line_spacing' => 30,
                'font_size' => 'normal',
            ],
            'is_active' => true,
        ]);
    }
}
