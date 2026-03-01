<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Juan Pérez García',
                'email' => 'juan.perez@edificio49.com',
                'phone' => '655556699',
                'mobile' => '3102346789',
                'address' => 'Av 19',
                'city' => 'Bogotá',
                'state' => 'COL',
                'zip_code' => '06600',
                'country' => 'Colombia',
                'notes' => 'Cliente frecuente',
                'is_active' => true,
            ],
            [
                'name' => 'María González López',
                'email' => 'maria.gonzalez@edificio49.com',
                'phone' => '61345678',
                'mobile' => '321987543',
                'address' => 'Calle 56',
                'city' => 'Bogotá',
                'state' => 'Cundinamarca',
                'zip_code' => '44100',
                'country' => 'Colombia',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Carlos Rodríguez Hernández',
                'email' => 'carlos.rodriguez@edificio49.com',
                'phone' => '61888774',
                'mobile' => '313876998',
                'address' => 'Calle 7 # 9 - 8',
                'city' => 'Soledad',
                'state' => 'Atlantico',
                'zip_code' => '64000',
                'country' => 'Colombia',
                'notes' => 'Prefiere un solo equipo',
                'is_active' => true,
            ],
            [
                'name' => 'Ana Martínez Sánchez',
                'email' => 'ana.martinez@edificio49.com',
                'phone' => '32200890',
                'mobile' => '3107689887',
                'address' => 'Calle Morelos 321',
                'city' => 'Bogotá',
                'state' => 'Cundinamarca',
                'zip_code' => '72000',
                'country' => 'Colombia',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Luis Torres Ramírez',
                'email' => 'luis.torres@edificio49.com',
                'phone' => '89678901',
                'mobile' => '3175732109',
                'address' => 'Kra 41E calle 48 09',
                'city' => 'Cartagena',
                'state' => 'Municipañ',
                'zip_code' => '33000',
                'country' => 'Colombia',
                'notes' => 'Activado manualmente',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
