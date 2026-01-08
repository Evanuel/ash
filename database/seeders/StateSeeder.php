<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['code' => 21, 'name' => 'Maranhão', 'uf' => 'MA', 'created_by' => 1],
            ['code' => 27, 'name' => 'Alagoas', 'uf' => 'AL', 'created_by' => 1],
            ['code' => 16, 'name' => 'Amapá', 'uf' => 'AP', 'created_by' => 1],
            ['code' => 13, 'name' => 'Amazonas', 'uf' => 'AM', 'created_by' => 1],
            ['code' => 29, 'name' => 'Bahia', 'uf' => 'BA', 'created_by' => 1],
            ['code' => 23, 'name' => 'Ceará', 'uf' => 'CE', 'created_by' => 1],
            ['code' => 53, 'name' => 'Distrito Federal', 'uf' => 'DF', 'created_by' => 1],
            ['code' => 32, 'name' => 'Espírito Santo', 'uf' => 'ES', 'created_by' => 1],
            ['code' => 52, 'name' => 'Goiás', 'uf' => 'GO', 'created_by' => 1],
            ['code' => 12, 'name' => 'Acre', 'uf' => 'AC', 'created_by' => 1],
            ['code' => 51, 'name' => 'Mato Grosso', 'uf' => 'MT', 'created_by' => 1],
            ['code' => 50, 'name' => 'Mato Grosso do Sul', 'uf' => 'MS', 'created_by' => 1],
            ['code' => 31, 'name' => 'Minas Gerais', 'uf' => 'MG', 'created_by' => 1],
            ['code' => 15, 'name' => 'Pará', 'uf' => 'PA', 'created_by' => 1],
            ['code' => 25, 'name' => 'Paraíba', 'uf' => 'PB', 'created_by' => 1],
            ['code' => 41, 'name' => 'Paraná', 'uf' => 'PR', 'created_by' => 1],
            ['code' => 26, 'name' => 'Pernambuco', 'uf' => 'PE', 'created_by' => 1],
            ['code' => 22, 'name' => 'Piauí', 'uf' => 'PI', 'created_by' => 1],
            ['code' => 33, 'name' => 'Rio de Janeiro', 'uf' => 'RJ', 'created_by' => 1],
            ['code' => 24, 'name' => 'Rio Grande do Norte', 'uf' => 'RN', 'created_by' => 1],
            ['code' => 43, 'name' => 'Rio Grande do Sul', 'uf' => 'RS', 'created_by' => 1],
            ['code' => 11, 'name' => 'Rondônia', 'uf' => 'RO', 'created_by' => 1],
            ['code' => 14, 'name' => 'Roraima', 'uf' => 'RR', 'created_by' => 1],
            ['code' => 42, 'name' => 'Santa Catarina', 'uf' => 'SC', 'created_by' => 1],
            ['code' => 35, 'name' => 'São Paulo', 'uf' => 'SP', 'created_by' => 1],
            ['code' => 28, 'name' => 'Sergipe', 'uf' => 'SE', 'created_by' => 1],
            ['code' => 17, 'name' => 'Tocantins', 'uf' => 'TO', 'created_by' => 1],
        ];

        foreach ($estados as $estado) {
            State::create($estado);
        }
    }
}
