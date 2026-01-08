<?php
// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_users') || 
               $user->isSuperAdmin() || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Usuário pode ver a si mesmo
        if ($user->id === $model->id) {
            return true;
        }

        // Super admin pode ver qualquer um
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin pode ver usuários do mesmo cliente
        if ($user->isAdmin() && $user->client_id === $model->client_id) {
            return true;
        }

        // Supervisor pode ver seus subordinados
        if ($user->isSupervisor() && $model->supervisor_id === $user->id) {
            return true;
        }

        return $user->hasPermission('view_users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_users') || 
               $user->isSuperAdmin() || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Usuário pode atualizar a si mesmo
        if ($user->id === $model->id) {
            return true;
        }

        // Super admin pode atualizar qualquer um
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin pode atualizar usuários do mesmo cliente
        if ($user->isAdmin() && $user->client_id === $model->client_id) {
            return true;
        }

        // Supervisor pode atualizar seus subordinados
        if ($user->isSupervisor() && $model->supervisor_id === $user->id) {
            return true;
        }

        return $user->hasPermission('edit_users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Usuário não pode deletar a si mesmo
        if ($user->id === $model->id) {
            return false;
        }

        // Super admin pode deletar qualquer um
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin pode deletar usuários do mesmo cliente
        if ($user->isAdmin() && $user->client_id === $model->client_id) {
            return true;
        }

        // Supervisor pode deletar seus subordinados
        if ($user->isSupervisor() && $model->supervisor_id === $user->id) {
            return $user->hasPermission('delete_users');
        }

        return $user->hasPermission('delete_users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermission('restore_users') || 
               $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermission('force_delete_users') || 
               $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can change the user's role.
     */
    public function changeRole(User $user, User $model): bool
    {
        return $user->hasPermission('change_user_roles') || 
               $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can reset the user's password.
     */
    public function resetPassword(User $user, User $model): bool
    {
        return $user->hasPermission('reset_user_passwords') || 
               $user->isSuperAdmin() || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can archive the model.
     */
    public function archive(User $user, User $model): bool
    {
        // Usuário não pode arquivar a si mesmo
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('archive_users') || 
               $user->isSuperAdmin() || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can unarchive the model.
     */
    public function unarchive(User $user, User $model): bool
    {
        return $user->hasPermission('unarchive_users') || 
               $user->isSuperAdmin() || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can impersonate the model.
     */
    public function impersonate(User $user, User $model): bool
    {
        return $user->hasPermission('impersonate_users') || 
               $user->isSuperAdmin();
    }
}