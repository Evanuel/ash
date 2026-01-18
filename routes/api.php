<?php

/**
 * ROUTES FOR THE API
 */

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    AuthController,
    CategoryController,
    UserController,
    RoleController,
    CompanyController,
    PeopleController,
    FinancialTransactionController
};

Route::prefix('v1')->name('api.v1.')->group(function () {
    // Health check da API
        Route::post('/', function () {
            return response()->json([
                'status' => 'healthy Authenticated',
                'timestamp' => now()->toISOString(),
                'service' => config('app.name'),
                'version' => config('app.version'),
            ]);
        })->name('health-auth');

    // Rotas públicas (sem autenticação)
    Route::prefix('auth')->name('auth.')->group(function () {
        // Route::get('/login', function () {
        //     return response()->json(['message' => 'Use POST method to login.'], 405);
        // })->name('api.login.get'); // Rota temporária para evitar conflito de nomes
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register'])->name('register');
    });

    // Rotas protegidas com Sanctum
    Route::middleware('auth:sanctum')->group(function () {

        // Autenticação
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('/me', [AuthController::class, 'me'])->name('me');
        });

        // Usuários
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::patch('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        });

        // Roles
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{id}', [RoleController::class, 'show'])->name('show');
            Route::put('/{id}', [RoleController::class, 'update'])->name('update');
            Route::patch('/{id}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        });

        // Empresas
        Route::prefix('companies')->name('companies.')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('index');
            Route::post('/', [CompanyController::class, 'store'])->name('store');
            Route::get('/{id}', [CompanyController::class, 'show'])->name('show');
            Route::put('/{id}', [CompanyController::class, 'update'])->name('update');
            Route::patch('/{id}', [CompanyController::class, 'update'])->name('update');
            Route::delete('/{id}', [CompanyController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [CompanyController::class, 'restore'])->name('restore');
        });

        // Pessoas
        Route::prefix('people')->name('people.')->group(function () {
            Route::get('/', [PeopleController::class, 'index'])->name('index');
            Route::post('/', [PeopleController::class, 'store'])->name('store');
            Route::get('/{id}', [PeopleController::class, 'show'])->name('show');
            Route::put('/{id}', [PeopleController::class, 'update'])->name('update');
            Route::patch('/{id}', [PeopleController::class, 'update'])->name('update');
            Route::delete('/{id}', [PeopleController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [PeopleController::class, 'restore'])->name('restore');
        });

        // Transações Financeiras
        Route::prefix('financial-transactions')->name('financial-transactions.')->group(function () {
            Route::get('/', [FinancialTransactionController::class, 'index'])->name('index');
            Route::post('/', [FinancialTransactionController::class, 'store'])->name('store');
            Route::get('/{id}', [FinancialTransactionController::class, 'show'])->name('show');
            Route::put('/{id}', [FinancialTransactionController::class, 'update'])->name('update');
            Route::patch('/{id}', [FinancialTransactionController::class, 'update'])->name('update');
            Route::delete('/{id}', [FinancialTransactionController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/pay', [FinancialTransactionController::class, 'markAsPaid'])->name('mark-as-paid');
            Route::get('/{id}/receipt', [FinancialTransactionController::class, 'downloadReceipt'])->name('receipt');
            Route::post('/{id}/restore', [FinancialTransactionController::class, 'restore'])->name('restore');
            Route::get('/summary', [FinancialTransactionController::class, 'summary'])->name('summary');
        });

        // Categorias
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::patch('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
            Route::get('/tree/hierarchical', [CategoryController::class, 'tree'])->name('tree');
        });

        
    });

    // Health check da API
    Route::get('/', function () {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'service' => config('app.name'),
            'version' => config('app.version'),
        ]);
    })->name('health');
});
