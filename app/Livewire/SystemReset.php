<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Type;
use App\Models\Rate;
use App\Models\Space;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Rental;
use App\Models\Company;
use App\Models\CashClosure;
use App\Models\PrinterConfiguration;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

/**
 * ========================================
 * SYSTEM RESET COMPONENT - PARKI
 * ========================================
 * 
 * Componente enterprise para restablecer el sistema completo.
 * Replica exactamente la lógica del Setup Wizard.
 * 
 * Features:
 * - Limpieza segura de base de datos
 * - Preservación del usuario actual
 * - Inserción de datos idénticos al wizard
 * - Progreso en tiempo real
 * - Manejo robusto de errores
 * - Logging detallado
 */
class SystemReset extends Component
{
    // ========================================
    // PROPIEDADES PÚBLICAS
    // ========================================

    public bool $showConfirmModal = false;
    public bool $isResetting = false;
    public int $progress = 0;
    public string $currentStep = '';
    public array $steps = [];
    public string $confirmText = '';
    public bool $canReset = false;

    // ========================================
    // MOUNT
    // ========================================

    public function mount()
    {
        $this->steps = [
            'Preparando sistema...',
            'Limpiando base de datos...',
            'Configurando empresa...',
            'Configurando impresora...',
            'Creando tipos de vehículos y tarifas...',
            'Creando espacios de estacionamiento...',
            'Insertando clientes de prueba...',
            'Registrando vehículos...',
            'Generando historial de rentas...',
            'Creando cortes de caja...',
            'Finalizando reset...',
        ];
    }

    // ========================================
    // MÉTODOS DE MODAL
    // ========================================

    public function openConfirmModal()
    {
        $this->showConfirmModal = true;
        $this->confirmText = '';
        $this->canReset = false;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->confirmText = '';
        $this->canReset = false;
    }

    public function updatedConfirmText($value)
    {
        $this->canReset = strtoupper($value) === 'RESET';
    }

    // ========================================
    // INICIAR RESET
    // ========================================

    public function startReset()
    {
        if (!$this->canReset) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Debes escribir RESET para confirmar'
            ]);
            return;
        }

        $this->showConfirmModal = false;
        $this->isResetting = true;
        $this->progress = 0;

        try {
            Log::info('=== SYSTEM RESET INICIADO ===', ['user_id' => auth()->id()]);

            // Paso 1: Preparando
            $this->updateProgress(0, $this->steps[0]);

            // Paso 2: Limpiar base de datos
            $this->updateProgress(8, $this->steps[1]);
            $this->cleanDatabase();

            // Paso 3: Configurar empresa
            $this->updateProgress(18, $this->steps[2]);
            $this->seedCompany();

            // Paso 4: Configurar impresora
            $this->updateProgress(28, $this->steps[3]);
            $this->seedPrinterConfiguration();

            // Paso 5: Crear tipos y tarifas
            $this->updateProgress(38, $this->steps[4]);
            $this->seedTypesAndRates();

            // Paso 6: Crear espacios
            $this->updateProgress(48, $this->steps[5]);
            $this->seedSpaces();

            // Paso 7: Insertar clientes
            $this->updateProgress(58, $this->steps[6]);
            $this->seedCustomers();

            // Paso 8: Registrar vehículos
            $this->updateProgress(68, $this->steps[7]);
            $this->seedVehicles();

            // Paso 9: Generar rentas
            $this->updateProgress(78, $this->steps[8]);
            $this->seedRentals();

            // Paso 10: Crear cortes de caja
            $this->updateProgress(88, $this->steps[9]);
            $this->seedCashClosures();

            // Paso 11: Finalizar
            $this->updateProgress(95, $this->steps[10]);
            $this->clearCaches();

            // Completado
            $this->updateProgress(100, '✅ Sistema restablecido exitosamente');

            Log::info('=== SYSTEM RESET COMPLETADO ===', [
                'types' => Type::count(),
                'rates' => Rate::count(),
                'spaces' => Space::count(),
                'customers' => Customer::count(),
                'vehicles' => Vehicle::count(),
                'rentals' => Rental::count(),
                'closures' => CashClosure::count(),
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '✅ ¡Sistema restablecido con éxito!'
            ]);

            $this->dispatch('resetComplete');
        } catch (\Exception $e) {
            $this->isResetting = false;
            $this->progress = 0;

            Log::error('ERROR EN SYSTEM RESET', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ Error: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // ACTUALIZAR PROGRESO
    // ========================================

    private function updateProgress(int $progress, string $step)
    {
        $this->progress = $progress;
        $this->currentStep = $step;
        $this->dispatch('progressUpdate', [
            'progress' => $progress,
            'step' => $step
        ]);
    }

    // ========================================
    // LIMPIAR BASE DE DATOS
    // ========================================

    private function cleanDatabase()
    {
        $currentUserId = auth()->id();

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncar tablas en orden correcto (hijos primero)
            $tables = [
                'cash_closures',
                'rentals',
                'vehicles',
                'customers',
                'parking_spaces',
                'rates',
                'types',
                'companies',
                'printer_configurations'
            ];

            foreach ($tables as $table) {
                DB::table($table)->truncate();
                DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
            }

            // Eliminar usuarios excepto el actual
            DB::table('users')->where('id', '!=', $currentUserId)->delete();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Verificar usuario actual existe
            if (!DB::table('users')->where('id', $currentUserId)->exists()) {
                throw new \Exception('Usuario actual no encontrado después de limpieza');
            }

            Log::info('Base de datos limpiada', ['user_preserved' => $currentUserId]);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw $e;
        }
    }

    // ========================================
    // SEED: EMPRESA
    // ========================================

    private function seedCompany()
    {
        $now = now();

        DB::table('companies')->insert([
            'name' => 'Parki',
            'business_name' => 'Parki Estacionamientos S.A. de C.V.',
            'rfc' => 'PES240101ABC',
            'email' => 'info@parki.com',
            'phone' => '5551234567',
            'mobile' => '5559876543',
            'website' => 'https://parki.com',
            'address' => 'Av. Principal 123',
            'city' => 'Ciudad de México',
            'state' => 'CDMX',
            'country' => 'México',
            'postal_code' => '06600',
            'logo_path' => null,
            'logo_url' => null,
            'receipt_footer' => '¡Gracias por su preferencia!',
            'receipt_terms' => 'No nos hacemos responsables por objetos dejados en el vehículo',
            'show_logo_on_receipt' => true,
            'timezone' => 'America/Mexico_City',
            'currency' => 'MXN',
            'currency_symbol' => '$',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Log::info('Empresa creada', ['name' => 'Parki']);
    }

    // ========================================
    // SEED: CONFIGURACIÓN DE IMPRESORA
    // ========================================

    private function seedPrinterConfiguration()
    {
        $now = now();

        DB::table('printer_configurations')->insert([
            'name' => 'Impresora Térmica Principal',
            'driver' => 'escpos',
            'connection_type' => 'usb',
            'connection_string' => '/dev/usb/lp0',
            'paper_width' => '80',
            'is_default' => true,
            'settings' => json_encode([
                'cuts_per_print' => 1,
                'line_spacing' => 30,
                'font_size' => 'normal',
            ]),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Log::info('Impresora configurada', ['name' => 'Impresora Térmica Principal']);
    }

    // ========================================
    // SEED: TIPOS DE VEHÍCULOS Y TARIFAS
    // ========================================

    private function seedTypesAndRates()
    {
        $now = now();

        // AUTOMÓVIL
        $autoId = DB::table('types')->insertGetId([
            'name' => 'Automóvil',
            'image' => null,
            'order' => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('rates')->insert([
            ['type_id' => $autoId, 'description' => 'Tarifa por hora', 'price' => 25.00, 'time' => 60, 'rate_type' => 'hourly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $autoId, 'description' => 'Tarifa por día', 'price' => 150.00, 'time' => 1440, 'rate_type' => 'daily', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $autoId, 'description' => 'Tarifa mensual', 'price' => 3000.00, 'time' => 43200, 'rate_type' => 'monthly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // MOTOCICLETA
        $motoId = DB::table('types')->insertGetId([
            'name' => 'Motocicleta',
            'image' => null,
            'order' => 2,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('rates')->insert([
            ['type_id' => $motoId, 'description' => 'Tarifa por hora', 'price' => 15.00, 'time' => 60, 'rate_type' => 'hourly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $motoId, 'description' => 'Tarifa por día', 'price' => 80.00, 'time' => 1440, 'rate_type' => 'daily', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $motoId, 'description' => 'Tarifa mensual', 'price' => 1500.00, 'time' => 43200, 'rate_type' => 'monthly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // CAMIONETA
        $camionetaId = DB::table('types')->insertGetId([
            'name' => 'Camioneta',
            'image' => null,
            'order' => 3,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('rates')->insert([
            ['type_id' => $camionetaId, 'description' => 'Tarifa por hora', 'price' => 35.00, 'time' => 60, 'rate_type' => 'hourly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $camionetaId, 'description' => 'Tarifa por día', 'price' => 200.00, 'time' => 1440, 'rate_type' => 'daily', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $camionetaId, 'description' => 'Tarifa mensual', 'price' => 4000.00, 'time' => 43200, 'rate_type' => 'monthly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // BICICLETA
        $biciId = DB::table('types')->insertGetId([
            'name' => 'Bicicleta',
            'image' => null,
            'order' => 4,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('rates')->insert([
            ['type_id' => $biciId, 'description' => 'Tarifa por hora', 'price' => 10.00, 'time' => 60, 'rate_type' => 'hourly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $biciId, 'description' => 'Tarifa por día', 'price' => 50.00, 'time' => 1440, 'rate_type' => 'daily', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => $biciId, 'description' => 'Tarifa mensual', 'price' => 800.00, 'time' => 43200, 'rate_type' => 'monthly', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        Log::info('Tipos y tarifas creados', ['types' => 4, 'rates' => 12]);
    }

    // ========================================
    // SEED: ESPACIOS DE ESTACIONAMIENTO
    // ========================================

    private function seedSpaces()
    {
        $now = now();
        $types = DB::table('types')->pluck('id', 'name');
        $spaces = [];

        // Espacios para automóviles (A01-A20)
        for ($i = 1; $i <= 20; $i++) {
            $spaces[] = [
                'code' => 'A' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'description' => 'Espacio para automóvil',
                'type_id' => $types['Automóvil'],
                'status' => $i <= 15 ? 'available' : 'occupied',
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Espacios para motocicletas (M01-M10)
        for ($i = 1; $i <= 10; $i++) {
            $spaces[] = [
                'code' => 'M' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'description' => 'Espacio para motocicleta',
                'type_id' => $types['Motocicleta'],
                'status' => $i <= 7 ? 'available' : 'occupied',
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Espacios para camionetas (C01-C10)
        for ($i = 1; $i <= 10; $i++) {
            $spaces[] = [
                'code' => 'C' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'description' => 'Espacio para camioneta',
                'type_id' => $types['Camioneta'],
                'status' => $i <= 6 ? 'available' : 'occupied',
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('parking_spaces')->insert($spaces);
        Log::info('Espacios creados', ['total' => count($spaces)]);
    }

    // ========================================
    // SEED: CLIENTES
    // ========================================

    private function seedCustomers()
    {
        $now = now();

        $customers = [
            ['name' => 'Juan Pérez García', 'email' => 'juan.perez@example.com', 'phone' => '5551234567', 'mobile' => '5559876543', 'address' => 'Av. Reforma 123', 'city' => 'Ciudad de México', 'state' => 'CDMX', 'zip_code' => '06600', 'country' => 'México', 'notes' => 'Cliente frecuente', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'María González López', 'email' => 'maria.gonzalez@example.com', 'phone' => '5552345678', 'mobile' => '5558765432', 'address' => 'Calle Juárez 456', 'city' => 'Guadalajara', 'state' => 'Jalisco', 'zip_code' => '44100', 'country' => 'México', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Carlos Rodríguez Hernández', 'email' => 'carlos.rodriguez@example.com', 'phone' => '5553456789', 'mobile' => '5557654321', 'address' => 'Blvd. Hidalgo 789', 'city' => 'Monterrey', 'state' => 'Nuevo León', 'zip_code' => '64000', 'country' => 'México', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ana Martínez Sánchez', 'email' => 'ana.martinez@example.com', 'phone' => '5554567890', 'mobile' => '5556543210', 'address' => 'Calle Morelos 321', 'city' => 'Puebla', 'state' => 'Puebla', 'zip_code' => '72000', 'country' => 'México', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Luis Torres Ramírez', 'email' => 'luis.torres@example.com', 'phone' => '5555678901', 'mobile' => '5555432109', 'address' => 'Av. Insurgentes 654', 'city' => 'Querétaro', 'state' => 'Querétaro', 'zip_code' => '76000', 'country' => 'México', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('customers')->insert($customers);
        Log::info('Clientes creados', ['total' => count($customers)]);
    }

    // ========================================
    // SEED: VEHÍCULOS
    // ========================================

    private function seedVehicles()
    {
        $now = now();
        $customers = DB::table('customers')->pluck('id')->toArray();
        $types = DB::table('types')->pluck('id', 'name');

        $vehicles = [
            ['plate' => 'ABC123', 'brand' => 'Toyota', 'model' => 'Corolla', 'year' => 2020, 'color' => 'Blanco', 'type_id' => $types['Automóvil'], 'customer_id' => $customers[0], 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['plate' => 'XYZ789', 'brand' => 'Honda', 'model' => 'Civic', 'year' => 2021, 'color' => 'Negro', 'type_id' => $types['Automóvil'], 'customer_id' => $customers[1] ?? $customers[0], 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['plate' => 'MOT456', 'brand' => 'Yamaha', 'model' => 'FZ', 'year' => 2022, 'color' => 'Azul', 'type_id' => $types['Motocicleta'], 'customer_id' => $customers[2] ?? $customers[0], 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['plate' => 'CAM789', 'brand' => 'Ford', 'model' => 'F-150', 'year' => 2019, 'color' => 'Rojo', 'type_id' => $types['Camioneta'], 'customer_id' => $customers[3] ?? $customers[0], 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['plate' => 'DEF321', 'brand' => 'Nissan', 'model' => 'Sentra', 'year' => 2023, 'color' => 'Gris', 'type_id' => $types['Automóvil'], 'customer_id' => $customers[4] ?? $customers[0], 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('vehicles')->insert($vehicles);
        Log::info('Vehículos creados', ['total' => count($vehicles)]);
    }

    // ========================================
    // SEED: RENTAS
    // ========================================

    private function seedRentals()
    {
        $now = now();
        $vehicles = DB::table('vehicles')->get();
        $spaces = DB::table('parking_spaces')->where('status', 'occupied')->take(5)->get();
        $userId = auth()->id();

        if ($vehicles->isEmpty() || $spaces->isEmpty()) {
            Log::warning('Sin vehículos o espacios para crear rentas');
            return;
        }

        $rentals = [];
        $barcodeCounter = 1;

        // Crear 5 rentas cerradas
        foreach ($vehicles->take(5) as $index => $vehicle) {
            $rate = DB::table('rates')
                ->where('type_id', $vehicle->type_id)
                ->where('rate_type', 'hourly')
                ->where('active', true)
                ->first();

            if (!$rate || !isset($spaces[$index])) continue;

            $checkIn = $now->copy()->subHours(rand(2, 8));
            $checkOut = $checkIn->copy()->addHours(rand(1, 4));
            $totalMinutes = $checkIn->diffInMinutes($checkOut);
            $totalAmount = ($rate->price / $rate->time) * $totalMinutes;

            $rentals[] = [
                'barcode' => 'RNT-' . str_pad((string)$barcodeCounter++, 6, '0', STR_PAD_LEFT),
                'space_id' => $spaces[$index]->id,
                'rate_id' => $rate->id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $vehicle->customer_id,
                'user_id' => $userId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_time' => $totalMinutes,
                'total_amount' => round($totalAmount, 2),
                'paid_amount' => round($totalAmount, 2),
                'change_amount' => 0,
                'status' => 'closed',
                'rental_type' => 'hourly',
                'description' => 'Renta completada',
                'notes' => null,
                'created_at' => $checkIn,
                'updated_at' => $checkOut,
            ];
        }

        if (!empty($rentals)) {
            DB::table('rentals')->insert($rentals);
            Log::info('Rentas creadas', ['total' => count($rentals)]);
        }
    }

    // ========================================
    // SEED: CORTES DE CAJA
    // ========================================

    private function seedCashClosures()
    {
        $now = now();
        $userId = auth()->id();

        $rentals = DB::table('rentals')
            ->where('status', 'closed')
            ->where('check_out', '>=', $now->copy()->subDays(7))
            ->get();

        if ($rentals->isEmpty()) {
            Log::warning('Sin rentas para crear cortes de caja');
            return;
        }

        $rentalsByDate = $rentals->groupBy(function ($rental) {
            return \Carbon\Carbon::parse($rental->check_out)->format('Y-m-d');
        });

        $closures = [];

        foreach ($rentalsByDate as $date => $dayRentals) {
            $periodStart = \Carbon\Carbon::parse($date)->startOfDay();
            $periodEnd = \Carbon\Carbon::parse($date)->endOfDay();

            $expectedCash = $dayRentals->sum('total_amount');
            $totalRentals = $dayRentals->count();
            $realCash = $expectedCash * (rand(95, 100) / 100);

            $closures[] = [
                'user_id' => $userId,
                'cashier_id' => null,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'expected_cash' => $expectedCash,
                'total_rentals' => $totalRentals,
                'average_per_rental' => $totalRentals > 0 ? $expectedCash / $totalRentals : 0,
                'real_cash' => round($realCash, 2),
                'difference' => round($realCash - $expectedCash, 2),
                'open_tickets' => 0,
                'had_open_tickets' => false,
                'notes' => 'Corte automático',
                'status' => 'closed',
                'created_at' => $periodEnd,
                'updated_at' => $periodEnd,
            ];
        }

        if (!empty($closures)) {
            DB::table('cash_closures')->insert($closures);
            Log::info('Cortes de caja creados', ['total' => count($closures)]);
        }
    }

    // ========================================
    // LIMPIAR CACHES
    // ========================================

    private function clearCaches()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Log::info('Caches limpiados');
    }

    // ========================================
    // RENDER
    // ========================================

    public function render()
    {
        return view('livewire.system-reset', [
            'typesCount' => Type::count(),
            'ratesCount' => Rate::count(),
            'spacesCount' => Space::count(),
            'customersCount' => Customer::count(),
            'vehiclesCount' => Vehicle::count(),
            'rentalsCount' => Rental::count(),
            'closuresCount' => CashClosure::count(),
        ]);
    }
}
