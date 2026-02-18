<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('banks')->delete();

        $banks = [
            [
                'code' => '001',
                'ispb' => '00000000',
                'name' => 'Banco do Brasil S.A.',
                'short_name' => 'BCO DO BRASIL S.A.',
                'compe_code' => '001',
            ],
            [
                'code' => '070',
                'ispb' => '00000208',
                'name' => 'BRB - BANCO DE BRASILIA S.A.',
                'short_name' => 'BRB - BCO DE BRASILIA S.A.',
                'compe_code' => '070',
            ],
            [
                'code' => '104',
                'ispb' => '00360305',
                'name' => 'CAIXA ECONOMICA FEDERAL',
                'short_name' => 'CAIXA ECONOMICA FEDERAL',
                'compe_code' => '104',
            ],
            [
                'code' => '341',
                'ispb' => '60701190',
                'name' => 'ITAÚ UNIBANCO S.A.',
                'short_name' => 'ITAÚ UNIBANCO S.A.',
                'compe_code' => '341',
            ],
            [
                'code' => '237',
                'ispb' => '60746948',
                'name' => 'Banco Bradesco S.A.',
                'short_name' => 'BCO BRADESCO S.A.',
                'compe_code' => '237',
            ],
            [
                'code' => '033',
                'ispb' => '90400888',
                'name' => 'Banco Santander (Brasil) S.A.',
                'short_name' => 'SANTANDER BRASIL',
                'compe_code' => '033',
            ],
            [
                'code' => '260',
                'ispb' => '18236120',
                'name' => 'NU PAGAMENTOS S.A. - INSTITUIÇÃO DE PAGAMENTO',
                'short_name' => 'NU PAGAMENTOS - IP',
                'compe_code' => '260',
            ],
            [
                'code' => '077',
                'ispb' => '00416968',
                'name' => 'Banco Inter S.A.',
                'short_name' => 'BANCO INTER',
                'compe_code' => '077',
            ],
            [
                'code' => '756',
                'ispb' => '02038232',
                'name' => 'BANCO COOPERATIVO SICOOB S.A.',
                'short_name' => 'BANCO SICOOB S.A.',
                'compe_code' => '756',
            ],
            [
                'code' => '748',
                'ispb' => '01181521',
                'name' => 'BANCO COOPERATIVO SICREDI S.A.',
                'short_name' => 'BCO COOPERATIVO SICREDI S.A.',
                'compe_code' => '748',
            ],
            [
                'code' => '208',
                'ispb' => '30306294',
                'name' => 'Banco BTG Pactual S.A.',
                'short_name' => 'BANCO BTG PACTUAL S.A.',
                'compe_code' => '208',
            ],
            [
                'code' => '422',
                'ispb' => '58160789',
                'name' => 'Banco Safra S.A.',
                'short_name' => 'BCO SAFRA S.A.',
                'compe_code' => '422',
            ],
        ];

        foreach ($banks as &$bank) {
            $bank['type'] = 'commercial';
            $bank['is_public'] = false;
            $bank['is_foreign'] = false;
            $bank['active'] = true;
            $bank['participates_on_pix'] = true;
            $bank['created_at'] = now();
            $bank['updated_at'] = now();
        }

        DB::table('banks')->insert($banks);
    }
}
