<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'code' => '001',
                'ispb' => '00000000',
                'name' => 'Banco do Brasil S.A.',
                'short_name' => 'BCO DO BRASIL S.A.',
                'compe_code' => '001',
                'document_number' => '00.000.000/0001-91',
                'type' => Bank::TYPE_COMMERCIAL,
                'is_public' => true,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '033',
                'ispb' => '90400888',
                'name' => 'Banco Santander (Brasil) S.A.',
                'short_name' => 'BCO SANTANDER (BRASIL) S.A.',
                'compe_code' => '033',
                'document_number' => '90.400.888/0001-42',
                'type' => Bank::TYPE_COMMERCIAL,
                'is_public' => false,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '104',
                'ispb' => '00360305',
                'name' => 'Caixa Econômica Federal',
                'short_name' => 'CAIXA ECONOMICA FEDERAL',
                'compe_code' => '104',
                'document_number' => '36.030.574/0001-94',
                'type' => Bank::TYPE_SAVINGS,
                'is_public' => true,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '237',
                'ispb' => '60746948',
                'name' => 'Banco Bradesco S.A.',
                'short_name' => 'BCO BRADESCO S.A.',
                'compe_code' => '237',
                'document_number' => '60.746.948/0001-12',
                'type' => Bank::TYPE_COMMERCIAL,
                'is_public' => false,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '341',
                'ispb' => '60701190',
                'name' => 'Itaú Unibanco S.A.',
                'short_name' => 'ITAÚ UNIBANCO S.A.',
                'compe_code' => '341',
                'document_number' => '60.701.190/0001-04',
                'type' => Bank::TYPE_COMMERCIAL,
                'is_public' => false,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '745',
                'ispb' => '33479023',
                'name' => 'Banco Citibank S.A.',
                'short_name' => 'BCO CITIBANK S.A.',
                'compe_code' => '745',
                'document_number' => '33.479.023/0001-80',
                'type' => Bank::TYPE_COMMERCIAL,
                'is_public' => false,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '260',
                'ispb' => '18236120',
                'name' => 'Nu Pagamentos S.A.',
                'short_name' => 'NUBANK',
                'compe_code' => '260',
                'document_number' => '18.236.120/0001-58',
                'type' => Bank::TYPE_PAYMENT,
                'is_public' => false,
                'participates_on_pix' => true,
                'active' => true,
            ],
            [
                'code' => '077',
                'ispb' => '00416968',
                'name' => 'Banco Inter S.A.',
                'short_name' => 'BANCO INTER',
                'compe_code' => '077',
                'document_number' => '00.416.968/0001-01',
                'type' => Bank::TYPE_COMMERCIAL,
                'is_public' => false,
                'participates_on_pix' => true,
                'active' => true,
            ],
        ];

        foreach ($banks as $bankData) {
            Bank::updateOrCreate(
                ['code' => $bankData['code']],
                $bankData
            );
        }
    }
}