<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        // Adicionar outras políticas aqui
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para permissões gerais
        Gate::define('has-permission', function (User $user, string $permission) {
            return $user->hasPermission($permission);
        });

        // Gate para verificar múltiplas permissões
        Gate::define('has-any-permission', function (User $user, array $permissions) {
            return $user->hasAnyPermission($permissions);
        });

        // Gate para verificar todas as permissões
        Gate::define('has-all-permissions', function (User $user, array $permissions) {
            return $user->hasAllPermissions($permissions);
        });

        // Gate para verificar se é admin
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        // Gate para verificar se é super admin
        Gate::define('is-super-admin', function (User $user) {
            return $user->isSuperAdmin();
        });

        // Gate para verificar se é supervisor
        Gate::define('is-supervisor', function (User $user) {
            return $user->isSupervisor();
        });
    }
}