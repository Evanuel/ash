<?php
// app/Http/Controllers/Api/V1/Controller.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Api\ApiResponse;

abstract class Controller extends BaseController
{
    use ApiResponse;

    /**
     * Middleware específico para API
     */
    public function __construct()
    {
        // Middleware de autenticação para todos os métodos, exceto os explicitamente excluídos
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        
        // Rate limiting para API
        $this->middleware('throttle:60,1')->only(['store', 'update', 'destroy']);
    }

    /**
     * Verifica se o usuário tem permissão para a ação
     */
    protected function checkPermission(string $permission): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Se o usuário tem role, verificar permissões
        if ($user->role_id && $user->role) {
            $rolePermissions = $user->role->permissions ?? [];
            
            // Verificar permissão na role
            if (in_array($permission, $rolePermissions)) {
                return true;
            }
        }

        // Verificar permissões específicas do usuário
        $userPermissions = $user->permissions ?? [];
        return in_array($permission, $userPermissions);
    }

    /**
     * Verificar permissão e lançar exceção se não tiver
     */
    protected function authorizeOrFail(string $permission, string $message = 'Acesso não autorizado'): void
    {
        if (!$this->checkPermission($permission)) {
            throw new \App\Exceptions\ApiException($message, 403);
        }
    }

    /**
     * Preparar query com filtros comuns
     */
    protected function applyFilters($query, array $filters = [])
    {
        // Filtro por client_id se disponível
        if (auth()->check() && isset($filters['client_id'])) {
            $user = auth()->user();
            
            // Se não for super admin, filtrar pelo client_id do usuário
            if (!$this->checkPermission('view_all_clients')) {
                $query->where('client_id', $user->client_id);
            }
        }

        // Filtro por status ativo
        if (isset($filters['active']) && in_array($filters['active'], [true, false, 'true', 'false'])) {
            $query->where('active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        }

        // Filtro por não arquivados
        if (!isset($filters['include_archived']) || !$filters['include_archived']) {
            $query->where('archived', false);
        }

        return $query;
    }
}