<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyData2025Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Asegurar cuentas contables para Caja Menor y Contrapartidas
        $cajaMenor = Account::firstOrCreate(
            ['code' => '1.1.05'],
            [
                'name' => 'Caja Menor',
                'level' => 3,
                'parent_id' => Account::where('code', '1.1')->first()?->id,
                'type' => 'asset'
            ]
        );

        $ingresosAdm = Account::where('code', '4.1')->first();
        if (!$ingresosAdm) {
            $ingresosAdm = Account::create(['code' => '4.1', 'name' => 'Ingresos por administración', 'level' => 2, 'type' => 'income']);
        }

        $gastosGen = Account::where('code', '6')->first();
        if (!$gastosGen) {
            $gastosGen = Account::create(['code' => '6', 'name' => 'GASTOS', 'level' => 1, 'type' => 'expense']);
        }

        // Cuenta para transferencias internas (ej. Retiros de bancos a caja menor)
        $cuentaPuente = Account::firstOrCreate(
            ['code' => '1.1.99'],
            ['name' => 'Cuenta Puente / Transferencias', 'level' => 3, 'type' => 'asset']
        );

        // 2. Poblar Gastos Generales (Modelo Expense)
        $this->command->info('Seeding Gastos Generales 2025...');
        $gastosData = [
            ['date' => '2025-03-25', 'description' => 'Papelería, impl aseo', 'amount' => 77500],
            ['date' => '2025-05-21', 'description' => 'fot.rejilla, elem aseo', 'amount' => 30614],
            ['date' => '2025-06-21', 'description' => 'Limp tanques, recarg ext', 'amount' => 419719],
            ['date' => '2025-09-17', 'description' => 'Cuenta de cobro A.Hdez', 'amount' => 534000],
            ['date' => '2025-08-19', 'description' => 'copias llaveselem aseo', 'amount' => 16900],
            ['date' => '2025-10-28', 'description' => 'Element aseo papeler', 'amount' => 45920],
            ['date' => '2025-12-11', 'description' => 'Impresiones, elem aseo', 'amount' => 36640],
            ['date' => '2025-12-22', 'description' => 'Ancheta Navid emplead', 'amount' => 53000],
        ];

        foreach ($gastosData as $gasto) {
            Expense::create([
                'date' => $gasto['date'],
                'description' => $gasto['description'],
                'amount' => $gasto['amount'],
                'category' => 'Gasto General',
                'status' => 'paid',
                'payment_method' => 'Cash'
            ]);
        }

        // 3. Poblar Caja Menor (JournalEntries)
        $this->command->info('Seeding Caja Menor 2025...');
        
        $cajaMenorData = [
            // [Fecha, Concepto, Ingreso, Egreso]
            ['2025-01-01', 'Viene dic/2024 (Saldo Inicial)', 361800, 0],
            ['2025-01-13', 'Admón Apto 202-Ord Ene-25', 212000, 0],
            ['2025-01-13', 'Enel', 0, 39420],
            ['2025-01-13', 'Nómina Aracely', 0, 218500],
            ['2025-01-30', 'Nómina Aracely', 0, 218500],
            ['2025-02-04', 'Retiro gastos varios', 1000000, 0],
            ['2025-02-06', 'Admón apto S-101 ene/feb-25', 106000, 0],
            ['2025-02-07', 'Enel', 0, 34740],
            ['2025-02-07', 'Acueducto', 0, 29250],
            ['2025-02-14', 'Nómina Aracely Agudelo', 0, 218500],
            ['2025-03-03', 'Admón apto 202-feb/marz-25', 212000, 0],
            ['2025-03-03', 'Nómina Aracely', 0, 218500],
            ['2025-03-06', 'Enel', 0, 33820],
            ['2025-03-13', 'Nómina Aracely', 0, 218500],
            ['2025-03-25', 'Gastos Varios', 0, 77500],
            ['2025-03-31', 'Nómina Aracely', 0, 218500],
            ['2025-04-03', 'Apto S-101 marz y abril', 106000, 0],
            ['2025-04-04', 'Apto 202 abril', 106000, 0],
            ['2025-04-08', 'Enel', 0, 31390],
            ['2025-04-08', 'Acueducto', 0, 29250],
            ['2025-04-15', 'Nómina Aracely', 0, 218500],
            ['2025-04-24', 'Apto 202 falt (4 meses) x6', 24000, 0],
            ['2025-04-29', 'Nómina Aracely', 0, 218500],
            ['2025-05-02', 'Apto S-101 falt meses ant 4x3', 12000, 0],
            ['2025-05-02', 'Ret.gastos varios dariv', 1000000, 0],
            ['2025-05-05', 'Apto S-101 falt+cuot admón', 53000, 0],
            ['2025-05-05', 'Apto 202 mayo 106 + 50 honor', 164000, 0],
            ['2025-05-08', 'Enel', 0, 33990],
            ['2025-05-12', 'Nómina Aracely', 0, 218500],
            ['2025-05-21', 'Gastos Varios', 0, 30614],
            ['2025-05-26', 'Nómina Aracely', 0, 218500],
            ['2025-05-31', 'Honorarios admor', 0, 400000],
            ['2025-06-03', 'apto 202 cuot admón/honor', 170000, 0],
            ['2025-06-06', 'Acueducto', 0, 29860],
            ['2025-06-07', 'Apto S-101 falt coefic+admón', 53000, 0],
            ['2025-06-11', 'Retiro gastos varios Coomev', 500000, 0],
            ['2025-06-11', 'Nómina Aracely', 0, 218500],
            ['2025-06-20', 'Enel', 0, 32910],
            ['2025-06-21', 'Gastos varios', 0, 419719],
            ['2025-06-25', 'Nómina Aracely', 0, 218500],
            ['2025-06-26', 'Préstamo ARC (1)', 500000, 0],
            ['2025-06-29', 'Honorarios admor', 0, 400000],
            ['2025-07-02', 'Apto 202 vi. traslado abr 24 (1)', 58000, 0],
            ['2025-07-02', 'Apto 202 Cuota julio', 100200, 0],
            ['2025-07-05', 'Apto S-101 falt coef+cuot admón', 17900, 0],
            ['2025-07-09', 'Nómina Aracely', 0, 218500],
            ['2025-07-15', 'Retiro G.varios Daviv', 1000000, 0],
            ['2025-07-23', 'Nómina Aracely', 0, 218500],
            ['2025-07-23', 'Acueducto', 0, 36320],
            ['2025-07-29', 'Gastos Varios', 0, 51470],
            ['2025-07-31', 'Honorarios admor', 0, 400000],
            ['2025-08-04', 'Apto S-101 falt coef+c.admón', 17900, 0],
            ['2025-08-04', 'Enel', 0, 63820],
            ['2025-08-05', 'Nómina Jeimy', 0, 218500],
            ['2025-08-11', 'Apto 202 cuot admón', 100200, 0],
            ['2025-08-11', 'Apto 202 Honor', 58000, 0],
            ['2025-08-19', 'Gastos varios', 0, 16900],
            ['2025-08-19', 'Nómina Jeimy', 0, 218500],
            ['2025-08-20', 'Liquidación Aracely Agudelo', 0, 190700],
            ['2025-08-28', 'Ret. Pag nómin y gast-varios da', 1000000, 0],
            ['2025-08-28', 'Honorarios admor', 0, 400000],
            ['2025-09-02', 'Nómina Jeimy', 0, 218500],
            ['2025-09-05', 'Apto S-101 falt.coef+c.admón', 17900, 0],
            ['2025-09-08', 'Apto 202 cuot admón/honorari', 152000, 0],
            ['2025-09-09', 'Enel', 0, 31110],
            ['2025-09-17', 'Nómina Jeimy', 0, 218500],
            ['2025-09-26', 'Retiro Davi G.grales y nómina', 1000000, 0],
            ['2025-09-26', 'Cta cobro arregl techo 401', 0, 534000],
            ['2025-09-26', 'Acueducto', 0, 30290],
            ['2025-09-30', 'Honorarios admor', 0, 400000],
            ['2025-09-30', 'Nómina Jeimy', 0, 218500],
            ['2025-10-06', 'Apto S101 falt coef+c.admón', 17900, 0],
            ['2025-10-06', 'Apto 202 Admón/honorar', 158200, 0],
            ['2025-10-06', 'Enel', 0, 30650],
            ['2025-10-06', 'Préstamo ARC (2)', 800000, 0],
            ['2025-10-14', 'Nómina Jeimy', 0, 218500],
            ['2025-10-28', 'Nómina Jeimy', 0, 218500],
            ['2025-10-28', 'Gastos papelería elementos aseo', 0, 45920],
            ['2025-10-31', 'Honorarios Administra', 0, 400000],
            ['2025-11-05', 'Retiro gastos Varios daw', 1000000, 0],
            ['2025-11-06', 'Apto S-101 falt.coef+c.admón', 17900, 0],
            ['2025-11-10', 'Apto 202 Admón/honorar', 158200, 0],
            ['2025-11-10', 'Enel', 0, 34390],
            ['2025-11-11', 'Nómina Jeimy', 0, 218500],
            ['2025-11-24', 'Nómina Jeimy', 0, 218500],
            ['2025-11-30', 'Honorarios admor', 0, 400000],
            ['2025-12-02', 'Acueducto', 0, 31180],
            ['2025-12-06', 'Apto S-101 falt coefic', 17900, 0],
            ['2025-12-09', 'Nómina Jeimy', 0, 218500],
            ['2025-12-09', 'Apto 202 Admón/honorar', 158200, 0],
            ['2025-12-11', 'Gastos var, elem aseo, papel', 0, 36640],
            ['2025-12-15', 'Enel', 0, 34480],
            ['2025-12-22', 'Ancheta Navidad emple', 0, 53000],
            ['2025-12-23', 'Retiro Gastos varios', 1500000, 0],
            ['2025-12-23', 'Devolución ARC (1)', 0, 900000],
            ['2025-12-23', 'Nómina Jeimy', 0, 218500],
            ['2025-12-30', 'Prestaciones jeimy', 0, 136900],
            ['2025-12-31', 'Honorar Admor', 0, 400000],
            ['2025-12-31', 'Nómina Jeimy (2 dias)', 0, 109300],
        ];

        foreach ($cajaMenorData as $data) {
            $fecha = $data[0];
            $concepto = $data[1];
            $ingreso = $data[2];
            $egreso = $data[3];

            $entry = JournalEntry::create([
                'date' => $fecha,
                'description' => $concepto,
                'total_debit' => $ingreso > 0 ? $ingreso : $egreso,
                'total_credit' => $ingreso > 0 ? $ingreso : $egreso,
                'status' => 'posted'
            ]);

            if ($ingreso > 0) {
                // Débito a Caja Menor
                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $cajaMenor->id,
                    'debit' => $ingreso,
                    'credit' => 0,
                    'description' => $concepto
                ]);

                // Crédito a Contrapartida (Ingresos o Cuenta Puente)
                $contraAccount = str_contains(strtolower($concepto), 'retiro') ? $cuentaPuente->id : $ingresosAdm->id;
                
                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $contraAccount,
                    'debit' => 0,
                    'credit' => $ingreso,
                    'description' => $concepto
                ]);
            } else {
                // Débito a Gastos
                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $gastosGen->id,
                    'debit' => $egreso,
                    'credit' => 0,
                    'description' => $concepto
                ]);

                // Crédito a Caja Menor
                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $cajaMenor->id,
                    'debit' => 0,
                    'credit' => $egreso,
                    'description' => $concepto
                ]);
            }
        }

        $this->command->info('✅ Data migration for 2025 completed!');
    }
}
