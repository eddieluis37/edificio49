<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\TreasuryEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyData2026Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener o asegurar cuentas contables unificadas
        $cajaMenor = Account::firstOrCreate(['code' => '110505'], ['name' => 'Caja Menor Administracion', 'level' => 3, 'type' => 'asset']);
        $bancos = Account::firstOrCreate(['code' => '111005'], ['name' => 'Bancos Fondos Operativos', 'level' => 3, 'type' => 'asset']);
        $recaudosAdm = Account::firstOrCreate(['code' => '4105'], ['name' => 'Recaudos Administracion', 'level' => 2, 'type' => 'income']);
        $gastosGen = Account::firstOrCreate(['code' => '5195'], ['name' => 'Gastos Diversos / Otros', 'level' => 2, 'type' => 'expense']);

        // 2. Poblar Movimientos de Caja Menor 2026
        $this->command->info('Seeding Detalle Caja Menor 2026 (Modulo Tesorería)...');

        $cajaMenorData = [
            // [Fecha, Concepto, Ingreso, Egreso]
            ['2026-01-04', 'Apto 202 Admón /Honor', 163600, 0],
            ['2026-01-06', 'Nómina Jeimy 2 días', 0, 135200],
            ['2026-01-06', 'Soldad cárcamo,otros', 0, 36380],
            ['2026-01-07', 'S-101 Faltant cuot admón', 18400, 0],
            ['2026-01-08', 'Enel', 0, 37780],
            ['2026-01-23', 'Nómina Jeimy Cuellar', 0, 244500],
            ['2026-01-30', 'Honorarios ARC', 0, 400000],
            ['2026-02-03', 'Acueducto', 0, 24570],
            ['2026-02-05', 'Apto 202 Admón /Honor', 163600, 0],
            ['2026-02-06', 'Nómina Jeimy', 0, 268000],
            ['2026-02-07', 'Apto S-101 falt cuot admón', 18400, 0],
            ['2026-02-10', 'Camb guardas.elem aseo', 0, 192643],
            ['2026-02-10', 'Retiro Dav gastos varios', 1000000, 0],
            ['2026-02-15', 'Tapa tanq.arreg filtrac teja', 0, 218400],
            ['2026-02-20', 'Nómina Jeimy Cuellar', 0, 268000],
            ['2026-02-27', 'Retiro Dav gastos varios', 1500000, 0],
            ['2026-02-28', 'Reconexión tubo agua', 0, 85200],
            ['2026-02-28', 'Honorarios ARC', 0, 400000],
            ['2026-03-04', 'Coloc y asegur.tapa tanque', 0, 50000],
            ['2026-03-07', 'Nómina Jeimy', 0, 268000],
            ['2026-03-07', 'Apto 202 Admón /Honor', 163600, 0],
            ['2026-03-07', 'Apto S-101 falt cuot admón', 18400, 0],
            ['2026-03-13', 'Enel', 0, 30500],
        ];

        foreach ($cajaMenorData as $data) {
            $fecha = $data[0];
            $concepto = $data[1];
            $ingreso = $data[2];
            $egreso = $data[3];
            $monto = $ingreso > 0 ? $ingreso : $egreso;
            $type = $ingreso > 0 ? 'petty_cash_in' : 'petty_cash_out';

            DB::transaction(function () use ($fecha, $concepto, $monto, $type, $cajaMenor, $bancos, $recaudosAdm, $gastosGen) {
                // Determinar contrapartida
                if ($type === 'petty_cash_in') {
                    // Si es retiro, viene de Bancos, si es Admón viene de Recaudos
                    $contra = str_contains(strtolower($concepto), 'retiro') ? $bancos : $recaudosAdm;
                } else {
                    $contra = $gastosGen;
                }

                // Crear Asiento
                $je = JournalEntry::create([
                    'number' => 'LEG-PC26-' . time() . rand(10, 99),
                    'date' => $fecha,
                    'description' => 'Caja Menor 2026: ' . $concepto,
                    'total_debit' => $monto,
                    'total_credit' => $monto,
                    'status' => 'posted'
                ]);

                if ($type === 'petty_cash_in') {
                    JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $cajaMenor->id, 'debit' => $monto, 'credit' => 0, 'description' => $concepto]);
                    JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $contra->id, 'debit' => 0, 'credit' => $monto, 'description' => $concepto]);
                    $cajaMenor->increment('balance', $monto);
                } else {
                    JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $contra->id, 'debit' => $monto, 'credit' => 0, 'description' => $concepto]);
                    JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $cajaMenor->id, 'debit' => 0, 'credit' => $monto, 'description' => $concepto]);
                    $cajaMenor->decrement('balance', $monto);
                }

                // Crear Movimiento Tesorería
                TreasuryEntry::create([
                    'type' => $type,
                    'date' => $fecha,
                    'amount' => $monto,
                    'description' => $concepto,
                    'account_id' => $cajaMenor->id,
                    'counterpart_account_id' => $contra->id,
                    'journal_entry_id' => $je->id,
                    'status' => 'posted'
                ]);
            });
        }

        $this->command->info('✅ Migración de Caja Menor 2026 completada.');
    }
}
