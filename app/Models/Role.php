<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'level',
        'permissions',
        'active',
        'client_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'level' => 'integer',
        'permissions' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    /**
     * Scope para roles por nível mínimo
     */
    public function scopeMinLevel($query, int $level)
    {
        return $query->where('level', '>=', $level);
    }

    /**
     * Scope para roles por cliente
     */
    public function scopeByClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId)->orWhereNull('client_id');
    }

    public function addPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
        }
    }

    public function removePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        $key = array_search($permission, $permissions);
        if ($key !== false) {
            unset($permissions[$key]);
            $this->permissions = array_values($permissions);
        }
    }

    /**
     * Contador de permissões
     */
    public function getPermissionCountAttribute(): int
    {
        return count($this->permissions ?? []);
    }

    /**
     * Verifica se a role é de administrador
     */
    public function isAdmin(): bool
    {
        return $this->level >= 90;
    }

    /**
     * Verifica se a role é de super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->level >= 100;
    }
}
