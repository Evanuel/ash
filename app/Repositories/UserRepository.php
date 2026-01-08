<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery();
        
        // Aplicar filtros
        $query = $this->applyFilters($query, $filters);
        
        return $query->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        
        // Aplicar filtros
        $query = $this->applyFilters($query, $filters);
        
        return $query->paginate($perPage);
    }

    public function create(array $data): User
    {
        // Hash da senha se fornecida
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->find($id);
        
        if (!$user) {
            return false;
        }
        
        // Hash da senha se fornecida
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $user->update($data);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $user = $this->find($id);
        
        if (!$user) {
            return false;
        }
        
        return $user->update([
            'password' => Hash::make($password)
        ]);
    }

    public function getByClientId(int $clientId, array $filters = []): Collection
    {
        $query = $this->model->where('client_id', $clientId);
        
        // Aplicar filtros adicionais
        $query = $this->applyFilters($query, $filters);
        
        return $query->get();
    }

    public function getAdmins(): Collection
    {
        // Implementar lógica para obter administradores
        // Isso pode variar conforme a regra de negócio
        return $this->model->whereHas('role', function ($query) {
            $query->where('level', '>=', 90); // Exemplo: nível de admin
        })->get();
    }

    /**
     * Aplicar filtros comuns
     */
    protected function applyFilters($query, array $filters)
    {
        if (isset($filters['active']) && in_array($filters['active'], [true, false, 'true', 'false'])) {
            $query->where('active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        }
        
        if (isset($filters['archived']) && in_array($filters['archived'], [true, false, 'true', 'false'])) {
            $query->where('archived', filter_var($filters['archived'], FILTER_VALIDATE_BOOLEAN));
        }
        
        if (isset($filters['role_id']) && is_numeric($filters['role_id'])) {
            $query->where('role_id', $filters['role_id']);
        }
        
        if (isset($filters['branch_id']) && is_numeric($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        return $query;
    }
}