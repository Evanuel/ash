<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'client_id',
        'name',
        'email',
        'password',
        'role_id',
        'permissions',
        'branch_id',
        'supervisor_id',
        'user_id', // Legacy system ID
        'company_id',
        'people_id',
        'archived',
        'archived_by',
        'archived_at',
        'custom_field1',
        'custom_field2',
        'custom_field3',
        'notes',
        'profile_image',
        'active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
        'active' => 'boolean',
        'client_id' => 'integer',
        'branch_id' => 'integer',
        'supervisor_id' => 'integer',
        'company_id' => 'integer',
        'people_id' => 'integer',
        'role_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'archived_by' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_name',
        'avatar_url',
        'is_active',
        'is_archived',
        'permission_list',
    ];

    /**
     * Default values for attributes
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'client_id' => 0,
        'branch_id' => 0,
        'user_id' => 0,
        'active' => true,
        'archived' => false,
        'created_by' => 0,
    ];

    /**
     * RELAÇÕES DO MODEL
     */

    /**
     * Role do usuário
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Supervisor do usuário
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Subordinados do usuário (se for supervisor)
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    /**
     * Empresa associada ao usuário
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Pessoa associada ao usuário
     */
    public function person()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    /**
     * Cliente (tenant) do usuário
     */
    public function client()
    {
        // Se você tiver um modelo Client
        // return $this->belongsTo(Client::class, 'client_id');
        return null; // Implementar quando tiver modelo Client
    }

    /**
     * Ramo/filial do usuário
     */
    public function branch()
    {
        // Se você tiver um modelo Branch
        // return $this->belongsTo(Branch::class, 'branch_id');
        return null; // Implementar quando tiver modelo Branch
    }

    /**
     * Usuário que criou este registro
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuário que atualizou este registro
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Usuário que arquivou este registro
     */
    public function archiver()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Tokens de acesso pessoais
     */
    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    /**
     * Transações financeiras criadas pelo usuário
     */
    public function createdFinancialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'created_by');
    }

    /**
     * SESSÕES ATIVAS
     */

    /**
     * Sessões ativas do usuário
     */
    public function activeSessions()
    {
        return $this->hasMany(Session::class, 'user_id')
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime')))
            ->orderBy('last_activity', 'desc');
    }

    /**
     * MÉTODOS DE PERMISSÃO
     */

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    public function hasPermission(string $permission): bool
    {
        // Se for super admin (role level 100)
        if ($this->role && $this->role->level >= 100) {
            return true;
        }

        $permissions = $this->getAllPermissions();

        return in_array($permission, $permissions);
    }

    /**
     * Verifica se o usuário tem qualquer uma das permissões
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o usuário tem todas as permissões
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtém todas as permissões do usuário (role + permissões individuais)
     */
    public function getAllPermissions(): array
    {
        $permissions = [];

        // Permissões da role
        if ($this->role && $this->role->permissions) {
            $rolePermissions = is_array($this->role->permissions) 
                ? $this->role->permissions 
                : json_decode($this->role->permissions, true);
            
            if ($rolePermissions) {
                $permissions = array_merge($permissions, $rolePermissions);
            }
        }

        // Permissões individuais do usuário
        if ($this->permissions) {
            $userPermissions = is_array($this->permissions) 
                ? $this->permissions 
                : json_decode($this->permissions, true);
            
            if ($userPermissions) {
                $permissions = array_merge($permissions, $userPermissions);
            }
        }

        // Remover duplicatas
        $permissions = array_unique($permissions);

        // Garantir que seja um array
        return $permissions ?: [];
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->level >= 90;
    }

    /**
     * Verifica se o usuário é super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->level >= 100;
    }

    /**
     * Verifica se o usuário é supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->subordinates()->count() > 0;
    }

    /**
     * Verifica se o usuário pode gerenciar outro usuário
     */
    public function canManageUser(User $user): bool
    {
        // Um usuário pode gerenciar a si mesmo
        if ($this->id === $user->id) {
            return true;
        }

        // Super admin pode gerenciar qualquer um
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admin pode gerenciar usuários do mesmo cliente
        if ($this->isAdmin() && $this->client_id === $user->client_id) {
            return true;
        }

        // Supervisor pode gerenciar seus subordinados
        if ($this->isSupervisor() && $user->supervisor_id === $this->id) {
            return true;
        }

        return false;
    }

    /**
     * MÉTODOS DE STATUS
     */

    /**
     * Verifica se o usuário está ativo
     */
    public function isActive(): bool
    {
        return $this->active && !$this->archived && !$this->trashed();
    }

    /**
     * Ativa o usuário
     */
    public function activate(): bool
    {
        return $this->update([
            'active' => true,
            'archived' => false,
        ]);
    }

    /**
     * Desativa o usuário
     */
    public function deactivate(): bool
    {
        return $this->update(['active' => false]);
    }

    /**
     * Arquiva o usuário
     */
    public function archive(?int $archivedBy = null): bool
    {
        return $this->update([
            'archived' => true,
            'archived_by' => $archivedBy ?? auth()->id(),
            'archived_at' => now(),
            'active' => false,
        ]);
    }

    /**
     * Desarquiva o usuário
     */
    public function unarchive(): bool
    {
        return $this->update([
            'archived' => false,
            'archived_by' => null,
            'archived_at' => null,
            'active' => true,
        ]);
    }

    /**
     * SCOPES DE CONSULTA
     */

    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where('archived', false);
    }

    /**
     * Scope para usuários inativos
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false)
            ->orWhere('archived', true);
    }

    /**
     * Scope para usuários não arquivados
     */
    public function scopeNotArchived($query)
    {
        return $query->where('archived', false);
    }

    /**
     * Scope para usuários de um cliente específico
     */
    public function scopeByClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope para usuários de uma filial específica
     */
    public function scopeByBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope para usuários com uma role específica
     */
    public function scopeByRole($query, int $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope para usuários administradores
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('level', '>=', 90);
        });
    }

    /**
     * Scope para busca por nome, email ou username
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        });
    }

    /**
     * ACCESSORS (GETTERS)
     */

    /**
     * Obtém o nome completo do usuário
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Obtém a URL do avatar do usuário
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->profile_image) {
            // Se for uma URL completa
            if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
                return $this->profile_image;
            }
            
            // Se for um caminho relativo
            return asset('storage/' . $this->profile_image);
        }
        
        // Avatar padrão baseado nas iniciais
        return $this->generateDefaultAvatar();
    }

    /**
     * Verifica se o usuário está ativo
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->isActive();
    }

    /**
     * Verifica se o usuário está arquivado
     */
    public function getIsArchivedAttribute(): bool
    {
        return $this->archived;
    }

    /**
     * Obtém a lista de permissões
     */
    public function getPermissionListAttribute(): array
    {
        return $this->getAllPermissions();
    }

    /**
     * Obtém o nível de acesso do usuário
     */
    public function getAccessLevelAttribute(): int
    {
        return $this->role ? $this->role->level : 0;
    }

    /**
     * MUTATORS (SETTERS)
     */

    /**
     * Define o email em minúsculas
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Define o username em minúsculas
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower(trim($value));
    }

    /**
     * Define o nome com capitalização correta
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Define as permissões como JSON
     */
    public function setPermissionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['permissions'] = json_encode($value);
        } else {
            $this->attributes['permissions'] = $value;
        }
    }

    /**
     * MÉTODOS AUXILIARES
     */

    /**
     * Gera um avatar padrão baseado nas iniciais
     */
    private function generateDefaultAvatar(): string
    {
        $initials = $this->getInitials();
        $colors = [
            'FF6B6B', '4ECDC4', '45B7D1', '96CEB4', 'FFEAA7',
            'DDA0DD', '98D8C8', 'F7DC6F', 'BB8FCE', '85C1E9'
        ];
        
        $colorIndex = crc32($this->email) % count($colors);
        $color = $colors[$colorIndex];
        
        return "https://ui-avatars.com/api/?name={$initials}&background={$color}&color=fff&size=128&bold=true";
    }

    /**
     * Obtém as iniciais do nome
     */
    private function getInitials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
                if (strlen($initials) >= 2) {
                    break;
                }
            }
        }
        
        return $initials ?: substr(strtoupper($this->username), 0, 2);
    }

    /**
     * EVENTOS DO MODEL
     */

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (auth()->check() && !$user->created_by) {
                $user->created_by = auth()->id();
            }
            
            // Gerar username único se não fornecido
            if (!$user->username && $user->email) {
                $username = explode('@', $user->email)[0];
                $originalUsername = $username;
                $counter = 1;
                
                while (self::where('username', $username)->exists()) {
                    $username = $originalUsername . $counter;
                    $counter++;
                }
                
                $user->username = $username;
            }
        });

        static::updating(function ($user) {
            if (auth()->check() && !$user->isDirty('updated_by')) {
                $user->updated_by = auth()->id();
            }
        });

        static::deleting(function ($user) {
            // Não permitir que um usuário delete a si mesmo
            if ($user->id === auth()->id()) {
                throw new \Exception('Você não pode excluir sua própria conta.');
            }
        });
    }

    /**
     * MÉTODOS PARA API
     */

    /**
     * Dados para API pública (sem informações sensíveis)
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar_url,
            'role' => $this->role ? [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'level' => $this->role->level,
            ] : null,
            'is_active' => $this->is_active,
            'is_admin' => $this->isAdmin(),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Dados completos para admin
     */
    public function toAdminArray(): array
    {
        return array_merge($this->toApiArray(), [
            'client_id' => $this->client_id,
            'branch_id' => $this->branch_id,
            'supervisor_id' => $this->supervisor_id,
            'company_id' => $this->company_id,
            'people_id' => $this->people_id,
            'archived' => $this->archived,
            'archived_at' => $this->archived_at,
            'archived_by' => $this->archived_by,
            'email_verified_at' => $this->email_verified_at,
            'last_login_at' => $this->last_login_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'permissions' => $this->permission_list,
            'access_level' => $this->access_level,
            'has_supervisor' => !is_null($this->supervisor_id),
            'subordinates_count' => $this->subordinates()->count(),
        ]);
    }
}