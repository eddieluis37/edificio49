<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'name' => 'AdminEdificio49',
            'business_name' => 'Admin Edificio 49',
            'rfc' => 'PES240101ABC',
            'email' => 'info@edificio49.com',
            'phone' => '3234769453',
            'mobile' => '3175436606',
            'website' => 'https://edificio49.com',
            'address' => 'Cra 49 # 15 - 59',
            'city' => 'Ciudad de Bogota',
            'state' => 'Cundinamarca',
            'country' => 'Bogota',
            'postal_code' => '11211',
            'logo_path' => null,
            'logo_url' => null,
            'receipt_footer' => '¡Gracias por su preferencia!',
            'receipt_terms' => 'Somos una empresa amigable con el medio ambiente',
            'show_logo_on_receipt' => true,
            'timezone' => 'America/Bogota',
            'currency' => 'COL',
            'currency_symbol' => '$',
            'is_active' => true,
        ]);
    }
}
