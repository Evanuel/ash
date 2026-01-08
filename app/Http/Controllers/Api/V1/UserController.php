<?php
// app/Http/Controllers/Api/V1/UserController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\User\StoreUserRequest;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Listar usuários
     */
    public function index(): JsonResponse
    {
        try {
            // Verificar permissão
            $this->authorizeOrFail('view_users', 'Você não tem permissão para visualizar usuários');
            
            // Obter parâmetros de filtro
            $filters = request()->all();
            
            // Paginação
            $perPage = request()->get('per_page', 15);
            $users = $this->userRepository->paginate($perPage, $filters);
            
            return $this->success(
                UserResource::collection($users)->response()->getData(true),
                'Usuários listados com sucesso'
            );
            
        } catch (\App\Exceptions\ApiException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error('Erro ao listar usuários: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Exibir usuário específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Verificar permissão
            $this->authorizeOrFail('view_users', 'Você não tem permissão para visualizar usuários');
            
            $user = $this->userRepository->find($id);
            
            if (!$user) {
                return $this->notFound('Usuário não encontrado');
            }
            
            return $this->success(new UserResource($user), 'Usuário obtido com sucesso');
            
        } catch (\App\Exceptions\ApiException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error('Erro ao obter usuário: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar novo usuário
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            // Verificar permissão
            $this->authorizeOrFail('create_users', 'Você não tem permissão para criar usuários');
            
            $data = $request->validated();
            
            // Adicionar client_id do usuário logado se não fornecido
            if (!isset($data['client_id']) && auth()->check()) {
                $data['client_id'] = auth()->user()->client_id;
            }
            
            // Criar usuário
            $user = $this->userRepository->create($data);
            
            return $this->created(new UserResource($user), 'Usuário criado com sucesso');
            
        } catch (\App\Exceptions\ApiException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error('Erro ao criar usuário: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar usuário
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            // Verificar permissão
            $this->authorizeOrFail('edit_users', 'Você não tem permissão para editar usuários');
            
            $user = $this->userRepository->find($id);
            
            if (!$user) {
                return $this->notFound('Usuário não encontrado');
            }
            
            $data = $request->validated();
            
            // Atualizar usuário
            $updated = $this->userRepository->update($id, $data);
            
            if ($updated) {
                $user = $this->userRepository->find($id); // Recarregar dados atualizados
                return $this->success(new UserResource($user), 'Usuário atualizado com sucesso');
            }
            
            return $this->error('Falha ao atualizar usuário', 500);
            
        } catch (\App\Exceptions\ApiException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error('Erro ao atualizar usuário: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir usuário (soft delete)
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Verificar permissão
            $this->authorizeOrFail('delete_users', 'Você não tem permissão para excluir usuários');
            
            $user = $this->userRepository->find($id);
            
            if (!$user) {
                return $this->notFound('Usuário não encontrado');
            }
            
            // Não permitir excluir a si mesmo
            if (auth()->id() === $user->id) {
                return $this->error('Você não pode excluir sua própria conta', 403);
            }
            
            // Excluir usuário
            $deleted = $this->userRepository->delete($id);
            
            if ($deleted) {
                return $this->success(null, 'Usuário excluído com sucesso');
            }
            
            return $this->error('Falha ao excluir usuário', 500);
            
        } catch (\App\Exceptions\ApiException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error('Erro ao excluir usuário: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restaurar usuário arquivado
     */
    public function restore(int $id): JsonResponse
    {
        try {
            // Verificar permissão
            $this->authorizeOrFail('restore_users', 'Você não tem permissão para restaurar usuários');
            
            $user = $this->userRepository->find($id);
            
            if (!$user) {
                return $this->notFound('Usuário não encontrado');
            }
            
            // Restaurar usuário
            $restored = $this->userRepository->restore($id);
            
            if ($restored) {
                $user = $this->userRepository->find($id); // Recarregar dados atualizados
                return $this->success(new UserResource($user), 'Usuário restaurado com sucesso');
            }
            
            return $this->error('Falha ao restaurar usuário', 500);
            
        } catch (\App\Exceptions\ApiException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error('Erro ao restaurar usuário: ' . $e->getMessage(), 500);
        }
    }
}