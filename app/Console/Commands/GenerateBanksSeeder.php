<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class GenerateBanksSeeder extends Command
{
    protected $signature = 'banks:generate-seeder';
    protected $description = 'Gera o seeder de bancos usando a lista oficial do Banco Central';

    public function handle()
    {
        $this->info('Buscando lista oficial do Banco Central...');

        // URL pública do STR participantes (CSV/XML) — substituir pela real
        $url = 'https://www.bcb.gov.br/conteudo/dadosabertos/BCBDeban/ParticipantesSTR.csv';

        $response = Http::get($url);

        if (!$response->ok()) {
            $this->error('Falha ao baixar dados oficiais.');
            return;
        }

        $csv = $response->body();
        $lines = explode("\n", trim($csv));
        $header = str_getcsv(array_shift($lines));

        $banks = [];

        foreach ($lines as $line) {
            $row = array_combine($header, str_getcsv($line));

            $banks[] = [
                'code' => $row['Numero-Codigo'] ?? null,
                'ispb' => $row['ISPB'] ?? null,
                'name' => $row['Nome_Extenso'] ?? $row['Nome_Reduzido'] ?? null,
                'short_name' => $row['Nome_Reduzido'] ?? null,
                'active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        $seederPath = database_path('seeders/GeneratedBanksSeeder.php');

        $seederContent = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneratedBanksSeeder extends Seeder
{
    public function run(): void
    {
        \$banks = [
PHP;

        foreach ($banks as $b) {
            $name = addslashes($b['name']);
            $ispb = addslashes($b['ispb']);
            $code = addslashes($b['code']);

            $seederContent .= "\n            [
                'name' => '{$name}',
                'ispb' => '{$ispb}',
                'code' => '{$code}',
                'short_name' => '{$b['short_name']}',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],";
        }

        $seederContent .= "\n        ];\n\n        DB::table('banks')->insert(\$banks);\n    }\n}\n";

        File::put($seederPath, $seederContent);

        $this->info('Seeder gerado em: ' . $seederPath);
    }
}
