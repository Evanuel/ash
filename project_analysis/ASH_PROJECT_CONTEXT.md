# 🚀 PROJETO ASH - CONTEXTO COMPLETO

## 📋 METADADOS DO PROJETO
- **Nome do Projeto**: ash
- **Data da Análise**: 2026-01-18T16:28:14.684331
- **Localização**: `C:\Users\Evanuel\repositories\ash`
- **Scanner**: v2.0.0

## 📊 ESTATÍSTICAS
- **Total de Arquivos**: 195
- **Total de Diretórios**: 59
- **Tamanho Total**: 2.80 MB
- **Arquivos PHP**: 66
- **Views Blade**: 0
- **Arquivos JavaScript**: 6

## 🗃️ ESTRUTURA DO PROJETO

### 📦 Models
- **FixModelStructure** → `app\Console\Commands\FixModelStructure.php`
  - *Namespace*: `App\Console\Commands`
  - *Extends*: `Command`
- **GenerateAllModels** → `app\Console\Commands\GenerateAllModels.php`
  - *Namespace*: `App\Console\Commands`
  - *Extends*: `Command`
- **GenerateModelStructure** → `app\Console\Commands\GenerateModelStructure.php`
  - *Namespace*: `App\Console\Commands`
  - *Extends*: `Command`
- **Bank** → `app\Models\Bank.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Category** → `app\Models\Category.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **City** → `app\Models\City.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Company** → `app\Models\Company.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Country** → `app\Models\Country.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **FinancialTransaction** → `app\Models\FinancialTransaction.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **PaymentMethod** → `app\Models\PaymentMethod.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Person** → `app\Models\Person.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Role** → `app\Models\Role.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **State** → `app\Models\State.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Status** → `app\Models\Status.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **Type** → `app\Models\Type.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Model`
- **User** → `app\Models\User.php`
  - *Namespace*: `App\Models`
  - *Extends*: `Authenticatable`

### 🎮 Controllers
- **AuthController** → `app\Http\Controllers\Api\V1\AuthController.php`
- **BankController** → `app\Http\Controllers\Api\V1\BankController.php`
- **BaseController** → `app\Http\Controllers\Api\V1\BaseController.php`
- **CategoryController** → `app\Http\Controllers\Api\V1\CategoryController.php`
- **CityController** → `app\Http\Controllers\Api\V1\CityController.php`
- **CompanyController** → `app\Http\Controllers\Api\V1\CompanyController.php`
- **CountryController** → `app\Http\Controllers\Api\V1\CountryController.php`
- **FinancialTransactionController** → `app\Http\Controllers\Api\V1\FinancialTransactionController.php`
- **PaymentMethodController** → `app\Http\Controllers\Api\V1\PaymentMethodController.php`
- **PeopleController** → `app\Http\Controllers\Api\V1\PeopleController.php`
- **PersonController** → `app\Http\Controllers\Api\V1\PersonController.php`
- **RoleController** → `app\Http\Controllers\Api\V1\RoleController.php`
- **StateController** → `app\Http\Controllers\Api\V1\StateController.php`
- **StatusController** → `app\Http\Controllers\Api\V1\StatusController.php`
- **TypeController** → `app\Http\Controllers\Api\V1\TypeController.php`
- **UserController** → `app\Http\Controllers\Api\V1\UserController.php`
- **Controller** → `app\Http\Controllers\Controller.php`

### ⚙️ Services
- **AuthServiceProvider** → `app\Providers\AppServiceProvider.php`
- **AuthService** → `app\Services\AuthService.php`
- **Unknown** → `app\Services\FinancialTransactionService.php`
- **LogService** → `app\Services\LogService.php`
- **PermissionService** → `app\Services\PermissionService.php`
- **Unknown** → `app\Services\UserService.php`
- **Unknown** → `bootstrap\cache\services.php`
- **Unknown** → `config\services.php`

### 📝 Requests
- `app\Http\Requests\Api\V1\Auth\LoginRequest.php`
- `app\Http\Requests\Api\V1\Auth\RegisterRequest.php`
- `app\Http\Requests\Api\V1\Category\CategoryRequest.php`
- `app\Http\Requests\Api\V1\Company\StoreCompanyRequest-20260118154218.php`
- `app\Http\Requests\Api\V1\Company\StoreCompanyRequest.php`
- `app\Http\Requests\Api\V1\Company\UpdateCompanyRequest-20260118154622.php`
- `app\Http\Requests\Api\V1\Company\UpdateCompanyRequest.php`
- `app\Http\Requests\Api\V1\FinancialTransaction\StoreFinancialTransactionRequest.php`
- `app\Http\Requests\Api\V1\FinancialTransaction\StoreFinancialTransactionRequest2.php`
- `app\Http\Requests\Api\V1\FinancialTransaction\UpdateFinancialTransactionRequest.php`
  *... e mais 2 itens*

### 🚀 Jobs
- `database\migrations\0001_01_01_000002_create_jobs_table.php`

### 🏗️ Providers
- `bootstrap\providers.php`

### 🌱 Seeders
- `database\seeders\BankSeeder.php`
- `database\seeders\CategorySeeder.php`
- `database\seeders\CitySeeder.php`
- `database\seeders\CompanySeeder.php`
- `database\seeders\DatabaseSeeder.php`
- `database\seeders\PaymentMethodSeeder.php`
- `database\seeders\RoleSeeder.php`
- `database\seeders\StateSeeder.php`
- `database\seeders\StatusesSeeder.php`
- `database\seeders\UserSeeder.php`

### 🏭 Factories
- `database\factories\UserFactory.php`

## 📦 DEPENDÊNCIAS
### Laravel & Framework
- `laravel/framework`: ^12.0
- `laravel/sanctum`: ^4.2
- `laravel/tinker`: ^2.10.1

### Outras Dependências
- `php`: ^8.2

## 🕐 TRABALHO RECENTE
Arquivos modificados nos últimos 7 dias:
- `app\Console\Commands\FixModelStructure.php` (0 dias atrás)
- `app\Console\Commands\GenerateAllModels.php` (0 dias atrás)
- `app\Console\Commands\GenerateModelStructure.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\BankController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\BaseController.php` (5 dias atrás)
- `app\Http\Controllers\Api\V1\CityController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\CompanyController.php` (5 dias atrás)
- `app\Http\Controllers\Api\V1\CountryController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\FinancialTransactionController.php` (4 dias atrás)
- `app\Http\Controllers\Api\V1\PaymentMethodController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\PersonController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\StateController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\StatusController.php` (0 dias atrás)
- `app\Http\Controllers\Api\V1\TypeController.php` (0 dias atrás)
- `app\Http\Requests\Api\V1\Category\CategoryRequest.php` (7 dias atrás)
- `app\Http\Requests\Api\V1\Company\StoreCompanyRequest-20260118154218.php` (5 dias atrás)
- `app\Http\Requests\Api\V1\Company\StoreCompanyRequest.php` (0 dias atrás)
- `app\Http\Requests\Api\V1\Company\UpdateCompanyRequest-20260118154622.php` (6 dias atrás)
- `app\Http\Requests\Api\V1\Company\UpdateCompanyRequest.php` (0 dias atrás)
- `app\Http\Requests\Api\V1\FinancialTransaction\create.bash` (4 dias atrás)

## 🎯 USO COM ASSISTENTES DE IA

            Quando solicitar ajuda sobre este projeto, inclua:
            CONTEXTO DO PROJETO ASH:

            Projeto: ash

            Total de arquivos: 195

            Principais modelos: FixModelStructure, GenerateAllModels, GenerateModelStructure

            Controllers: 17

            Framework: Laravel

            OBJETIVO ATUAL: [Descreva o que está tentando fazer]
            ARQUIVOS ENVOLVIDOS: [Mencione arquivos específicos se aplicável]
            ---

            *Documento gerado automaticamente em 2026-01-18T16:28:14.859548*
            