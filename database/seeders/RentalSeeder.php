<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Space;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\ParkingSpace;
use Illuminate\Database\Seeder;


class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = Vehicle::with('customer', 'vehicleRate')->get();
        $occupiedSpaces = Space::where('status', 'occupied')->get();
        $availableSpaces = Space::where('status', 'available')->get();
        $user = User::first();

        if ($vehicles->isEmpty() || !$user) {
            $this->command->warn(' No vehicles or users found. Skipping rental seeding.');
            return;
        }

        // ========================================
        // RENTAS ACTIVAS (últimos 7 días)
        // ========================================
        $activeRentalsCount = min(5, $occupiedSpaces->count());

        for ($i = 0; $i < $activeRentalsCount; $i++) {
            $vehicle = $vehicles->random();
            $space = $occupiedSpaces[$i];
            $rateModel = $vehicle->vehicleRate;

            if (!$rateModel) continue;

            $checkInDate = Carbon::now()->subDays(rand(1, 7));

            Rental::create([
                'barcode' => 'RNT-' . str_pad((string)($i + 1), 6, '0', STR_PAD_LEFT),
                'space_id' => $space->id,
                'rate_id' => $rateModel->id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $vehicle->customer_id,
                'user_id' => $user->id,
                'check_in' => $checkInDate,
                'check_out' => null,
                'total_time' => null,
                'total_amount' => 0,
                'paid_amount' => 0,
                'change_amount' => 0,
                'status' => 'open',
                'rental_type' => 'hourly',
                'description' => 'Renta activa',
                'notes' => 'En curso',
            ]);
        }

        // ========================================
        // RENTAS COMPLETADAS (últimos 30 días)
        // ========================================
        for ($i = 0; $i < 20; $i++) {
            $vehicle = $vehicles->random();
            $space = $availableSpaces->random();
            $rateModel = $vehicle->vehicleRate;

            if (!$rateModel) continue;

            $daysAgo = rand(1, 30);
            $checkInDate = Carbon::now()->subDays($daysAgo);

            $rand = rand(1, 100);
            if ($rand <= 50) {
                // Renta por hora (2-8 horas)
                $hours = rand(2, 8);
                $checkOutDate = $checkInDate->copy()->addHours($hours);
                $totalMinutes = $hours * 60;
                $amountPaid = $rateModel->price * $hours;
            } elseif ($rand <= 85) {
                // Renta por día (1-5 días)
                $days = rand(1, 5);
                $checkOutDate = $checkInDate->copy()->addDays($days);
                $totalMinutes = $days * 1440;
                $amountPaid = $rateModel->price * $days;
            } else {
                // Renta mensual
                $checkOutDate = $checkInDate->copy()->addMonth();
                $totalMinutes = 43200;
                $amountPaid = $rateModel->price;
            }

            Rental::create([
                'barcode' => 'RNT-' . str_pad((string)(100 + $i), 6, '0', STR_PAD_LEFT),
                'space_id' => $space->id,
                'rate_id' => $rateModel->id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $vehicle->customer_id,
                'user_id' => $user->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'total_time' => $totalMinutes,
                'total_amount' => $amountPaid,
                'paid_amount' => $amountPaid,
                'change_amount' => 0,
                'status' => 'closed',
                'rental_type' => 'hourly',
                'description' => 'Renta completada',
                'notes' => 'Completada satisfactoriamente',
            ]);
        }

        $this->command->info('✅ Created ' . Rental::count() . ' rental records');
    }
}
