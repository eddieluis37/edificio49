<?php

namespace Database\Seeders;

use App\Models\Type;
use App\Models\Rate;
use Illuminate\Database\Seeder;

class VehicleRateSeeder extends Seeder
{
    public function run(): void
    {
        // AUTOMÓVIL
        $auto = Type::create(['name' => 'Automóvil', 'image' => null, 'order' => 1]);

        Rate::create([
            'type_id' => $auto->id,
            'description' => 'Tarifa por hora',
            'price' => 25.00,
            'time' => 60,
            'rate_type' => 'hourly',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $auto->id,
            'description' => 'Tarifa por día',
            'price' => 150.00,
            'time' => 1440,
            'rate_type' => 'daily',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $auto->id,
            'description' => 'Tarifa mensual',
            'price' => 3000.00,
            'time' => 43200,
            'rate_type' => 'monthly',
            'active' => true,
        ]);

        // MOTOCICLETA
        $moto = Type::create(['name' => 'Motocicleta', 'image' => null, 'order' => 2]);

        Rate::create([
            'type_id' => $moto->id,
            'description' => 'Tarifa por hora',
            'price' => 15.00,
            'time' => 60,
            'rate_type' => 'hourly',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $moto->id,
            'description' => 'Tarifa por día',
            'price' => 80.00,
            'time' => 1440,
            'rate_type' => 'daily',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $moto->id,
            'description' => 'Tarifa mensual',
            'price' => 1500.00,
            'time' => 43200,
            'rate_type' => 'monthly',
            'active' => true,
        ]);

        // CAMIONETA
        $camioneta = Type::create(['name' => 'Camioneta', 'image' => null, 'order' => 3]);

        Rate::create([
            'type_id' => $camioneta->id,
            'description' => 'Tarifa por hora',
            'price' => 35.00,
            'time' => 60,
            'rate_type' => 'hourly',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $camioneta->id,
            'description' => 'Tarifa por día',
            'price' => 200.00,
            'time' => 1440,
            'rate_type' => 'daily',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $camioneta->id,
            'description' => 'Tarifa mensual',
            'price' => 4000.00,
            'time' => 43200,
            'rate_type' => 'monthly',
            'active' => true,
        ]);

        // BICICLETA
        $bici = Type::create(['name' => 'Bicicleta', 'image' => null, 'order' => 4]);

        Rate::create([
            'type_id' => $bici->id,
            'description' => 'Tarifa por hora',
            'price' => 10.00,
            'time' => 60,
            'rate_type' => 'hourly',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $bici->id,
            'description' => 'Tarifa por día',
            'price' => 50.00,
            'time' => 1440,
            'rate_type' => 'daily',
            'active' => true,
        ]);

        Rate::create([
            'type_id' => $bici->id,
            'description' => 'Tarifa mensual',
            'price' => 800.00,
            'time' => 43200,
            'rate_type' => 'monthly',
            'active' => true,
        ]);
    }
}
