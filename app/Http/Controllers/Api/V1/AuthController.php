<?php
// app/Http/Controllers/Api/V1/AuthController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiResponse;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login do usuário
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['As credenciais fornecidas estão incorretas.'],
                ]);
            }

            // Verificar se o usuário está ativo
            if (!$user->active) {
                return $this->error('Sua conta está desativada. Entre em contato com o administrador.', 403);
            }

            // Verificar se está arquivado
            if ($user->archived) {
                return $this->error('Sua conta está arquivada. Entre em contato com o administrador.', 403);
            }

            // Criar token
            $token = $user->createToken($request->device_name)->plainTextToken;

            return $this->success([
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => now()->addMinutes(config('sanctum.expiration', 525600))->toISOString(),
            ], 'Login realizado com sucesso');

        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), 'Credenciais inválidas');
        } catch (\Exception $e) {
            return $this->error('Erro ao realizar login: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Logout do usuário
     */
    public function logout(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if ($user) {
                // Revogar todos os tokens do usuário
                $user->tokens()->delete();
                
                return $this->success(null, 'Logout realizado com sucesso');
            }
            
            return $this->error('Usuário não autenticado', 401);
            
        } catch (\Exception $e) {
            return $this->error('Erro ao realizar logout: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter usuário atual
     */
    public function me(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return $this->error('Usuário não autenticado', 401);
            }
            
            return $this->success(new UserResource($user), 'Usuário obtido com sucesso');
            
        } catch (\Exception $e) {
            return $this->error('Erro ao obter usuário: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Refresh token (se necessário)
     */
    public function refresh(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return $this->error('Usuário não autenticado', 401);
            }
            
            // Revogar token atual
            $user->currentAccessToken()->delete();
            
            // Criar novo token
            $token = $user->createToken('refresh-token')->plainTextToken;
            
            return $this->success([
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => now()->addMinutes(config('sanctum.expiration', 525600))->toISOString(),
            ], 'Token atualizado com sucesso');
            
        } catch (\Exception $e) {
            return $this->error('Erro ao atualizar token: ' . $e->getMessage(), 500);
        }
    }

    /**
     * 
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = $this->authService->register($request->validated());

            DB::commit();

            return $this->success(new UserResource($user), 'Registro realizado com sucesso');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Erro ao registrar usuário: ' . $e->getMessage(), 500);
        }
    }
}