<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixModelStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:models 
                            {--model= : Modelo específico para corrigir}
                            {--all : Corrigir todos os modelos}
                            {--dry-run : Mostrar apenas o que será feito}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige a estrutura dos modelos para seguir o padrão ASH';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->option('model');
        $all = $this->option('all');
        $dryRun = $this->option('dry-run');
        
        if ($model) {
            $this->fixSingleModel($model, $dryRun);
        } elseif ($all) {
            $this->fixAllModels($dryRun);
        } else {
            $this->info("Uso:");
            $this->line("  php artisan fix:models --model=Bank");
            $this->line("  php artisan fix:models --all");
            $this->line("  php artisan fix:models --all --dry-run");
        }
    }
    
    /**
     * Corrigir um modelo específico
     */
    private function fixSingleModel(string $modelName, bool $dryRun): void
    {
        $this->info("🔧 Corrigindo modelo: {$modelName}");
        
        $filesToCheck = [
            'controller' => "Http/Controllers/Api/V1/{$modelName}Controller.php",
            'resource' => "Http/Resources/Api/V1/{$modelName}Resource.php",
            'collection' => "Http/Resources/Api/V1/{$modelName}Collection.php",
        ];
        
        foreach ($filesToCheck as $type => $path) {
            $fullPath = app_path($path);
            
            if (!File::exists($fullPath)) {
                $this->warn("   ❌ {$type} não encontrado: {$path}");
                
                if (!$dryRun) {
                    $this->call('make:model-structure', [
                        'model' => $modelName,
                        "--{$type}" => true,
                    ]);
                }
            } else {
                $this->line("   ✅ {$type}: OK");
            }
        }
        
        if (!$dryRun) {
            $this->info("✅ Modelo {$modelName} corrigido!");
        }
    }
    
    /**
     * Corrigir todos os modelos
     */
    private function fixAllModels(bool $dryRun): void
    {
        $this->info("🔧 Verificando todos os modelos do projeto ASH...");
        
        $models = [
            'Bank', 'Category', 'City', 'Company', 'Country',
            'FinancialTransaction', 'PaymentMethod', 'Person',
            'Role', 'State', 'Status', 'Type', 'User'
        ];
        
        $issues = [];
        
        foreach ($models as $model) {
            $this->line("📋 Verificando: {$model}");
            
            $missing = $this->checkModelStructure($model);
            
            if (!empty($missing)) {
                $issues[$model] = $missing;
                $this->warn("   ❌ Faltando: " . implode(', ', $missing));
            } else {
                $this->line("   ✅ Completo");
            }
        }
        
        if (empty($issues)) {
            $this->info("🎉 Todos os modelos estão com estrutura completa!");
            return;
        }
        
        $this->newLine();
        $this->warn("⚠️  " . count($issues) . " modelos precisam de correção:");
        
        foreach ($issues as $model => $missing) {
            $this->line("   {$model}: " . implode(', ', $missing));
        }
        
        if (!$dryRun && $this->confirm('Deseja corrigir todos os modelos automaticamente?')) {
            foreach (array_keys($issues) as $model) {
                $this->newLine();
                $this->call('make:model-structure', [
                    'model' => $model,
                    '--api' => true,
                    '--force' => true,
                ]);
            }
            
            $this->info("✅ Todos os modelos foram corrigidos!");
        }
    }
    
    /**
     * Verificar estrutura do modelo
     */
    private function checkModelStructure(string $modelName): array
    {
        $missing = [];
        
        $files = [
            'controller' => "Http/Controllers/Api/V1/{$modelName}Controller.php",
            'resource' => "Http/Resources/Api/V1/{$modelName}Resource.php",
            'collection' => "Http/Resources/Api/V1/{$modelName}Collection.php",
            'store_request' => "Http/Requests/Api/V1/{$modelName}/Store{$modelName}Request.php",
            'update_request' => "Http/Requests/Api/V1/{$modelName}/Update{$modelName}Request.php",
        ];
        
        foreach ($files as $key => $path) {
            if (!File::exists(app_path($path))) {
                $missing[] = $key;
            }
        }
        
        return $missing;
    }
}