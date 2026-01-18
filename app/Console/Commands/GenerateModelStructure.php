<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class GenerateModelStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-structure 
                            {model : Nome do modelo (singular)}
                            {--all : Gerar todos os arquivos}
                            {--controller : Gerar controller}
                            {--request : Gerar requests (store/update)}
                            {--resource : Gerar resource}
                            {--collection : Gerar resource collection}
                            {--migration : Gerar migration}
                            {--seed : Gerar seeder}
                            {--factory : Gerar factory}
                            {--policy : Gerar policy}
                            {--force : Sobrescrever arquivos existentes}
                            {--no-routes : Não adicionar rotas}
                            {--api : Gerar controller API apenas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera estrutura completa para um modelo Laravel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('model');
        $modelName = Str::studly($modelName);
        $tableName = Str::snake(Str::plural($modelName));
        
        $this->info("📦 [Laravel 12] Gerando estrutura para: {$modelName}");
        $this->line("📊 Nome da tabela: {$tableName}");
        $this->newLine();

        $options = $this->options();
        
        // Se --all foi especificado, gerar tudo
        if ($options['all']) {
            $this->generateAll($modelName, $tableName);
        } else {
            $this->generateSelected($modelName, $tableName, $options);
        }

        $this->newLine();
        $this->info('✅ Estrutura gerada com sucesso!');
        $this->showSummary($modelName);
    }

    /**
     * Gerar todos os arquivos
     */
    private function generateAll(string $modelName, string $tableName): void
    {
        $this->generateModel($modelName);
        $this->generateMigration($modelName, $tableName);
        $this->generateController($modelName);
        $this->generateRequests($modelName);
        $this->generateResource($modelName);
        $this->generateResourceCollection($modelName);
        $this->generateSeeder($modelName);
        $this->generateFactory($modelName);
        $this->generatePolicy($modelName);
        
        if (!$this->option('no-routes')) {
            $this->updateRoutes($modelName);
        }
    }

    /**
     * Gerar apenas os selecionados
     */
    private function generateSelected(string $modelName, string $tableName, array $options): void
    {
        if ($options['controller'] || $options['api']) {
            $this->generateController($modelName, $options['api']);
        }
        
        if ($options['request']) {
            $this->generateRequests($modelName);
        }
        
        if ($options['resource']) {
            $this->generateResource($modelName);
        }
        
        if ($options['collection']) {
            $this->generateResourceCollection($modelName);
        }
        
        if ($options['migration']) {
            $this->generateMigration($modelName, $tableName);
        }
        
        if ($options['seed']) {
            $this->generateSeeder($modelName);
        }
        
        if ($options['factory']) {
            $this->generateFactory($modelName);
        }
        
        if ($options['policy']) {
            $this->generatePolicy($modelName);
        }
        
        // Se nenhuma opção foi especificada, gerar o básico do API
        if (!$options['controller'] && !$options['request'] && !$options['resource'] && 
            !$options['collection'] && !$options['migration'] && !$options['seed'] && 
            !$options['factory'] && !$options['policy'] && !$options['api']) {
            $this->generateController($modelName, true);
            $this->generateRequests($modelName);
            $this->generateResource($modelName);
            $this->generateResourceCollection($modelName);
        }
    }

    /**
     * Gerar ou atualizar modelo
     */
    private function generateModel(string $modelName): void
    {
        $modelPath = app_path("Models/{$modelName}.php");
        
        if (!File::exists($modelPath) || $this->option('force')) {
            // Usar o comando Artisan padrão
            Artisan::call('make:model', [
                'name' => $modelName,
                '--force' => $this->option('force'),
            ]);
            
            $this->line("✅ Modelo: app/Models/{$modelName}.php");
        } else {
            $this->line("⏭️  Modelo já existe: app/Models/{$modelName}.php");
        }
    }

    /**
     * Gerar migration
     */
    private function generateMigration(string $modelName, string $tableName): void
    {
        Artisan::call('make:migration', [
            'name' => "create_{$tableName}_table",
            '--create' => $tableName,
        ]);
        
        $this->line("✅ Migration: database/migrations/*_create_{$tableName}_table.php");
    }

    /**
     * Gerar controller
     */
    private function generateController(string $modelName, bool $apiOnly = true): void
    {
        $controllerName = "{$modelName}Controller";
        $controllerPath = app_path("Http/Controllers/Api/V1/{$controllerName}.php");
        
        // Verificar se já existe no padrão do seu projeto
        if (File::exists($controllerPath) && !$this->option('force')) {
            $this->line("⏭️  Controller já existe: app/Http/Controllers/Api/V1/{$controllerName}.php");
            return;
        }
        
        // Criar controller baseado no padrão do projeto ASH
        $stub = $this->getControllerStub();
        $content = str_replace(
            [
                '{{modelName}}',
                '{{modelVariable}}',
                '{{resourceName}}',
                '{{collectionName}}',
                '{{storeRequest}}',
                '{{updateRequest}}'
            ],
            [
                $modelName,
                Str::camel($modelName),
                "{$modelName}Resource",
                "{$modelName}Collection",
                "Store{$modelName}Request",
                "Update{$modelName}Request"
            ],
            $stub
        );
        
        // Garantir que o diretório existe
        File::ensureDirectoryExists(dirname($controllerPath));
        File::put($controllerPath, $content);
        
        $this->line("✅ Controller API: app/Http/Controllers/Api/V1/{$controllerName}.php");
    }

    /**
     * Gerar requests
     */
    private function generateRequests(string $modelName): void
    {
        $requestDir = app_path("Http/Requests/Api/V1/{$modelName}");
        
        // Criar diretório se não existir
        File::ensureDirectoryExists($requestDir);
        
        // Store Request
        $storeRequestPath = "{$requestDir}/Store{$modelName}Request.php";
        if (!File::exists($storeRequestPath) || $this->option('force')) {
            $stub = $this->getStoreRequestStub();
            $content = str_replace(
                ['{{modelName}}', '{{modelVariable}}'],
                [$modelName, Str::camel($modelName)],
                $stub
            );
            
            File::put($storeRequestPath, $content);
            $this->line("✅ Store Request: app/Http/Requests/Api/V1/{$modelName}/Store{$modelName}Request.php");
        }
        
        // Update Request
        $updateRequestPath = "{$requestDir}/Update{$modelName}Request.php";
        if (!File::exists($updateRequestPath) || $this->option('force')) {
            $stub = $this->getUpdateRequestStub();
            $content = str_replace(
                ['{{modelName}}', '{{modelVariable}}'],
                [$modelName, Str::camel($modelName)],
                $stub
            );
            
            File::put($updateRequestPath, $content);
            $this->line("✅ Update Request: app/Http/Requests/Api/V1/{$modelName}/Update{$modelName}Request.php");
        }
    }

    /**
     * Gerar resource
     */
    private function generateResource(string $modelName): void
    {
        $resourcePath = app_path("Http/Resources/Api/V1/{$modelName}Resource.php");
        
        if (!File::exists($resourcePath) || $this->option('force')) {
            $stub = $this->getResourceStub();
            $content = str_replace(
                ['{{modelName}}', '{{modelVariable}}'],
                [$modelName, Str::camel($modelName)],
                $stub
            );
            
            File::ensureDirectoryExists(dirname($resourcePath));
            File::put($resourcePath, $content);
            
            $this->line("✅ Resource: app/Http/Resources/Api/V1/{$modelName}Resource.php");
        }
    }

    /**
     * Gerar resource collection
     */
    private function generateResourceCollection(string $modelName): void
    {
        $collectionPath = app_path("Http/Resources/Api/V1/{$modelName}Collection.php");
        
        if (!File::exists($collectionPath) || $this->option('force')) {
            $stub = $this->getResourceCollectionStub();
            $content = str_replace(
                ['{{modelName}}', '{{modelVariable}}'],
                [$modelName, Str::camel($modelName)],
                $stub
            );
            
            File::ensureDirectoryExists(dirname($collectionPath));
            File::put($collectionPath, $content);
            
            $this->line("✅ Resource Collection: app/Http/Resources/Api/V1/{$modelName}Collection.php");
        }
    }

    /**
     * Gerar seeder
     */
    private function generateSeeder(string $modelName): void
    {
        Artisan::call('make:seeder', [
            'name' => "{$modelName}Seeder",
        ]);
        
        $this->line("✅ Seeder: database/seeders/{$modelName}Seeder.php");
    }

    /**
     * Gerar factory
     */
    private function generateFactory(string $modelName): void
    {
        Artisan::call('make:factory', [
            'name' => "{$modelName}Factory",
            '--model' => $modelName,
        ]);
        
        $this->line("✅ Factory: database/factories/{$modelName}Factory.php");
    }

    /**
     * Gerar policy
     */
    private function generatePolicy(string $modelName): void
    {
        Artisan::call('make:policy', [
            'name' => "{$modelName}Policy",
            '--model' => $modelName,
        ]);
        
        $this->line("✅ Policy: app/Policies/{$modelName}Policy.php");
    }

    /**
     * Atualizar rotas
     */
    private function updateRoutes(string $modelName): void
    {
        $routeName = Str::kebab(Str::plural($modelName));
        $controller = "{$modelName}Controller";
        
        $routesFile = base_path('routes/api.php');
        
        if (!File::exists($routesFile)) {
            $this->warn("⚠️  Arquivo de rotas API não encontrado: routes/api.php");
            return;
        }
        
        $routeDefinition = <<<PHP

    // {$modelName} Routes
    Route::apiResource('{$routeName}', \\App\\Http\\Controllers\\Api\\V1\\{$controller}::class);

PHP;

        $content = File::get($routesFile);
        
        // Verificar se a rota já existe
        if (str_contains($content, "Route::apiResource('{$routeName}'")) {
            $this->line("⏭️  Rota já existe: api/{$routeName}");
            return;
        }
        
        // Adicionar após o último Route:: prefix
        if (str_contains($content, "Route::prefix(")) {
            $lines = explode("\n", $content);
            $newLines = [];
            $added = false;
            
            foreach ($lines as $line) {
                $newLines[] = $line;
                
                // Adicionar após o fechamento do último grupo
                if (trim($line) === '});' && !$added) {
                    $newLines[] = $routeDefinition;
                    $added = true;
                }
            }
            
            File::put($routesFile, implode("\n", $newLines));
            $this->line("✅ Rota adicionada: api/{$routeName}");
        }
    }

    /**
     * Mostrar resumo
     */
    private function showSummary(string $modelName): void
    {
        $this->line('📋 Arquivos gerados:');
        $this->line("   📄 Model: app/Models/{$modelName}.php");
        $this->line("   🎮 Controller: app/Http/Controllers/Api/V1/{$modelName}Controller.php");
        $this->line("   📝 Store Request: app/Http/Requests/Api/V1/{$modelName}/Store{$modelName}Request.php");
        $this->line("   📝 Update Request: app/Http/Requests/Api/V1/{$modelName}/Update{$modelName}Request.php");
        $this->line("   📦 Resource: app/Http/Resources/Api/V1/{$modelName}Resource.php");
        $this->line("   📚 Resource Collection: app/Http/Resources/Api/V1/{$modelName}Collection.php");
        $this->line("   🛣️  Rota: api/" . Str::kebab(Str::plural($modelName)));
        $this->newLine();
        $this->line('🚀 Próximos passos:');
        $this->line('   1. php artisan migrate (para criar as tabelas)');
        $this->line('   2. php artisan db:seed (para popular dados)');
        $this->line('   3. Ajustar regras de validação nos Requests');
        $this->line('   4. Definir campos no Resource');
    }

    /**
     * Obter stub do controller (baseado no padrão ASH)
     */
    private function getControllerStub(): string
    {
        return <<<'EOT'
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\{{modelName}}\Store{{modelName}}Request;
use App\Http\Requests\Api\V1\{{modelName}}\Update{{modelName}}Request;
use App\Http\Resources\Api\V1\{{resourceName}};
use App\Http\Resources\Api\V1\{{collectionName}};
use App\Models\{{modelName}};
use Illuminate\Http\Request;

class {{modelName}}Controller extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new {{modelName}};
        $this->resource = {{resourceName}}::class;
        $this->collection = {{collectionName}}::class;
        $this->relations = [];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return parent::index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store{{modelName}}Request $request)
    {
        $validated = $request->validated();
        ${{modelVariable}} = {{modelName}}::create($validated);
        
        return new {{resourceName}}(${{modelVariable}});
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return parent::show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update{{modelName}}Request $request, $id)
    {
        ${{modelVariable}} = {{modelName}}::findOrFail($id);
        $validated = $request->validated();
        ${{modelVariable}}->update($validated);
        
        return new {{resourceName}}(${{modelVariable}});
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}
EOT;
    }

    /**
     * Obter stub do store request
     */
    private function getStoreRequestStub(): string
    {
        return <<<'EOT'
<?php

namespace App\Http\Requests\Api\V1\{{modelName}};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Store{{modelName}}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Example rules:
            // 'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:{{modelVariable}}s',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            // 'name' => 'nome',
            // 'email' => 'e-mail',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            // 'name.required' => 'O campo nome é obrigatório.',
            // 'email.unique' => 'Este e-mail já está em uso.',
        ];
    }
}
EOT;
    }

    /**
     * Obter stub do update request
     */
    private function getUpdateRequestStub(): string
    {
        return <<<'EOT'
<?php

namespace App\Http\Requests\Api\V1\{{modelName}};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Update{{modelName}}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        ${{modelVariable}}Id = $this->route('{{modelVariable}}');
        
        return [
            // Example rules:
            // 'name' => 'sometimes|string|max:255',
            // 'email' => [
            //     'sometimes',
            //     'email',
            //     Rule::unique('{{modelVariable}}s')->ignore(${{modelVariable}}Id),
            // ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            // 'name' => 'nome',
            // 'email' => 'e-mail',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            // 'name.required' => 'O campo nome é obrigatório.',
            // 'email.unique' => 'Este e-mail já está em uso.',
        ];
    }
}
EOT;
    }

    /**
     * Obter stub do resource
     */
    private function getResourceStub(): string
    {
        return <<<'EOT'
<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {{modelName}}Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Add fields here:
            // 'name' => $this->name,
            // 'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
EOT;
    }

    /**
     * Obter stub do resource collection
     */
    private function getResourceCollectionStub(): string
    {
        return <<<'EOT'
<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class {{modelName}}Collection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
EOT;
    }
}