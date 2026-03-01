<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('data/Customer.csv'); // ajusta ruta
        if (!file_exists($path)) {
            $this->command->error("Archivo no encontrado: $path");
            return;
        }

        // Leer contenido y detectar codificación
        $content = file_get_contents($path);

        // Eliminar BOM si existe
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        // Detectar codificación probable y convertir a UTF-8 si es necesario
        $encoding = mb_detect_encoding($content, ['UTF-8', 'Windows-1252', 'ISO-8859-1'], true) ?: 'Windows-1252';
        if ($encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        // Sobreescribir temporalmente o usar stream desde memoria
        $tmp = tmpfile();
        fwrite($tmp, $content);
        fseek($tmp, 0);

        // Leer CSV (usa ';' si tu CSV usa punto y coma)
        $delimiter = ';'; // cambia a ',' si tu CSV usa comas
        $header = null;
        $rowCount = 0;

        while (($data = fgetcsv($tmp, 0, $delimiter)) !== false) {
            // Saltar filas vacías
            if (count($data) === 0) continue;

            if (!$header) {
                // limpiar espacios por si acaso
                $header = array_map(function($h){ return trim($h); }, $data);
                continue;
            }

            // Mapear fila a clave => valor
            $row = [];
            foreach ($header as $i => $col) {
                $row[$col] = isset($data[$i]) ? trim($data[$i]) : null;
            }

            // Inserta en la tabla customers (ajusta campos según tu tabla)
            DB::table('customers')->insert([
                'name' => $row['name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'mobile' => $row['mobile'] ?? null,
                'address' => $row['address'] ?? null,
                'city' => $row['city'] ?? null,
                'state' => $row['state'] ?? null,
                'notes' => $row['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $rowCount++;
        }

        fclose($tmp);
        $this->command->info("Insertadas $rowCount filas desde CSV.");
    }
}
