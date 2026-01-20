<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Person\StorePersonRequest;
use App\Http\Requests\Api\V1\Person\UpdatePersonRequest;
use App\Http\Resources\Api\V1\PersonResource;
use App\Http\Resources\Api\V1\PersonCollection;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Person;
        $this->resource = PersonResource::class;
        $this->collection = PersonCollection::class;
        
        // Configurações específicas do PersonController
        $this->permissionView = 'people.view';
        $this->permissionCreate = 'people.create';
        $this->permissionUpdate = 'people.update';
        $this->permissionDelete = 'people.delete';
        
        // Filtros permitidos
        $this->allowedFilters = [
            'type',
            'category_id',
            'subcategory_id',
            'state_id',
            'city_id',
            'activated',
            'status',
            'situation',
            'archived',
        ];
        
        // Campos pesquisáveis
        $this->searchable = [
            'cpf',
            'first_name',
            'last_name',
            'email',
            'phone',
            'rg',
        ];
        
        // Ordenação padrão
        $this->defaultSort = 'first_name';
        $this->defaultOrder = 'asc';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return parent::index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonRequest $request)
    {
        $this->authorizeOrFail($this->permissionCreate);
        
        $validated = $request->validated();
        $person = Person::create($validated);
        
        // Carregar relacionamentos
        $person->load(['client', 'state', 'city', 'category', 'subcategory']);
        
        return new PersonResource($person);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = $this->model->with([
            'client',
            'state',
            'city', 
            'category',
            'subcategory',
            'users',
            'financialTransactions'
        ])->findOrFail($id);
        
        return new PersonResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);
        
        $person = Person::findOrFail($id);
        $validated = $request->validated();
        $person->update($validated);
        
        // Carregar relacionamentos atualizados
        $person->load(['client', 'state', 'city', 'category', 'subcategory']);
        
        return new PersonResource($person);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }

    /**
     * Restaurar uma pessoa arquivada.
     */
    public function restore($id)
    {
        $this->authorizeOrFail($this->permissionUpdate);
        
        $person = Person::withTrashed()->findOrFail($id);
        
        // Verificar se a pessoa está realmente excluída
        if (!$person->trashed()) {
            return response()->json([
                'message' => 'Esta pessoa não está excluída.'
            ], 400);
        }
        
        $person->restore();
        
        return response()->json([
            'success' => true,
            'message' => 'Pessoa restaurada com sucesso.',
            'data' => new PersonResource($person)
        ]);
    }

    /**
     * Arquivar uma pessoa.
     */
    public function archive($id)
    {
        $this->authorizeOrFail($this->permissionUpdate);
        
        $person = Person::findOrFail($id);
        
        $person->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pessoa arquivada com sucesso.',
            'data' => new PersonResource($person)
        ]);
    }

    /**
     * Desarquivar uma pessoa.
     */
    public function unarchive($id)
    {
        $this->authorizeOrFail($this->permissionUpdate);
        
        $person = Person::findOrFail($id);
        
        $person->update([
            'archived' => false,
            'archived_at' => null,
            'archived_by' => null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pessoa desarquivada com sucesso.',
            'data' => new PersonResource($person)
        ]);
    }

    /**
     * Listar pessoas arquivadas.
     */
    public function archived(Request $request)
    {
        $this->authorizeOrFail($this->permissionView);
        
        $query = $this->model->newQuery()->where('archived', true);
        
        $this->applyFilters($query, $request);
        $this->applySearch($query, $request);
        $this->applySorting($query, $request);
        
        $data = $query->paginate(
            $request->integer('per_page', $this->perPage)
        );
        
        return $this->collection::collection($data);
    }

    /**
     * Atualizar o crédito de uma pessoa.
     */
    public function updateCredit(Request $request, $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);
        
        $request->validate([
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'used_credit' => ['nullable', 'numeric', 'min:0'],
        ]);
        
        $person = Person::findOrFail($id);
        
        $person->update([
            'credit_limit' => $request->credit_limit ?? $person->credit_limit,
            'used_credit' => $request->used_credit ?? $person->used_credit,
            'updated_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Crédito atualizado com sucesso.',
            'data' => new PersonResource($person)
        ]);
    }

    /**
     * Estatísticas das pessoas.
     */
    public function stats()
    {
        $this->authorizeOrFail($this->permissionView);
        
        $user = Auth::user();
        
        $stats = [
            'total' => Person::where('client_id', $user->client_id)->count(),
            'active' => Person::where('client_id', $user->client_id)
                            ->where('status', true)
                            ->where('archived', false)
                            ->count(),
            'inactive' => Person::where('client_id', $user->client_id)
                              ->where('status', false)
                              ->where('archived', false)
                              ->count(),
            'archived' => Person::where('client_id', $user->client_id)
                               ->where('archived', true)
                               ->count(),
            'with_credit' => Person::where('client_id', $user->client_id)
                                  ->where('credit_limit', '>', 0)
                                  ->where('archived', false)
                                  ->count(),
            'total_credit_limit' => Person::where('client_id', $user->client_id)
                                        ->where('archived', false)
                                        ->sum('credit_limit'),
            'total_used_credit' => Person::where('client_id', $user->client_id)
                                        ->where('archived', false)
                                        ->sum('used_credit'),
        ];
        
        $stats['available_credit'] = $stats['total_credit_limit'] - $stats['total_used_credit'];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Aplicar filtros específicos para pessoas.
     */
    protected function applyFilters($query, Request $request): void
    {
        parent::applyFilters($query, $request);
        
        // Filtro por cliente (sempre restringir ao cliente do usuário)
        $user = Auth::user();
        $query->where('client_id', $user->client_id);
        
        // Filtro por tipo específico
        if ($request->has('person_type')) {
            $query->where('type', $request->person_type);
        }
        
        // Filtro por situação
        if ($request->has('situation')) {
            $query->where('situation', $request->situation);
        }
        
        // Filtro por data de nascimento (range)
        if ($request->has('birthdate_from')) {
            $query->where('birthdate', '>=', $request->birthdate_from);
        }
        
        if ($request->has('birthdate_to')) {
            $query->where('birthdate', '<=', $request->birthdate_to);
        }
        
        // Filtro por crédito disponível
        if ($request->has('min_available_credit')) {
            $query->whereRaw('(credit_limit - used_credit) >= ?', [$request->min_available_credit]);
        }
        
        if ($request->has('max_available_credit')) {
            $query->whereRaw('(credit_limit - used_credit) <= ?', [$request->max_available_credit]);
        }
        
        // Por padrão, mostrar apenas não arquivados
        if (!$request->has('archived')) {
            $query->where('archived', false);
        }
    }
}