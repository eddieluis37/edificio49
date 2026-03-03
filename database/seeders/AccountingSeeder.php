<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Service;
use App\Models\AdminFeeSetting;
use App\Models\MonthlyInterestRate;
use App\Models\Apartment;
use App\Models\Owner;
use Carbon\Carbon;

class AccountingSeeder extends Seeder {
    public function run(): void {
        // Cuentas mínimas
        $assets = Account::create(['code'=>'1','name'=>'ACTIVO','level'=>1,'type'=>'asset']);
        $cash = Account::create(['code'=>'1.1','name'=>'Caja y Bancos','level'=>2,'parent_id'=>$assets->id,'type'=>'asset']);
        $receivables = Account::create(['code'=>'1.2','name'=>'Cuentas por Cobrar Propietarios','level'=>2,'parent_id'=>$assets->id,'type'=>'asset']);

        $liab = Account::create(['code'=>'2','name'=>'PASIVO','level'=>1,'type'=>'liability']);
        $equity = Account::create(['code'=>'3','name'=>'PATRIMONIO','level'=>1,'type'=>'equity']);

        $income = Account::create(['code'=>'4','name'=>'INGRESOS','level'=>1,'type'=>'income']);
        $adminIncome = Account::create(['code'=>'4.1','name'=>'Ingresos por administración','level'=>2,'parent_id'=>$income->id,'type'=>'income']);
        $interestIncome = Account::create(['code'=>'4.2','name'=>'Ingresos por intereses','level'=>2,'parent_id'=>$income->id,'type'=>'income']);

        $expense = Account::create(['code'=>'6','name'=>'GASTOS','level'=>1,'type'=>'expense']);
        $discountExpense = Account::create(['code'=>'6.1.05','name'=>'Descuentos por pronto pago','level'=>3,'parent_id'=>$expense->id,'type'=>'expense']);

        // Servicio admin
        Service::create(['code'=>'ADMIN','name'=>'Cuota Administración','shared_by_coefs'=>true]);

        // Un AdminFeeSetting ejemplo
        AdminFeeSetting::create([
            'year' => Carbon::now()->year,
            'month' => Carbon::now()->month,
            'base_budget' => 2_000_000,
            'early_discount_enabled' => true,
            'early_discount_days' => 10,
            'early_discount_type' => 'fixed',
            'early_discount_value' => 6000,
            'due_date' => Carbon::now()->copy()->endOfMonth()->toDateString()
        ]);

        // Monthly interest example (tasa del mes actual 1.8% mensual -> 0.018)
        MonthlyInterestRate::create([
            'year' => Carbon::now()->year,
            'month' => Carbon::now()->month,
            'rate' => 0.018,
            'source' => 'Manual seed'
        ]);

        // Crear 10 aptos y propietarios (ejemplo)
        for ($i=1; $i<=10; $i++) {
            $apt = Apartment::create(['code'=>"Apt-$i",'floor'=>ceil($i/2),'number'=>$i,'area'=>60+$i,'share_fraction'=>round(1/10,6)]);
            Owner::create(['apartment_id'=>$apt->id,'name'=>"Propietario $i",'document_type'=>'CC','document_number'=>"10000{$i}",'email'=>"prop{$i}@edificio.test",'phone'=>"3000000{$i}"]);
        }
    }
}