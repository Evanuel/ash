<?php
// app/Http/Requests/Api/V1/StoreCompanyRequest.php

namespace App\Http\Requests\Api\V1\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;

class StoreCompanyRequest extends FormRequest
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

        // Usar PermissionService para verificar permissão
        return app(PermissionService::class)->has($user, 'company.create');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = Auth::user();
        $clientId = $user ? $user->client_id : 0;

        return [
            // Dados obrigatórios
            'client_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],
            'type' => [
                'required',
                'integer',
                'min:1',
                'max:255',
            ],
            'cnpj' => [
                'required',
                'string',
                'size:18',
                'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/',
                Rule::unique('companies', 'cnpj')
                    ->where('client_id', $clientId)
                    ->whereNull('deleted_at'),
            ],
            'trade_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('companies', 'trade_name')
                    ->where('client_id', $clientId)
                    ->whereNull('deleted_at'),
            ],
            'company_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('companies', 'company_name')
                    ->where('client_id', $clientId)
                    ->whereNull('deleted_at'),
            ],
            
            // Registros
            'state_registration' => [
                'nullable',
                'string',
                'max:50',
            ],
            'municipal_registration' => [
                'nullable',
                'string',
                'max:50',
            ],
            
            // Endereço
            'street' => [
                'nullable',
                'string',
                'max:255',
            ],
            'number' => [
                'nullable',
                'string',
                'max:20',
            ],
            'neighborhood' => [
                'nullable',
                'string',
                'max:100',
            ],
            'zip_code' => [
                'nullable',
                'string',
                'size:9',
                'regex:/^\d{5}-\d{3}$/',
            ],
            'state_id' => [
                'nullable',
                'integer',
                'exists:states,id',
            ],
            'city_id' => [
                'nullable',
                'integer',
                'exists:cities,id',
            ],
            
            // Detalhes da empresa
            'logo' => [
                'nullable',
                'string',
                'max:500',
                'url',
            ],
            'cnae' => [
                'nullable',
                'string',
                'max:10',
            ],
            'opening_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            
            // Matriz/Filial
            'is_headquarters' => [
                'nullable',
                'boolean',
            ],
            'headquarters_code' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_branch' => [
                'nullable',
                'boolean',
            ],
            'branch_code' => [
                'nullable',
                'integer',
                'min:0',
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
            
            // Regime tributário
            'tax_regime' => [
                'nullable',
                'integer',
                'in:1,2,3,4,5',
            ],
            
            // Contatos - NOVO FORMATO
            'contacts' => [
                'nullable',
                'array',
            ],
            'contacts.*.name' => [
                'required_with:contacts',
                'string',
                'max:100',
            ],
            'contacts.*.type' => [
                'required_with:contacts',
                'string',
                'in:comercial,financial,technical,administrative,support,marketing,other',
            ],
            'contacts.*.phone' => [
                'required_with:contacts',
                'string',
                'max:20',
                'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/',
            ],
            'contacts.*.email' => [
                'required_with:contacts',
                'string',
                'email',
                'max:255',
            ],
            'contacts.*.note' => [
                'nullable',
                'string',
                'max:500',
            ],
            'contacts.*.is_main' => [
                'boolean',
            ],
            
            // Financeiro
            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],
            'used_credit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],
            
            // Status
            'activated' => [
                'nullable',
                'boolean',
            ],
            'situation' => [
                'nullable',
                'integer',
                'in:1,2,3,4',
            ],
            'status' => [
                'nullable',
                'boolean',
            ],
            
            // Campos customizados
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
            
            // Campos de auditoria
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
            'client_id' => 'ID do Cliente',
            'type' => 'tipo',
            'cnpj' => 'CNPJ',
            'trade_name' => 'nome fantasia',
            'company_name' => 'razão social',
            'state_registration' => 'inscrição estadual',
            'municipal_registration' => 'inscrição municipal',
            'street' => 'rua',
            'number' => 'número',
            'neighborhood' => 'bairro',
            'zip_code' => 'CEP',
            'state_id' => 'estado',
            'city_id' => 'cidade',
            'logo' => 'logotipo',
            'cnae' => 'CNAE',
            'opening_date' => 'data de abertura',
            'is_headquarters' => 'matriz',
            'headquarters_code' => 'código da matriz',
            'is_branch' => 'filial',
            'branch_code' => 'código da filial',
            'category_id' => 'categoria',
            'subcategory_id' => 'subcategoria',
            'tax_regime' => 'regime tributário',
            'contacts' => 'contatos',
            'contacts.*.name' => 'nome do contato',
            'contacts.*.type' => 'tipo do contato',
            'contacts.*.phone' => 'telefone do contato',
            'contacts.*.email' => 'e-mail do contato',
            'contacts.*.note' => 'observação do contato',
            'contacts.*.is_main' => 'contato principal',
            'credit_limit' => 'limite de crédito',
            'used_credit' => 'crédito utilizado',
            'activated' => 'ativado',
            'situation' => 'situação',
            'status' => 'status',
            'notes' => 'observações',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'O ID do cliente é obrigatório.',
            'client_id.integer' => 'O ID do cliente deve ser um número inteiro.',
            'client_id.exists' => 'O ID do cliente selecionado é inválido.',
            'type.required' => 'O tipo é obrigatório.',
            'type.integer' => 'O tipo deve ser um número inteiro.',
            'type.min' => 'O tipo deve ser no mínimo :min.',
            'type.max' => 'O tipo deve ser no máximo :max.',
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.size' => 'O CNPJ deve ter 18 caracteres.',
            'cnpj.regex' => 'O CNPJ deve estar no formato 00.000.000/0000-00.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado.',
            'trade_name.required' => 'O nome fantasia é obrigatório.',
            'trade_name.unique' => 'Este nome fantasia já está em uso.',
            'company_name.required' => 'A razão social é obrigatória.',
            'company_name.unique' => 'Esta razão social já está em uso.',
            'zip_code.size' => 'O CEP deve ter 9 caracteres.',
            'zip_code.regex' => 'O CEP deve estar no formato 00000-000.',
            'state_id.exists' => 'O estado selecionado é inválido.',
            'city_id.exists' => 'A cidade selecionada é inválida.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
            'subcategory_id.exists' => 'A subcategoria selecionada é inválida.',
            'opening_date.before_or_equal' => 'A data de abertura não pode ser futura.',
            'credit_limit.max' => 'O limite de crédito não pode ser superior a 999.999.999.999,99.',
            'used_credit.max' => 'O crédito utilizado não pode ser superior a 999.999.999.999,99.',
            'tax_regime.in' => 'O regime tributário selecionado é inválido.',
            'situation.in' => 'A situação selecionada é inválida.',
            'contacts.*.name.required_with' => 'O nome do contato é obrigatório.',
            'contacts.*.type.required_with' => 'O tipo do contato é obrigatório.',
            'contacts.*.type.in' => 'O tipo do contato deve ser: comercial, financeiro, técnico, administrativo, suporte, marketing ou outro.',
            'contacts.*.phone.required_with' => 'O telefone do contato é obrigatório.',
            'contacts.*.phone.regex' => 'O telefone deve estar em um formato válido.',
            'contacts.*.email.required_with' => 'O e-mail do contato é obrigatório.',
            'contacts.*.email.email' => 'O e-mail do contato deve ser um endereço de e-mail válido.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalizar CNPJ
        if ($this->has('cnpj')) {
            $this->merge([
                'cnpj' => $this->normalizeCnpj($this->cnpj),
            ]);
        }

        // Normalizar CEP
        if ($this->has('zip_code')) {
            $this->merge([
                'zip_code' => $this->normalizeZipCode($this->zip_code),
            ]);
        }

        // Normalizar telefones nos contatos
        if ($this->has('contacts') && is_array($this->contacts)) {
            $contacts = [];
            foreach ($this->contacts as $contact) {
                if (isset($contact['phone'])) {
                    $contact['phone'] = $this->normalizePhone($contact['phone']);
                }
                $contacts[] = $contact;
            }
            $this->merge(['contacts' => $contacts]);
        }

        // Definir valores padrão
        $defaults = [
            'type' => 1,
            'state_registration' => '',
            'municipal_registration' => '',
            'cnae' => '',
            'tax_regime' => 1,
            'credit_limit' => 0,
            'used_credit' => 0,
            'activated' => false,
            'situation' => 1,
            'status' => true,
            'client_id' => Auth::user()->client_id ?? 0,
            'contacts' => [],
        ];

        foreach ($defaults as $key => $value) {
            if (!$this->has($key)) {
                $this->merge([$key => $value]);
            }
        }

        // Capitalizar nomes
        if ($this->has('trade_name')) {
            $this->merge([
                'trade_name' => ucwords(mb_strtolower(trim($this->trade_name))),
            ]);
        }

        if ($this->has('company_name')) {
            $this->merge([
                'company_name' => mb_strtoupper(trim($this->company_name)),
            ]);
        }
    }

    /**
     * Normaliza CNPJ para validação.
     */
    private function normalizeCnpj(string $cnpj): string
    {
        $cleanCnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        if (strlen($cleanCnpj) === 14) {
            return sprintf(
                '%s.%s.%s/%s-%s',
                substr($cleanCnpj, 0, 2),
                substr($cleanCnpj, 2, 3),
                substr($cleanCnpj, 5, 3),
                substr($cleanCnpj, 8, 4),
                substr($cleanCnpj, 12, 2)
            );
        }
        
        return $cnpj;
    }

    /**
     * Normaliza CEP.
     */
    private function normalizeZipCode(string $zipCode): string
    {
        $cleanZipCode = preg_replace('/[^0-9]/', '', $zipCode);
        
        if (strlen($cleanZipCode) === 8) {
            return substr($cleanZipCode, 0, 5) . '-' . substr($cleanZipCode, 5, 3);
        }
        
        return $zipCode;
    }

    /**
     * Normaliza telefone.
     */
    private function normalizePhone(string $phone): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($cleanPhone) === 11) {
            // Formato: (11) 99999-9999
            return '(' . substr($cleanPhone, 0, 2) . ') ' . 
                   substr($cleanPhone, 2, 5) . '-' . 
                   substr($cleanPhone, 7, 4);
        } elseif (strlen($cleanPhone) === 10) {
            // Formato: (11) 9999-9999
            return '(' . substr($cleanPhone, 0, 2) . ') ' . 
                   substr($cleanPhone, 2, 4) . '-' . 
                   substr($cleanPhone, 6, 4);
        }
        
        return $phone;
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
        
        // Garantir que booleanos sejam convertidos
        foreach (['is_headquarters', 'is_branch', 'activated', 'status'] as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => filter_var($this->$field, FILTER_VALIDATE_BOOLEAN),
                ]);
            }
        }
        
        // Garantir que is_main em contatos seja boolean
        if ($this->has('contacts') && is_array($this->contacts)) {
            $contacts = [];
            foreach ($this->contacts as $contact) {
                if (isset($contact['is_main'])) {
                    $contact['is_main'] = filter_var($contact['is_main'], FILTER_VALIDATE_BOOLEAN);
                }
                $contacts[] = $contact;
            }
            $this->merge(['contacts' => json_encode($contacts)]);
        }
    }
}