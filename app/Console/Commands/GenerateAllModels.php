<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateAllModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:all-models 
                            {--models= : Lista de modelos separados por vírgula}
                            {--force : Sobrescrever arquivos existentes}
                            {--skip-existing : Pular modelos que já existem}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera estrutura completa para todos os modelos faltantes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $models = $this->option('models') 
            ? explode(',', $this->option('models'))
            : $this->getMissingModels();
        
        if (empty($models)) {
            $this->info("✅ Todos os modelos já possuem estrutura completa!");
            return;
        }
        
        $this->info("🚀 [ASH Project] Gerando " . count($models) . " modelos faltantes");
        $this->newLine();
        
        $successCount = 0;
        $skipCount = 0;
        
        foreach ($models as $model) {
            $model = trim($model);
            $this->line("📦 Processando: {$model}");
            
            // Verificar se o modelo já existe e skip-existing está ativo
            if ($this->option('skip-existing') && $this->modelExists($model)) {
                $this->line("   ⏭️  Pulando (já existe)");
                $skipCount++;
                continue;
            }
            
            try {
                $this->call('make:model-structure', [
                    'model' => $model,
                    '--api' => true,
                    '--force' => $this->option('force'),
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $this->error("   ❌ Erro ao gerar {$model}: " . $e->getMessage());
            }
            
            $this->newLine();
        }
        
        $this->newLine();
        $this->info("📊 Resumo:");
        $this->line("   ✅ Gerados com sucesso: {$successCount}");
        $this->line("   ⏭️  Pulados: {$skipCount}");
        $this->line("   📦 Total processados: " . count($models));
        
        if ($successCount > 0) {
            $this->newLine();
            $this->line('🚀 Próximos passos:');
            $this->line('   1. php artisan migrate');
            $this->line('   2. php artisan db:seed');
            $this->line('   3. Testar endpoints da API');
        }
    }
    
    /**
     * Obter lista de modelos faltantes baseado no projeto ASH
     */
    private function getMissingModels(): array
    {
        // Modelos que já estão completos no seu projeto
        $completeModels = ['Category', 'Company', 'FinancialTransaction', 'User'];
        
        // Todos os modelos do projeto ASH
        $allModels = [
            'Bank', 'Category', 'City', 'Company', 'Country', 
            'FinancialTransaction', 'PaymentMethod', 'Person', 
            'Role', 'State', 'Status', 'Type', 'User'
        ];
        
        // Filtrar modelos faltantes
        $missingModels = [];
        
        foreach ($allModels as $model) {
            // Verificar se tem estrutura completa
            if (!in_array($model, $completeModels)) {
                $missingModels[] = $model;
            }
        }
        
        return $missingModels;
    }
    
    /**
     * Verificar se o modelo já existe
     */
    private function modelExists(string $modelName): bool
    {
        $controllerPath = app_path("Http/Controllers/Api/V1/{$modelName}Controller.php");
        $resourcePath = app_path("Http/Resources/Api/V1/{$modelName}Resource.php");
        $collectionPath = app_path("Http/Resources/Api/V1/{$modelName}Collection.php");
        
        return File::exists($controllerPath) && 
               File::exists($resourcePath) && 
               File::exists($collectionPath);
    }
}