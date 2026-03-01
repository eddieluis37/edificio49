<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Models\Type;
use Illuminate\Database\Seeder;

class ParkingSpaceSeeder extends Seeder
{
    public function run(): void
    {
        $autoType = Type::where('name', 'Automóvil')->first();
        $motoType = Type::where('name', 'Motocicleta')->first();
        $camionetaType = Type::where('name', 'Camioneta')->first();

        $autoId = $autoType?->id ?? 1;
        $motoId = $motoType?->id ?? 2;
        $camionetaId = $camionetaType?->id ?? 3;

        // Espacios para automóviles (A1-A20)
        for ($i = 1; $i <= 20; $i++) {
            Space::create([
                'code' => 'A' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'description' => 'Espacio para automóvil',
                'type_id' => $autoId,
                'status' => $i <= 15 ? 'available' : 'occupied',
                'notes' => null,
            ]);
        }

        // Espacios para motocicletas (M1-M10)
        for ($i = 1; $i <= 10; $i++) {
            Space::create([
                'code' => 'M' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'description' => 'Espacio para motocicleta',
                'type_id' => $motoId,
                'status' => $i <= 7 ? 'available' : 'occupied',
                'notes' => null,
            ]);
        }

        // Espacios para camionetas (C1-C10)
        for ($i = 1; $i <= 10; $i++) {
            Space::create([
                'code' => 'C' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'description' => 'Espacio para camioneta',
                'type_id' => $camionetaId,
                'status' => $i <= 6 ? 'available' : 'occupied',
                'notes' => null,
            ]);
        }
    }
}
