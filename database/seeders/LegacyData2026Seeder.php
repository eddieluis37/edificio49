<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\TreasuryEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyData2026Seeder extends Seeder
{
    public function run(): void
    {
        // 0. Limpiar datos previos de migración 2026 para evitar duplicidad
        $this->command->info('Depurando migración 2026 y eliminando duplicidades...');

        $previousJEs = JournalEntry::where('number', 'like', 'LEG-PC26-%')->get();

        // Revertir impactos en balance antes de borrar para mantener integridad si se re-ejecuta
        foreach ($previousJEs as $je) {
            foreach ($je->items as $item) {
                $account = $item->account;
                if ($account) {
                    if ($item->debit > 0)
                        $account->decrement('balance', $item->debit);
                    if ($item->credit > 0)
                        $account->increment('balance', $item->credit);
                }
            }
            // Borrar registros relacionados
            TreasuryEntry::where('journal_entry_id', $je->id)->delete();
            Expense::where('journal_entry_id', $je->id)->delete();
            JournalItem::where('journal_entry_id', $je->id)->delete();
            $je->delete();
        }

        // 1. Obtener o asegurar cuentas contables unificadas
        $cajaMenor = Account::firstOrCreate(['code' => '110505'], ['name' => 'Caja Menor Administracion', 'level' => 3, 'type' => 'asset']);
        $bancos = Account::firstOrCreate(['code' => '111005'], ['name' => 'Bancos Fondos Operativos', 'level' => 3, 'type' => 'asset']);
        $recaudosAdm = Account::firstOrCreate(['code' => '4105'], ['name' => 'Recaudos Administracion', 'level' => 2, 'type' => 'income']);
        $gastosGen = Account::firstOrCreate(['code' => '5195'], ['name' => 'Gastos Diversos / Otros', 'level' => 2, 'type' => 'expense']);

        // 2. Poblar Movimientos Consolidados de 2026
        // Datos corregidos según imágenes detalladas de Nómina, Servicios y Gastos Generales.
        $this->command->info('Seeding Detalle Caja Menor y Egresos 2026...');

        $cajaMenorData = [
            // [Fecha, Concepto, Ingreso, Egreso, Categoria (opcional)]
            ['2026-01-04', 'Apto 202 Admón /Honor', 163600, 0, 'Recaudos'],
            ['2026-01-06', 'Nómina Jeimy 2 días', 0, 135200, 'Nomina'],
            ['2026-01-06', 'Soldad cárcamo,otros', 0, 36380, 'Mantenimiento'],
            ['2026-01-07', 'S-101 Faltant cuot admón', 18400, 0, 'Recaudos'],
            ['2026-01-08', 'Enel', 0, 33780, 'Servicios Publicos'],
            ['2026-01-23', 'Nómina Jeimy Cuellar', 0, 244500, 'Nomina'],
            ['2026-01-30', 'Honorarios ARC', 0, 512000, 'Honorarios'],
            ['2026-02-03', 'Acueducto (E.a.a.b)', 0, 24570, 'Servicios Publicos'],
            ['2026-02-05', 'Apto 202 Admón /Honor', 163600, 0, 'Recaudos'],
            ['2026-02-06', 'Nómina Jeimy', 0, 268000, 'Nomina'],
            ['2026-02-07', 'Apto S-101 falt cuot admón', 18400, 0, 'Recaudos'],
            ['2026-02-10', 'Camb guardas.elem aseo', 0, 192643, 'Mantenimiento'],
            ['2026-02-10', 'Retiro Dav gastos varios', 1000000, 0, 'Replenish'],
            ['2026-02-15', 'Tapa tanq.arreg filtrac teja', 0, 218400, 'Mantenimiento'],
            ['2026-02-20', 'Nómina Jeimy Cuellar', 0, 268000, 'Nomina'],
            ['2026-02-27', 'Retiro Dav gastos varios', 1500000, 0, 'Replenish'],
            ['2026-02-28', 'Reconexión tubo agua', 0, 85200, 'Mantenimiento'],
            ['2026-02-28', 'Honorarios ARC', 0, 512000, 'Honorarios'],
            ['2026-03-04', 'Coloc y asegur.tapa tanque', 0, 50000, 'Mantenimiento'],
            ['2026-03-07', 'Nómina Jeimy', 0, 268000, 'Nomina'],
            ['2026-03-07', 'Apto 202 Admón /Honor', 163600, 0, 'Recaudos'],
            ['2026-03-07', 'Apto S-101 falt cuot admón', 18400, 0, 'Recaudos'],
            ['2026-03-13', 'Enel', 0, 30500, 'Servicios Publicos'],
        ];

        foreach ($cajaMenorData as $index => $data) {
            [$fecha, $concepto, $ingreso, $egreso, $cat] = $data;
            $monto = $ingreso > 0 ? $ingreso : $egreso;
            $type = $ingreso > 0 ? 'petty_cash_in' : 'petty_cash_out';

            DB::transaction(function () use ($index, $fecha, $concepto, $monto, $type, $cat, $cajaMenor, $bancos, $recaudosAdm, $gastosGen) {
                // Determinar contrapartida
                $contra = ($type === 'petty_cash_in')
                    ? (str_contains(strtolower($concepto), 'retiro') ? $bancos : $recaudosAdm)
                    : $gastosGen;

                // Crear Asiento
                $je = JournalEntry::create([
                    'number' => 'LEG-PC26-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $fecha,
                    'description' => 'Migración 2026: ' . $concepto,
                    'total_debit' => $monto,
                    'total_credit' => $monto,
                    'status' => 'posted'
                ]);

                // Partidas Contables (unificadas para evitar duplicidad de lógica)
                $debitAccount = ($type === 'petty_cash_in') ? $cajaMenor : $contra;
                $creditAccount = ($type === 'petty_cash_in') ? $contra : $cajaMenor;

                JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $debitAccount->id, 'debit' => $monto, 'credit' => 0, 'description' => $concepto]);
                JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $creditAccount->id, 'debit' => 0, 'credit' => $monto, 'description' => $concepto]);

                // Actualizar Balances (con precaución)
                if ($type === 'petty_cash_in') {
                    $cajaMenor->increment('balance', $monto);
                } else {
                    $cajaMenor->decrement('balance', $monto);
                }

                // Registro Tesorería (La fuente de verdad para movimientos de Caja Menor)
                TreasuryEntry::create([
                    'type' => $type,
                    'date' => $fecha,
                    'amount' => $monto,
                    'description' => $concepto,
                    'account_id' => $cajaMenor->id,
                    'counterpart_account_id' => $contra->id,
                    'journal_entry_id' => $je->id,
                    'status' => 'posted',
                    'reference_doc' => 'LEG26-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)
                ]);
            });
        }

        $this->command->info('✅ Migración 2026 depurada y cargada sin duplicidades.');
    }
}
