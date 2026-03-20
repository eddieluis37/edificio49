<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\TreasuryEntry;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyData2025Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener o asegurar cuentas contables unificadas
        $cajaMenor = Account::firstOrCreate(['code' => '110505'], ['name' => 'Caja Menor Administracion', 'level' => 3, 'type' => 'asset']);
        $bancos = Account::firstOrCreate(['code' => '111005'], ['name' => 'Bancos Fondos Operativos', 'level' => 3, 'type' => 'asset']);
        $recaudosAdm = Account::firstOrCreate(['code' => '4105'], ['name' => 'Recaudos Administracion', 'level' => 2, 'type' => 'income']);
        $gastosGen = Account::firstOrCreate(['code' => '5195'], ['name' => 'Gastos Diversos / Otros', 'level' => 2, 'type' => 'expense']);

        // 2. Poblar Gastos Generales (Modelo Expense) 
        // Estos se vinculan con el módulo de Proveedores y Egresos
        $this->command->info('Seeding Gastos Generales 2025 (Modulo Egresos)...');
        $gastosData = [
            ['date' => '2025-03-25', 'description' => 'Papelería, impl aseo', 'amount' => 77500, 'ref' => 'G-250325'],
            ['date' => '2025-05-21', 'description' => 'fot.rejilla, elem aseo', 'amount' => 30614, 'ref' => 'G-250521'],
            ['date' => '2025-06-21', 'description' => 'Limp tanques, recarg ext', 'amount' => 419719, 'ref' => 'G-250621'],
            ['date' => '2025-09-17', 'description' => 'Cuenta de cobro A.Hdez', 'amount' => 534000, 'ref' => 'G-250917'],
            ['date' => '2025-08-19', 'description' => 'copias llaveselem aseo', 'amount' => 16900, 'ref' => 'G-250819'],
            ['date' => '2025-10-28', 'description' => 'Element aseo papeler', 'amount' => 45920, 'ref' => 'G-251028'],
            ['date' => '2025-12-11', 'description' => 'Impresiones, elem aseo', 'amount' => 36640, 'ref' => 'G-251211'],
            ['date' => '2025-12-22', 'description' => 'Ancheta Navid emplead', 'amount' => 53000, 'ref' => 'G-251222'],
        ];

        foreach ($gastosData as $gasto) {
            DB::transaction(function() use ($gasto, $cajaMenor, $gastosGen) {
                // Crear Asiento Contable
                $je = JournalEntry::create([
                    'number' => 'LEG-EXP-' . $gasto['ref'],
                    'date' => $gasto['date'],
                    'description' => "Migración: " . $gasto['description'],
                    'total_debit' => $gasto['amount'],
                    'total_credit' => $gasto['amount'],
                    'status' => 'posted'
                ]);

                // Asiento: Débito Gasto, Crédito Caja Menor (según descripción anterior de migración)
                JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $gastosGen->id, 'debit' => $gasto['amount'], 'credit' => 0, 'description' => $gasto['description']]);
                JournalItem::create(['journal_entry_id' => $je->id, 'account_id' => $cajaMenor->id, 'debit' => 0, 'credit' => $gasto['amount'], 'description' => $gasto['description']]);

                // Crear Movimiento de Tesorería (para que aparezca en el modulo Caja Menor)
                TreasuryEntry::create([
                    'type' => 'petty_cash_out',
                    'date' => $gasto['date'],
                    'amount' => $gasto['amount'],
                    'description' => $gasto['description'],
                    'account_id' => $cajaMenor->id,
                    'counterpart_account_id' => $gastosGen->id,
                    'journal_entry_id' => $je->id,
                    'status' => 'posted',
                    'reference_doc' => $gasto['ref']
                ]);

                // Crear Registro en tabla Expenses
                Expense::create([
                    'date' => $gasto['date'],
                    'description' => $gasto['description'],
                    'amount' => $gasto['amount'],
                    'category' => 'Gasto General',
                    'status' => 'paid',
                    'payment_method' => 'Caja Menor',
                    'reference' => $gasto['ref'],
                    'journal_entry_id' => $je->id
                ]);

                // Actualizar Balances (Simplificado para seeder)
                $cajaMenor->decrement('balance', $gasto['amount']);
                $gastosGen->increment('balance', $gasto['amount']);
            });
        }

        // 3. Poblar Movimientos de Caja Menor (JournalEntries + TreasuryEntries)
        $this->command->info('Seeding Detalle Caja Menor 2025 (Modulo Tesorería)...');
        
        $cajaMenorData = [
            // [Fecha, Concepto, Ingreso, Egreso]
            ['2025-01-01', 'Viene dic/2024 (Saldo Inicial)', 361800, 0],
            ['2025-01-13', 'Admón Apto 202-Ord Ene-25', 212000, 0],
            ['2025-01-13', 'Pago Enel', 0, 39420],
            ['2025-01-13', 'Nómina Aracely', 0, 218500],
            ['2025-01-30', 'Nómina Aracely', 0, 218500],
            ['2025-02-04', 'Retiro gastos varios (Replenish)', 1000000, 0],
            ['2025-02-06', 'Admón apto S-101 ene/feb-25', 106000, 0],
            ['2025-02-07', 'Enel', 0, 34740],
            ['2025-02-07', 'Acueducto', 0, 29250],
            ['2025-02-14', 'Nómina Aracely Agudelo', 0, 218500],
            ['2025-03-03', 'Admón apto 202-feb/marz-25', 212000, 0],
            ['2025-03-03', 'Nómina Aracely', 0, 218500],
            ['2025-10-06', 'Préstamo ARC (Específ)', 800000, 0],
            ['2025-12-23', 'Retiro Gastos varios (Banco)', 1500000, 0],
        ];

        foreach ($cajaMenorData as $data) {
            $fecha = $data[0]; $concepto = $data[1]; $ingreso = $data[2]; $egreso = $data[3];
            $monto = $ingreso > 0 ? $ingreso : $egreso;
            $type = $ingreso > 0 ? 'petty_cash_in' : 'petty_cash_out';

            DB::transaction(function() use ($fecha, $concepto, $monto, $type, $cajaMenor, $bancos, $recaudosAdm, $gastosGen) {
                // Determinar contrapartida
                if ($type === 'petty_cash_in') {
                    // Si es retiro, viene de Bancos, si es Admón viene de Recaudos
                    $contra = str_contains(strtolower($concepto), 'retiro') ? $bancos : $recaudosAdm;
                } else {
                    $contra = $gastosGen;
                }

                // Crear Asiento
                $je = JournalEntry::create([
                    'number' => 'LEG-PC-' . time().rand(10,99),
                    'date' => $fecha,
                    'description' => "Caja Menor: " . $concepto,
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

        $this->command->info('✅ Consistencia de datos para 2025 asegurada en todos los módulos contables.');
    }
}
