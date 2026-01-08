<?php

/**
 * ROUTES FOR THE API
 */

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    AuthController,
    UserController,
    RoleController,
    CompanyController,
    PeopleController,
    FinancialTransactionController
};


Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'service' => 'Ash API',
        'version' => '1.0.0'

    ], 200);
});


Route::prefix('v1')->group(function () {
    
    // Rotas públicas (sem autenticação)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']); // Se necessário
    });
    
    // Rotas protegidas com Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        
        // Autenticação
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'me']);
        });
        
        // Usuários
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::patch('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::post('/{id}/restore', [UserController::class, 'restore']);
        });
        
        // Roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{id}', [RoleController::class, 'show']);
            Route::put('/{id}', [RoleController::class, 'update']);
            Route::patch('/{id}', [RoleController::class, 'update']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
        });
        
        // Empresas
        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'index']);
            Route::post('/', [CompanyController::class, 'store']);
            Route::get('/{id}', [CompanyController::class, 'show']);
            Route::put('/{id}', [CompanyController::class, 'update']);
            Route::patch('/{id}', [CompanyController::class, 'update']);
            Route::delete('/{id}', [CompanyController::class, 'destroy']);
            Route::post('/{id}/restore', [CompanyController::class, 'restore']);
        });
        
        // Pessoas
        Route::prefix('people')->group(function () {
            Route::get('/', [PeopleController::class, 'index']);
            Route::post('/', [PeopleController::class, 'store']);
            Route::get('/{id}', [PeopleController::class, 'show']);
            Route::put('/{id}', [PeopleController::class, 'update']);
            Route::patch('/{id}', [PeopleController::class, 'update']);
            Route::delete('/{id}', [PeopleController::class, 'destroy']);
            Route::post('/{id}/restore', [PeopleController::class, 'restore']);
        });
        
        // Transações Financeiras
        Route::prefix('financial-transactions')->group(function () {
            Route::get('/', [FinancialTransactionController::class, 'index']);
            Route::post('/', [FinancialTransactionController::class, 'store']);
            Route::get('/{id}', [FinancialTransactionController::class, 'show']);
            Route::put('/{id}', [FinancialTransactionController::class, 'update']);
            Route::patch('/{id}', [FinancialTransactionController::class, 'update']);
            Route::delete('/{id}', [FinancialTransactionController::class, 'destroy']);
            Route::post('/{id}/pay', [FinancialTransactionController::class, 'markAsPaid']);
            Route::get('/summary', [FinancialTransactionController::class, 'summary']);
        });
        
        // Outros recursos podem ser adicionados aqui
    });
    
    // Health check da API
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'service' => config('app.name'),
            'version' => '1.0.0',
        ]);
    });
});
