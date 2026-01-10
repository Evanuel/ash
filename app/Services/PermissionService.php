<?php

namespace App\Services;

use App\Models\User;

class PermissionService
{
    public function has(User $user, string $permission): bool
    {
        // Super admin
        if ($user->role && $user->role->level >= 100) {
            return true;
        }

        // Role permissions
        if ($user->role && is_array($user->role->permissions)) {
            if (in_array($permission, $user->role->permissions)) {
                return true;
            }
        }

        // User permissions
        if (is_array($user->permissions)) {
            if (in_array($permission, $user->permissions)) {
                return true;
            }
        }

        return false;
    }
}
