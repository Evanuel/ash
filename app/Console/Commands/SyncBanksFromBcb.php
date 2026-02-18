<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SyncBanksFromBcb extends Command
{
    protected $signature = 'banks:sync';
    protected $description = 'Sincroniza bancos diretamente do CSV oficial do Banco Central';

    public function handle()
    {
        $this->info('Baixando CSV oficial do Banco Central...');

        $url = 'https://www.bcb.gov.br/content/estabilidadefinanceira/str1/ParticipantesSTR.csv';

        $response = Http::timeout(60)->get($url);

        if (!$response->ok()) {
            $this->error('Erro ao baixar CSV.');
            return 1;
        }

        // Corrigir encoding (ISO-8859-1 → UTF-8)
        $csvContent = mb_convert_encoding($response->body(), 'UTF-8', 'ISO-8859-1');

        // Criar stream em memória
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csvContent);
        rewind($stream);

        // Ler cabeçalho corretamente usando ;
        $header = fgetcsv($stream, 0, ';');

        if (!$header) {
            $this->error('Não foi possível ler cabeçalho do CSV.');
            return 1;
        }

        $count = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($stream, 0, ';')) !== false) {

                $data = array_combine($header, $row);

                if (!$data || empty($data['ISPB'])) {
                    continue;
                }

                DB::table('banks')->updateOrInsert(
                    ['ispb' => trim($data['ISPB'])],
                    [
                        'code' => trim($data['Número Código'] ?? ''),
                        'name' => trim($data['Nome Extenso']),
                        'short_name' => trim($data['Nome Reduzido'] ?? ''),
                        'type' => 'commercial',
                        'is_public' => false,
                        'is_foreign' => false,
                        'active' => true,
                        'participates_on_pix' => true,
                        'updated_by' => 1,
                        'updated_at' => Carbon::now(),
                        'created_at' => Carbon::now(),
                    ]
                );

                $count++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            return 1;
        }

        fclose($stream);

        $this->info("✔ Sincronização concluída.");
        $this->info("Instituições processadas: {$count}");

        return 0;
    }
}
