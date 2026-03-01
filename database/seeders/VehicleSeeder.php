<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Type;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $autoType = Type::where('name', 'Automóvil')->first();
        $motoType = Type::where('name', 'Motocicleta')->first();
        $camionetaType = Type::where('name', 'Camioneta')->first();

        if ($customers->isEmpty() || !$autoType) {
            $this->command->warn(' No customers or types found. Skipping vehicle seeding.');
            return;
        }

        $vehicles = [
            [
                'plate' => 'ABC123',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'color' => 'Blanco',
                'type_id' => $autoType->id,
                'customer_id' => $customers[0]->id,
                'notes' => null,
                'is_active' => true,
            ],
            [
                'plate' => 'XYZ789',
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2021,
                'color' => 'Negro',
                'type_id' => $autoType->id,
                'customer_id' => $customers[1]->id ?? $customers[0]->id,
                'notes' => null,
                'is_active' => true,
            ],
            [
                'plate' => 'MOT456',
                'brand' => 'Yamaha',
                'model' => 'FZ',
                'year' => 2022,
                'color' => 'Azul',
                'type_id' => $motoType?->id ?? $autoType->id,
                'customer_id' => $customers[2]->id ?? $customers[0]->id,
                'notes' => null,
                'is_active' => true,
            ],
            [
                'plate' => 'CAM789',
                'brand' => 'Ford',
                'model' => 'F-150',
                'year' => 2019,
                'color' => 'Rojo',
                'type_id' => $camionetaType?->id ?? $autoType->id,
                'customer_id' => $customers[3]->id ?? $customers[0]->id,
                'notes' => null,
                'is_active' => true,
            ],
            [
                'plate' => 'DEF321',
                'brand' => 'Nissan',
                'model' => 'Sentra',
                'year' => 2023,
                'color' => 'Gris',
                'type_id' => $autoType->id,
                'customer_id' => $customers[4]->id ?? $customers[0]->id,
                'notes' => 'Vehículo eléctrico',
                'is_active' => true,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
