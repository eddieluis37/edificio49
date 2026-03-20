<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class TreasuryAccountsSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar estructura base
        $assets = Account::firstOrCreate(['code' => '1'], ['name' => 'ACTIVO', 'level' => 1, 'type' => 'asset']);
        $disponible = Account::firstOrCreate(['code' => '1.1'], ['name' => 'Disponible', 'level' => 2, 'parent_id' => $assets->id, 'type' => 'asset']);
        
        Account::firstOrCreate(['code' => '110505'], ['name' => 'Caja Menor Administracion', 'level' => 3, 'parent_id' => $disponible->id, 'type' => 'asset']);
        Account::firstOrCreate(['code' => '111005'], ['name' => 'Bancos Fondos Operativos', 'level' => 3, 'parent_id' => $disponible->id, 'type' => 'asset']);
        
        $income = Account::firstOrCreate(['code' => '4'], ['name' => 'INGRESOS', 'level' => 1, 'type' => 'income']);
        Account::firstOrCreate(['code' => '4195'], ['name' => 'Ingresos Varios / No Operacionales', 'level' => 2, 'parent_id' => $income->id, 'type' => 'income']);
        
        $expenses = Account::firstOrCreate(['code' => '5'], ['name' => 'GASTOS', 'level' => 1, 'type' => 'expense']);
        Account::firstOrCreate(['code' => '5195'], ['name' => 'Gastos Diversos / Otros', 'level' => 2, 'parent_id' => $expenses->id, 'type' => 'expense']);
    }
}
