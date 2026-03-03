<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Owner;
use App\Models\AdminFeeSetting;
use App\Models\MonthlyInterestRate;
use Carbon\Carbon;

class ExampleInvoicesSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now();

        AdminFeeSetting::updateOrCreate(
            ['year' => $today->year, 'month' => $today->month],
            [
                'base_budget' => 0,
                'rate_per_sqm' => 1877.78,
                'honorarios_default' => 58000,
            ]
        );
    }
}
