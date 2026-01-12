<?php
// database/seeders/CompanySeeder.php (versão simplificada)

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem empresas
        if (Company::count() > 0) {
            $this->command->info('Companies already seeded.');
            return;
        }

        // Obter usuário admin ou primeiro usuário
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        // Obter estado de São Paulo
        $state = State::where('uf', 'SP')->first();
        if (!$state) {
            $this->command->error('State SP not found. Please run StateSeeder first.');
            return;
        }

        // Obter cidade de São Paulo
        $city = City::where('name', 'São Paulo')
                    ->where('uf', 'SP')
                    ->first();
        if (!$city) {
            $this->command->error('City São Paulo not found. Please run CitySeeder first.');
            return;
        }

        // Obter ou criar categoria padrão
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'client_id' => $user->client_id ?? 0,
                'name' => 'Tecnologia',
                'slug' => 'tecnologia',
                'description' => 'Empresas de tecnologia',
                'type' => 'company_category',
                'active' => true,
                'created_by' => $user->id,
            ]);
        }

        // Dados da empresa padrão
        $companyData = [
            'client_id' => $user->client_id ?? 0,
            'type' => 1,
            'cnpj' => '12345678000190',
            'trade_name' => 'Cliente Padrão LTDA',
            'company_name' => 'Cliente Padrão LTDA',
            'state_registration' => '123456789012',
            'municipal_registration' => '987654321',
            'street' => 'Avenida Paulista',
            'number' => '1000',
            'neighborhood' => 'Bela Vista',
            'zip_code' => '01310-100',
            'state_id' => $state->id,
            'city_id' => $city->id,
            'cnae' => '6201500',
            'opening_date' => '2020-01-15',
            'is_headquarters' => true,
            'category_id' => $category->id,
            'tax_regime' => 1,
            'contacts' => json_encode([
                [
                    'type' => 'email',
                    'value' => 'contato@empresaexemplo.com.br',
                    'is_main' => true,
                ]
            ]),
            'credit_limit' => 50000.00,
            'used_credit' => 0.00,
            'activated' => true,
            'situation' => 1,
            'status' => true,
            'notes' => 'Empresa de exemplo para testes',
            'created_by' => $user->id,
        ];

        // Criar empresa
        Company::create($companyData);
        
        $this->command->info('Company seeded successfully.');
    }
}