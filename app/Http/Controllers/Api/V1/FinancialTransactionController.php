<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\FinancialTransaction\StoreFinancialTransactionRequest;
use App\Http\Requests\Api\V1\FinancialTransaction\UpdateFinancialTransactionRequest;
use App\Http\Resources\Api\V1\FinancialTransactionResource;
use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FinancialTransactionController extends BaseController
{

    protected Model $model;

    protected string $resource = FinancialTransactionResource::class;

    protected string $permissionView   = 'financial-transaction.view';
    protected string $permissionCreate = 'financial-transaction.create';
    protected string $permissionUpdate = 'financial-transaction.update';
    protected string $permissionDelete = 'financial-transaction.delete';

    protected array $allowedFilters = [
        'type_id',
        'person_type',
        'status_id',
        'archived',
        'client_id',
        'category_id',
        'subcategory_id',
    ];

    protected array $searchable = [
        'description',
        'fiscal_document',
        'cost_center',
    ];

    /**
     * Ordenação padrão
     */
    protected string $defaultSort = 'due_date';
    protected string $defaultOrder = 'asc';

    public function __construct(FinancialTransaction $financialTransaction)
    {
        $this->model = $financialTransaction;
    }

    public function store(StoreFinancialTransactionRequest $request)
    {
        \Log::info('=== FINANCIAL TRANSACTION STORE START ===', [
            'user_id' => auth()->id(),
            'has_authorization' => $request->authorize(),
            'data_keys' => array_keys($request->all())
        ]);

        try {
            $this->authorizeOrFail($this->permissionCreate);
            \Log::info('Authorization passed in controller');

            $data = $request->validated();
            \Log::info('Request validated', ['data' => $data]);

            // Adicionar campos automáticos se não fornecidos
            if (!isset($data['client_id']) && auth()->check()) {
                $data['client_id'] = auth()->user()->client_id;
            }

            if (!isset($data['created_by']) && auth()->check()) {
                $data['created_by'] = auth()->id();
            }

            // Gerar transaction_key para parcelas
            if (($data['total_installments'] ?? 1) > 1 && empty($data['transaction_key'])) {
                $data['transaction_key'] = uniqid('TRX_', true);
            }

            \Log::info('Creating transaction with data:', $data);

            $item = $this->model->create($data);
            // Carregar os relacionamentos necessários
            $item->load(['status', 'type', 'category', 'subcategory', 'company', 'individual', 'paymentMethod', 'bank']);
            
            \Log::info('Transaction created successfully', ['id' => $item->id]);

            return new $this->resource($item);
        } catch (\Exception $e) {
            \Log::error('Error creating financial transaction:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => auth()->id()
            ]);
            throw $e;
        } finally {
            \Log::info('=== FINANCIAL TRANSACTION STORE END ===');
        }
    }

    public function storeOld(StoreFinancialTransactionRequest $request)
    {
        $this->authorizeOrFail($this->permissionCreate);

        $data = $request->validated();
        if (!isset($data['client_id']) && auth()->check()) {
            $data['client_id'] = auth()->user()->client_id;
        }

        if (!isset($data['created_by']) && auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        // Gerar transaction_key para parcelas
        if (($data['total_installments'] ?? 1) > 1 && empty($data['transaction_key'])) {
            $data['transaction_key'] = uniqid('TRX_', true);
        }

        $item = $this->model->create($data);
        return new $this->resource($item);
    }

    // public function store(StoreFinancialTransactionRequest $request) {
    //     error_log("Mensagem de erro ou debug");
    //     \Log::error('Usuário não autenticado na requisição de transação financeira');
    //     return response()->json(['error' => 'Unauthorized'], 401);

    //     $this->authorizeOrFail($this->permissionCreate);

    //     $item = $this->model->create($request->validated());

    //     return new $this->resource($item);
    // }

    public function update(UpdateFinancialTransactionRequest $request, int|string $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $item = $this->model->findOrFail($id);
        $item->update($request->validated());

        return new $this->resource($item);
    }

    /**
     * Marcar transação como paga
     */
    public function markAsPaid(UpdateFinancialTransactionRequest $request, $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $transaction = $this->model->findOrFail($id);

        // Preparar dados específicos para marcação como pago
        $data = [
            'paid_amount' => $request->input('paid_amount', $transaction->amount),
            'paid_at' => $request->input('paid_at', now()->format('Y-m-d')),
            'payment_method_id' => $request->input('payment_method_id', $transaction->payment_method_id),
            'bank_id' => $request->input('bank_id', $transaction->bank_id),
            'receipt_url' => $request->input('receipt_url', $transaction->receipt_url),
        ];

        // Remover campos vazios
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        $transaction->update($data);

        return new $this->resource($transaction);
    }

    /**
     * Restaurar transação deletada (soft delete)
     */
    public function restore($id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $transaction = $this->model->withTrashed()->findOrFail($id);
        $transaction->restore();

        return new $this->resource($transaction);
    }

    /**
     * Download de recibo (simulado)
     */
    public function downloadReceipt($id)
    {
        $this->authorizeOrFail($this->permissionView);

        $transaction = $this->model->findOrFail($id);

        if (!$transaction->receipt_url) {
            return response()->json([
                'success' => false,
                'message' => 'Recibo não disponível'
            ], 404);
        }

        // Em uma implementação real, você faria o download do arquivo
        return response()->json([
            'success' => true,
            'message' => 'Recibo disponível para download',
            'url' => $transaction->receipt_url
        ]);
    }

    /**
     * Resumo de transações
     */
    public function summary(Request $request)
    {
        $this->authorizeOrFail($this->permissionView);

        $query = $this->model->newQuery();

        // Aplicar filtros por data
        if ($request->has('start_date')) {
            $query->where('due_date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('due_date', '<=', $request->input('end_date'));
        }

        // Aplicar outros filtros
        $this->applyFilters($query, $request);

        $transactions = $query->get();

        $summary = [
            'total' => [
                'count' => $transactions->count(),
                'amount' => $transactions->sum('amount'),
                'paid' => $transactions->sum('paid_amount'),
                'pending' => $transactions->sum('amount') - $transactions->sum('paid_amount'),
            ],
            'by_type' => [
                'receivable' => [
                    'count' => $transactions->where('type_id', 1)->count(),
                    'amount' => $transactions->where('type_id', 1)->sum('amount'),
                    'paid' => $transactions->where('type_id', 1)->sum('paid_amount'),
                ],
                'payable' => [
                    'count' => $transactions->where('type_id', 2)->count(),
                    'amount' => $transactions->where('type_id', 2)->sum('amount'),
                    'paid' => $transactions->where('type_id', 2)->sum('paid_amount'),
                ],
            ],
            'by_status' => [
                'paid' => $transactions->whereNotNull('paid_at')->count(),
                'overdue' => $transactions->where('due_date', '<', now())
                    ->whereNull('paid_at')
                    ->count(),
                'pending' => $transactions->where('due_date', '>=', now())
                    ->whereNull('paid_at')
                    ->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
            'period' => [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]
        ]);
    }

    /**
     * Sobrescrever o método index para adicionar filtros específicos
     */
    public function index(Request $request)
    {
        $this->authorizeOrFail($this->permissionView);

        $query = $this->model->newQuery();

        // Filtro por status de pagamento
        if ($request->has('payment_status')) {
            $status = $request->input('payment_status');
            switch ($status) {
                case 'paid':
                    $query->whereNotNull('paid_at');
                    break;
                case 'overdue':
                    $query->whereNull('paid_at')
                        ->where('due_date', '<', now());
                    break;
                case 'pending':
                    $query->whereNull('paid_at')
                        ->where('due_date', '>=', now());
                    break;
            }
        }

        // Filtro por intervalo de datas
        if ($request->has('date_range')) {
            $range = $request->input('date_range');
            switch ($range) {
                case 'today':
                    $query->whereDate('due_date', now());
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('due_date', [
                        now()->startOfMonth(),
                        now()->endOfMonth()
                    ]);
                    break;
                case 'next_30_days':
                    $query->whereBetween('due_date', [
                        now(),
                        now()->addDays(30)
                    ]);
                    break;
            }
        }

        $this->applyFilters($query, $request);
        $this->applySearch($query, $request);
        $this->applySorting($query, $request);

        $data = $query->paginate(
            $request->integer('per_page', $this->perPage)
        );

        return $this->resource::collection($data);
    }
}
