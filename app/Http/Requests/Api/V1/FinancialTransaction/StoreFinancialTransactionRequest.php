<?php
// app/Http/Requests/Api/V1/FinancialTransaction/StoreFinancialTransactionRequest.php

namespace App\Http\Requests\Api\V1\FinancialTransaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreFinancialTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = Auth::user();
        $clientId = $user ? $user->client_id : 0;

        return [
            // Tipo da transação (1 = receivable, 2 = payable)
            'type_id' => [
                'required',
                'integer',
                'min:1',
                'max:2',
            ],

            // Documento fiscal
            'fiscal_document' => [
                'nullable',
                'string',
                'max:100',
            ],
            'cost_center' => [
                'nullable',
                'string',
                'max:100',
            ],
            'description' => [
                'required',
                'string',
                'max:500',
            ],

            // Categorias
            'category_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'subcategory_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],

            // Tipo de pessoa (1 = individual, 2 = company)
            'person_type' => [
                'required',
                'integer',
                'in:1,2',
            ],

            // Referências baseadas no tipo de pessoa
            'individual_id' => [
                Rule::requiredIf(function () {
                    return $this->person_type == 1;
                }),
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
                Rule::requiredIf(function () {
                    return $this->person_type == 2;
                }),
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
                'required',
                'date',
                'after_or_equal:today',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999999.99', // 15 dígitos, 2 decimais
            ],

            // Status
            'status_id' => [
                'nullable',
                'integer',
                'exists:statuses,id',
            ],

            // Informações de pagamento
            'boleto_url' => [
                'nullable',
                'string',
                'max:500',
                'url',
            ],
            'paid_at' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
                function ($attribute, $value, $fail) {
                    if ($value && $this->amount && $value > $this->amount) {
                        $fail('O valor pago não pode ser maior que o valor total.');
                    }
                },
            ],
            'bank_id' => [
                'nullable',
                'integer',
                'exists:banks,id',
            ],
            'payment_method_id' => [
                'nullable',
                'integer',
                'exists:payment_methods,id',
            ],

            // Parcelas
            'installment' => [
                'nullable',
                'integer',
                'min:1',
                'max:360',
            ],
            'total_installments' => [
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
                'nullable',
                'string',
                'max:100',
            ],

            // Anexos
            'receipt_url' => [
                'nullable',
                'string',
                'max:500',
                'url',
            ],

            // Campos personalizados
            'custom_field1' => [
                'nullable',
                'string',
                'max:255',
            ],
            'custom_field2' => [
                'nullable',
                'string',
                'max:255',
            ],
            'custom_field3' => [
                'nullable',
                'string',
                'max:255',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'css_class' => [
                'nullable',
                'string',
                'max:100',
                'in:text-warning,text-success,text-danger,text-info,text-primary',
            ],

            // Campos bloqueados
            'client_id' => [
                'prohibited',
            ],
            'created_by' => [
                'prohibited',
            ],
            'updated_by' => [
                'prohibited',
            ],
            'archived' => [
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
            'type_id.required' => 'O tipo da transação é obrigatório.',
            'type_id.in' => 'O tipo da transação deve ser 1 (recebível) ou 2 (pagável).',
            'description.required' => 'A descrição é obrigatória.',
            'description.max' => 'A descrição não pode ter mais de 500 caracteres.',
            'person_type.required' => 'O tipo de pessoa é obrigatório.',
            'person_type.in' => 'O tipo de pessoa deve ser 1 (individual) ou 2 (empresa).',
            'individual_id.required' => 'A pessoa física é obrigatória quando o tipo de pessoa é individual.',
            'individual_id.exists' => 'A pessoa física selecionada não existe.',
            'company_id.required' => 'A empresa é obrigatória quando o tipo de pessoa é empresa.',
            'company_id.exists' => 'A empresa selecionada não existe.',
            'due_date.required' => 'A data de vencimento é obrigatória.',
            'due_date.after_or_equal' => 'A data de vencimento não pode ser anterior a hoje.',
            'amount.required' => 'O valor é obrigatório.',
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
        // Definir valores padrão
        $defaults = [
            'type_id' => 2, // payable por padrão
            'person_type' => 1, // individual por padrão
            'installment' => 1,
            'total_installments' => 1,
            'transaction_key' => '0',
            'css_class' => 'text-warning',
            'client_id' => Auth::user()->client_id ?? 0,
        ];

        foreach ($defaults as $key => $value) {
            if (!$this->has($key)) {
                $this->merge([$key => $value]);
            }
        }

        // Gerar transaction_key se não fornecida e houver parcelas
        if (!$this->has('transaction_key') || $this->transaction_key === '0') {
            if ($this->total_installments > 1) {
                $this->merge([
                    'transaction_key' => uniqid('TRX_', true),
                ]);
            }
        }

        // Converter valores monetários para formato correto
        foreach (['amount', 'paid_amount'] as $field) {
            if ($this->has($field)) {
                $value = $this->$field;
                // Se for string com vírgula, converter para ponto
                if (is_string($value) && strpos($value, ',') !== false) {
                    $value = str_replace(['.', ','], ['', '.'], $value);
                }
                $this->merge([$field => (float) $value]);
            }
        }

        // Garantir que paid_at seja null se não houver paid_amount
        if ($this->has('paid_amount') && empty($this->paid_amount)) {
            $this->merge([
                'paid_at' => null,
                'paid_amount' => null,
            ]);
        }

        // Se paid_amount for preenchido, garantir que paid_at também seja
        if ($this->has('paid_amount') && $this->paid_amount > 0 && !$this->has('paid_at')) {
            $this->merge([
                'paid_at' => now()->format('Y-m-d'),
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Adicionar audit fields
        if (Auth::check()) {
            $this->merge([
                'created_by' => Auth::id(),
                'client_id' => Auth::user()->client_id,
            ]);
        }

        // Se houver pagamento, atualizar o status automaticamente
        if ($this->has('paid_amount') && $this->paid_amount > 0) {
            // Buscar status "Pago" se não houver status_id definido
            if (!$this->has('status_id') || !$this->status_id) {
                // Aqui você poderia buscar o status "Pago" do banco
                // Por enquanto, vamos deixar o controller tratar isso
            }
        }

        // Garantir que person_type, individual_id e company_id sejam consistentes
        if ($this->person_type == 1) {
            $this->merge(['company_id' => null]);
        } else {
            $this->merge(['individual_id' => null]);
        }

        // Garantir que os valores monetários tenham 2 casas decimais
        foreach (['amount', 'paid_amount'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => round($this->$field, 2)]);
            }
        }
    }
}
