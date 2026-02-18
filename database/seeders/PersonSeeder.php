<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter um cliente/admin para associar
        $client = User::where('email', 'admin@ash.com')->first();

        if (!$client) {
            $client = User::first();
        }

        if (!$client) {
            $this->command->info('Nenhum usuário encontrado para associar pessoas.');
            return;
        }

        // Obter estados e cidades
        $state = State::first();
        $state_code = $state->code;
        $city = City::where('state_code', $state->code)->first();

        // Obter categorias
        $category = Category::whereNull('parent_id')->first();
        $subcategory = Category::whereNotNull('parent_id')->first();

        $people = [
            [
                'client_id' => $client->id,
                'type' => 'customer',
                'cpf' => '12345678901',
                'first_name' => 'João',
                'last_name' => 'Silva',
                'rg' => '1234567',
                'street' => 'Rua das Flores',
                'number' => '123',
                'neighborhood' => 'Centro',
                'zip_code' => '12345678',
                'state_id' => $state->id,
                'city_id' => $city->id,
                'birthdate' => '1985-05-15',
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'email' => 'joao.silva@example.com',
                'phone' => '11987654321',
                'credit_limit' => 10000.00,
                'used_credit' => 2500.00,
                'activated' => true,
                'situation' => 'active',
                'status' => true,
                'notes' => 'Cliente preferencial',
                'created_by' => $client->id,
                'updated_by' => $client->id,
            ],
            [
                'client_id' => $client->id,
                'type' => 'supplier',
                'cpf' => '98765432109',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'rg' => '7654321',
                'street' => 'Avenida Brasil',
                'number' => '456',
                'neighborhood' => 'Jardins',
                'zip_code' => '87654321',
                'state_id' => $state->id,
                'city_id' => $city->id,
                'birthdate' => '1990-08-22',
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'email' => 'maria.santos@example.com',
                'phone' => '11912345678',
                'credit_limit' => 5000.00,
                'used_credit' => 1000.00,
                'activated' => true,
                'situation' => 'active',
                'status' => true,
                'notes' => 'Fornecedor confiável',
                'created_by' => $client->id,
                'updated_by' => $client->id,
            ],
            [
                'client_id' => $client->id,
                'type' => 'employee',
                'cpf' => '45678912345',
                'first_name' => 'Carlos',
                'last_name' => 'Oliveira',
                'rg' => '3216547',
                'street' => 'Rua das Palmeiras',
                'number' => '789',
                'neighborhood' => 'Vila Nova',
                'zip_code' => '54321678',
                'state_id' => $state->id,
                'city_id' => $city->id,
                'birthdate' => '1995-03-10',
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'email' => 'carlos.oliveira@example.com',
                'phone' => '11955554444',
                'credit_limit' => 0.00,
                'used_credit' => 0.00,
                'activated' => true,
                'situation' => 'active',
                'status' => true,
                'notes' => 'Funcionário',
                'created_by' => $client->id,
                'updated_by' => $client->id,
            ],
            [
                'client_id' => $client->id,
                'type' => 'customer',
                'cpf' => '65432198765',
                'first_name' => 'Ana',
                'last_name' => 'Costa',
                'rg' => '9871234',
                'street' => 'Rua dos Pinheiros',
                'number' => '321',
                'neighborhood' => 'Alphaville',
                'zip_code' => '98765432',
                'state_id' => $state->id,
                'city_id' => $city->id,
                'birthdate' => '1988-11-30',
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'email' => 'ana.costa@example.com',
                'phone' => '11933332222',
                'credit_limit' => 15000.00,
                'used_credit' => 7500.00,
                'activated' => true,
                'situation' => 'inactive',
                'status' => false,
                'notes' => 'Cliente inativo temporariamente',
                'created_by' => $client->id,
                'updated_by' => $client->id,
            ],
            [
                'client_id' => $client->id,
                'type' => 'supplier',
                'cpf' => '32165498700',
                'first_name' => 'Roberto',
                'last_name' => 'Ferreira',
                'rg' => '6547891',
                'street' => 'Alameda Santos',
                'number' => '1000',
                'neighborhood' => 'Paulista',
                'zip_code' => '01418100',
                'state_id' => $state->id,
                'city_id' => $city->id,
                'birthdate' => '1975-07-04',
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'email' => 'roberto.ferreira@example.com',
                'phone' => '11988887777',
                'credit_limit' => 20000.00,
                'used_credit' => 5000.00,
                'activated' => true,
                'situation' => 'archived',
                'status' => true,
                'archived' => true,
                'archived_at' => now()->subDays(30),
                'archived_by' => $client->id,
                'notes' => 'Fornecedor arquivado',
                'created_by' => $client->id,
                'updated_by' => $client->id,
            ],
        ];

        foreach ($people as $personData) {
            Person::create($personData);
        }

        $this->command->info(count($people) . ' pessoas criadas com sucesso.');
    }
}