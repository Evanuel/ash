<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado',
            ], 401);
        }
        
        // Verificar permissões
        $hasPermission = $this->checkUserPermission($user, $permission);
        
        if (!$hasPermission) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado. Permissão necessária: ' . $permission,
            ], 403);
        }
        
        return $next($request);
    }
    
    private function checkUserPermission($user, string $permission): bool
    {
        // Verificar permissões na role
        if ($user->role_id && $user->role) {
            $rolePermissions = $user->role->permissions ?? [];
            
            if (in_array($permission, $rolePermissions)) {
                return true;
            }
        }
        
        // Verificar permissões específicas do usuário
        $userPermissions = $user->permissions ?? [];
        return in_array($permission, $userPermissions);
    }
}