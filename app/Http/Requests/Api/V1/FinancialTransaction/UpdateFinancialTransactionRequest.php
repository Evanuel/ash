<?php
// app/Http/Requests/Api/V1/FinancialTransaction/UpdateFinancialTransactionRequest.php

namespace App\Http\Requests\Api\V1\FinancialTransaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;

class UpdateFinancialTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }
        return app(PermissionService::class)->has($user, 'financial-transaction.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $transactionId = $this->route('id') ?? $this->route('financial-transaction');

        return [
            // Tipo da transação
            'type_id' => [
                'sometimes',
                'integer',
                'min:1',
                'max:2',
            ],
            
            // Documento fiscal
            'fiscal_document' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],
            'cost_center' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],
            'description' => [
                'sometimes',
                'string',
                'max:500',
            ],
            
            // Categorias
            'category_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'subcategory_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            
            // Tipo de pessoa
            'person_type' => [
                'sometimes',
                'integer',
                'in:1,2',
            ],
            
            // Referências baseadas no tipo de pessoa
            'individual_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:people,id',
                function ($attribute, $value, $fail) {
                    if ($this->person_type == 1 && !$value) {
                        $fail('O campo pessoa física é obrigatório quando o tipo de pessoa é individual.');
                    }
                },
            ],
            'company_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:companies,id',
                function ($attribute, $value, $fail) {
                    if ($this->person_type == 2 && !$value) {
                        $fail('O campo empresa é obrigatório quando o tipo de pessoa é empresa.');
                    }
                },
            ],
            
            // Datas e valores
            'due_date' => [
                'sometimes',
                'date',
            ],
            'amount' => [
                'sometimes',
                'numeric',
                'min:0.01',
                'max:999999999999.99',
            ],
            
            // Status
            'status_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:statuses,id',
            ],
            
            // Informações de pagamento
            'boleto_url' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
                'url',
            ],
            'paid_at' => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'paid_amount' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
                function ($attribute, $value, $fail) use ($transactionId) {
                    $amount = $this->amount ?? $this->financialTransaction->amount ?? 0;
                    if ($value && $value > $amount) {
                        $fail('O valor pago não pode ser maior que o valor total.');
                    }
                    
                    // Se estiver marcando como pago, validar campos obrigatórios
                    if ($value && $value > 0 && !$this->has('paid_at')) {
                        $fail('A data de pagamento é obrigatória quando há valor pago.');
                    }
                },
            ],
            'bank_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:banks,id',
            ],
            'payment_method_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:payment_methods,id',
            ],
            
            // Parcelas
            'installment' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
                'max:360',
            ],
            'total_installments' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
                'max:360',
                function ($attribute, $value, $fail) {
                    if ($value && $this->installment && $value < $this->installment) {
                        $fail('O número total de parcelas não pode ser menor que a parcela atual.');
                    }
                },
            ],
            'transaction_key' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],
            
            // Anexos
            'receipt_url' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
                'url',
            ],
            
            // Campos personalizados
            'custom_field1' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'custom_field2' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'custom_field3' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'notes' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],
            'css_class' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                'in:text-warning,text-success,text-danger,text-info,text-primary',
            ],
            
            // Arquivamento
            'archived' => [
                'sometimes',
                'boolean',
                function ($attribute, $value, $fail) use ($transactionId) {
                    if ($value && $this->financialTransaction && $this->financialTransaction->paid_amount > 0) {
                        $fail('Não é possível arquivar uma transação com pagamento realizado.');
                    }
                },
            ],
            
            // Campos bloqueados
            'client_id' => [
                'prohibited',
            ],
            'created_by' => [
                'prohibited',
            ],
            'archived_by' => [
                'prohibited',
            ],
            'archived_at' => [
                'prohibited',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type_id' => 'tipo da transação',
            'fiscal_document' => 'documento fiscal',
            'cost_center' => 'centro de custo',
            'description' => 'descrição',
            'category_id' => 'categoria',
            'subcategory_id' => 'subcategoria',
            'person_type' => 'tipo de pessoa',
            'individual_id' => 'pessoa física',
            'company_id' => 'empresa',
            'due_date' => 'data de vencimento',
            'amount' => 'valor',
            'status_id' => 'status',
            'boleto_url' => 'URL do boleto',
            'paid_at' => 'data de pagamento',
            'paid_amount' => 'valor pago',
            'bank_id' => 'banco',
            'payment_method_id' => 'método de pagamento',
            'installment' => 'parcela',
            'total_installments' => 'total de parcelas',
            'transaction_key' => 'chave da transação',
            'receipt_url' => 'URL do comprovante',
            'notes' => 'observações',
            'css_class' => 'classe CSS',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'type_id.in' => 'O tipo da transação deve ser 1 (recebível) ou 2 (pagável).',
            'description.max' => 'A descrição não pode ter mais de 500 caracteres.',
            'person_type.in' => 'O tipo de pessoa deve ser 1 (individual) ou 2 (empresa).',
            'individual_id.exists' => 'A pessoa física selecionada não existe.',
            'company_id.exists' => 'A empresa selecionada não existe.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'amount.min' => 'O valor deve ser maior que zero.',
            'amount.max' => 'O valor não pode ser superior a 999.999.999.999,99.',
            'status_id.exists' => 'O status selecionado não existe.',
            'paid_at.before_or_equal' => 'A data de pagamento não pode ser futura.',
            'paid_amount.max' => 'O valor pago não pode ser superior a 999.999.999.999,99.',
            'bank_id.exists' => 'O banco selecionado não existe.',
            'payment_method_id.exists' => 'O método de pagamento selecionado não existe.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'subcategory_id.exists' => 'A subcategoria selecionada não existe.',
            'installment.min' => 'O número da parcela deve ser pelo menos 1.',
            'installment.max' => 'O número da parcela não pode ser maior que 360.',
            'total_installments.min' => 'O total de parcelas deve ser pelo menos 1.',
            'total_installments.max' => 'O total de parcelas não pode ser maior que 360.',
            'boleto_url.url' => 'A URL do boleto deve ser uma URL válida.',
            'receipt_url.url' => 'A URL do comprovante deve ser uma URL válida.',
            'css_class.in' => 'A classe CSS deve ser uma das opções: text-warning, text-success, text-danger, text-info, text-primary.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Converter valores monetários para formato correto
        foreach (['amount', 'paid_amount'] as $field) {
            if ($this->has($field)) {
                $value = $this->$field;
                if (is_string($value) && strpos($value, ',') !== false) {
                    $value = str_replace(['.', ','], ['', '.'], $value);
                }
                $this->merge([$field => (float) $value]);
            }
        }

        // Se paid_amount for alterado para 0 ou null, remover paid_at
        if ($this->has('paid_amount') && empty($this->paid_amount)) {
            $this->merge([
                'paid_at' => null,
            ]);
        }

        // Se paid_amount for preenchido, garantir que paid_at também seja
        if ($this->has('paid_amount') && $this->paid_amount > 0 && !$this->has('paid_at')) {
            $this->merge([
                'paid_at' => now()->format('Y-m-d'),
            ]);
        }

        // Garantir consistência entre person_type e os IDs
        if ($this->has('person_type')) {
            if ($this->person_type == 1) {
                $this->merge(['company_id' => null]);
            } else {
                $this->merge(['individual_id' => null]);
            }
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Adicionar audit field
        if (Auth::check() && !$this->has('updated_by')) {
            $this->merge([
                'updated_by' => Auth::id(),
            ]);
        }

        // Se estiver arquivando, adicionar timestamp
        if ($this->has('archived') && $this->archived) {
            $this->merge([
                'archived_at' => now(),
                'archived_by' => Auth::id(),
            ]);
        }

        // Se estiver desarquivando, limpar campos
        if ($this->has('archived') && !$this->archived) {
            $this->merge([
                'archived_at' => null,
                'archived_by' => null,
            ]);
        }

        // Se houver pagamento, garantir que o status seja atualizado
        if ($this->has('paid_amount') && $this->paid_amount > 0) {
            // Se não houver status_id definido ou se estiver marcando como pago
            if (!$this->has('status_id') || !$this->status_id) {
                // O controller deve buscar o status "Pago" do banco
            }
        }

        // Garantir que os valores monetários tenham 2 casas decimais
        foreach (['amount', 'paid_amount'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => round($this->$field, 2)]);
            }
        }

        // Se transaction_key for alterada, validar se há outras transações com a mesma chave
        if ($this->has('transaction_key') && $this->transaction_key !== '0') {
            // Validação adicional pode ser feita no controller
        }
    }
}