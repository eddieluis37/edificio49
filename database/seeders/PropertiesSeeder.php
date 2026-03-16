<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Garage;
use App\Models\Owner;
use App\Models\AdminFeeSetting;
use Carbon\Carbon;

class PropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar datos existentes (Precaución en producción, útil para depurar)
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Garage::truncate();
        Owner::truncate();
        Apartment::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Asegurarnos que existe la configuración base del año 2026/2025 para el ejemplo
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        AdminFeeSetting::updateOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'base_budget' => 1120000,
                'honorarios_default' => 58000, // Honorarios mensuales globales 58.000 COP
                'early_discount_enabled' => true,
                'due_date' => Carbon::create($year, $month, 1)->endOfMonth()->toDateString()
            ]
        );

        // 3. Crear Estructura Solicitada
        $unidades = [
            [
                'code' => 'CONSULTORIO No. S1-05',
                'share_fraction' => 9.60,
                'garages' => [['code' => 'GARAJE S1-01', 'share_fraction' => 1.99]]
            ],
            [
                'code' => 'CONSULTORIO No. S1-06',
                'share_fraction' => 9.60,
                'garages' => []
            ],
            [
                'code' => 'APARTAMENTO No. 1-01',
                'share_fraction' => 6.85,
                'garages' => []
            ],
            [
                'code' => 'APARTAMENTO No. 1-02',
                'share_fraction' => 9.09,
                'garages' => []
            ],
            [
                'code' => 'APARTAMENTO No. 2-01',
                'share_fraction' => 9.48,
                'garages' => []
            ],
            [
                'code' => 'APARTAMENTO No. 2-02',
                'share_fraction' => 9.48,
                'garages' => []
            ],
            [
                'code' => 'APARTAMENTO No. 3-01',
                'share_fraction' => 9.48,
                'garages' => [['code' => 'GARAJE S1-02', 'share_fraction' => 2.00]]
            ],
            [
                'code' => 'APARTAMENTO No. 3-02',
                'share_fraction' => 9.48,
                'garages' => [['code' => 'GARAJE S1-03', 'share_fraction' => 2.00]]
            ],
            [
                'code' => 'APARTAMENTO No. 4-01',
                'share_fraction' => 9.48,
                'garages' => []
            ],
            [
                'code' => 'APARTAMENTO No. 4-02',
                'share_fraction' => 9.48,
                'garages' => [['code' => 'GARAJE S1-04', 'share_fraction' => 1.99]]
            ],
        ];

        $ownerCounter = 1;

        foreach ($unidades as $data) {
            // Crear el Apartamento
            $apartment = Apartment::create([
                'code' => $data['code'],
                'share_fraction' => $data['share_fraction'],
                'status' => 'occupied'
            ]);

            // Crear el Propietario (Dummy)
            $owner = Owner::create([
                'apartment_id' => $apartment->id, // Este campo parece existir de versiones tempranas en la app
                'name' => 'Propietario ' . $ownerCounter,
                'document_type' => 'CC',
                'document_number' => '1000' . rand(100,999) . $ownerCounter,
                'active' => true,
            ]);

            // Crear los Garajes vinculados al Apartamento y al Propietario
            foreach ($data['garages'] as $garageData) {
                Garage::create([
                    'code' => $garageData['code'],
                    'share_fraction' => $garageData['share_fraction'],
                    'apartment_id' => $apartment->id,
                    'owner_id' => $owner->id,
                    'status' => 'occupied'
                ]);
            }

            $ownerCounter++;
        }
    }
}
