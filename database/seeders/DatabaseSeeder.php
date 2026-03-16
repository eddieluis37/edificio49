<?php

namespace Database\Seeders;

use App\Models\Rate;
use App\Models\User;
use App\Models\Space;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\CashClosure;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RentalSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\VehicleSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\CashClosureSeeder;
use Database\Seeders\VehicleRateSeeder;
use Database\Seeders\ParkingSpaceSeeder;

/**
 * ========================================
 * DATABASE SEEDER PRINCIPAL - PARKI
 * ========================================
 * 
 * Este seeder se ejecuta automáticamente durante
 * el Setup Wizard y llena la base de datos con
 * información de prueba para testear rápidamente
 * todas las funcionalidades del sistema
 * 
 * Orden de ejecución:
 * 1. Usuarios y roles
 * 2. Tarifas de vehículos
 * 3. Espacios de estacionamiento
 * 4. Clientes
 * 5. Vehículos
 * 6. Rentas (historial)
 * 7. Cierres de caja
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Deshabilitar verificación de foreign keys temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info(' Starting Parki database seeding...');

        // 1. Configuración de la empresa
        $this->call([
            CompanySeeder::class,
        ]);
        $this->command->info('✅ Company information configured');

        // 2. Usuarios del sistema
        $this->call([
            UserSeeder::class,
        ]);
        $this->command->info('✅ Users created');

        // 2. Tarifas de vehículos
        $this->call([
            VehicleRateSeeder::class,
        ]);
        $this->command->info('✅ Vehicle rates configured');

        // 3. Espacios de estacionamiento
        $this->call([
            ParkingSpaceSeeder::class,
        ]);
        $this->command->info('✅ Parking spaces created');

        // 4. Clientes
        $this->call([
            CustomerSeeder::class,
        ]);
        $this->command->info('✅ Customers registered');

        // 5. Vehículos
        $this->call([
            VehicleSeeder::class,
        ]);
        $this->command->info('✅ Vehicles added');

        // 6. Rentas (historial de estacionamientos)
        $this->call([
            RentalSeeder::class,
        ]);
        $this->command->info('✅ Rental history generated');

        // 7. Cierres de caja
        $this->call([
            CashClosureSeeder::class,
        ]);
        $this->command->info('✅ Cash closures created');

        // 8. Configuración de impresoras
        $this->call([
            PrinterConfigurationSeeder::class,
        ]);
        $this->command->info('✅ Printer configurations setup');

        // Rehabilitar verificación de foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('');
        $this->command->info('🎉 Parky database seeding completed successfully!');
        $this->command->info('');
        $this->command->info(' Summary:');
        $this->command->info('   • Company: ' . DB::table('companies')->count());
        $this->command->info('   • Users: ' . User::count());
        $this->command->info('   • Vehicle Rates: ' . Rate::count());
        $this->command->info('   • Parking Spaces: ' . Space::count());
        $this->command->info('   • Customers: ' . Customer::count());
        $this->command->info('   • Vehicles: ' . Vehicle::count());
        $this->command->info('   • Rentals: ' . Rental::count());
        $this->command->info('   • Cash Closures: ' . CashClosure::count());
        $this->command->info('   • Printer Configurations: ' . DB::table('printer_configurations')->count());
        $this->command->info('');

        // seeder para la administracion de edificio

        $this->call([
            AccountingSeeder::class,
        ]);

        $this->call(\Database\Seeders\ExampleInvoicesSeeder::class);

        $this->call(\Database\Seeders\PropietariosSeeder::class);
    }
}
