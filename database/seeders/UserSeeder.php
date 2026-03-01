<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin (se crea en el wizard, pero por si acaso)
        User::firstOrCreate(
            ['email' => 'admin@seikou.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'active' => true,
                'profile_photo' => null,
            ]
        );

        // Usuario Cajero
        User::create([
            'name' => 'Juan Cajero',
            'email' => 'cajero@seikou.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'active' => true,
            'profile_photo' => null,
        ]);

        // Usuario Visor
        User::create([
            'name' => 'Mario Supervisor',
            'email' => 'visor@seikou.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'active' => true,
            'profile_photo' => null,
        ]);
    }
}
