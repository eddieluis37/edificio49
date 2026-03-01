<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'name' => 'AdminSeikou',
            'business_name' => 'Admin Seikou Colombia',
            'rfc' => 'PES240101ABC',
            'email' => 'info@seikou.com',
            'phone' => '3234769453',
            'mobile' => '3175436606',
            'website' => 'https://admin-seikou.369connect.com',
            'address' => 'Cra 19 # 66 - 54',
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
