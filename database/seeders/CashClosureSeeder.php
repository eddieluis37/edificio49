<?php

namespace Database\Seeders;

use App\Models\Rental;
use App\Models\CashClosure;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;


class CashClosureSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('  No users found. Skipping cash closure seeding.');
            return;
        }

        // Obtener rentas cerradas de los últimos 30 días
        $rentals = Rental::where('status', 'closed')
            ->where('check_out', '>=', Carbon::now()->subDays(30))
            ->orderBy('check_out')
            ->get();

        if ($rentals->isEmpty()) {
            $this->command->warn('  No closed rentals found. Skipping cash closure seeding.');
            return;
        }

        // Agrupar rentas por día
        $rentalsByDate = $rentals->groupBy(function ($rental) {
            return Carbon::parse($rental->check_out)->format('Y-m-d');
        });

        // Generar cierres de caja para cada día
        foreach ($rentalsByDate as $date => $dayRentals) {
            $periodStart = Carbon::parse($date)->startOfDay();
            $periodEnd = Carbon::parse($date)->endOfDay();

            // Calcular montos
            $expectedCash = $dayRentals->sum('total_amount');
            $totalRentals = $dayRentals->count();
            $averagePerRental = $totalRentals > 0 ? $expectedCash / $totalRentals : 0;

            // Simular monto real (95-100% del esperado)
            $variance = rand(95, 100) / 100;
            $realCash = round($expectedCash * $variance, 2);
            $difference = $realCash - $expectedCash;

            // Contar tickets abiertos (rentas en 'open')
            $openTickets = Rental::where('status', 'open')
                ->whereDate('check_in', $date)
                ->count();

            CashClosure::create([
                'user_id' => $user->id,
                'cashier_id' => null, // Cierre general
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'expected_cash' => $expectedCash,
                'total_rentals' => $totalRentals,
                'average_per_rental' => $averagePerRental,
                'real_cash' => $realCash,
                'difference' => $difference,
                'open_tickets' => $openTickets,
                'had_open_tickets' => $openTickets > 0,
                'notes' => $difference >= 0
                    ? 'Sobrante: $' . number_format(abs($difference), 2)
                    : 'Faltante: $' . number_format(abs($difference), 2),
                'status' => 'closed',
            ]);
        }

        $this->command->info(' Created ' . CashClosure::count() . ' cash closures');
    }
}
