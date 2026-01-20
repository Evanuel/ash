<?php

namespace App\Http\Requests\Api\V1\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StorePersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = Auth::user();
        
        return [
            'type' => ['required', 'string', 'max:50'],
            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('people')->where(function ($query) use ($user) {
                    return $query->where('client_id', $user->client_id);
                })
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'rg' => ['nullable', 'string', 'max:20'],
            'street' => ['nullable', 'string', 'max:200'],
            'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:9'],
            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'birthdate' => ['nullable', 'date'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:categories,id'],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('people')->where(function ($query) use ($user) {
                    return $query->where('client_id', $user->client_id);
                })
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'used_credit' => ['nullable', 'numeric', 'min:0'],
            'activated' => ['nullable', 'boolean'],
            'situation' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
            'custom_field1' => ['nullable', 'string', 'max:255'],
            'custom_field2' => ['nullable', 'string', 'max:255'],
            'custom_field3' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cpf' => 'CPF',
            'first_name' => 'primeiro nome',
            'last_name' => 'sobrenome',
            'rg' => 'RG',
            'street' => 'rua',
            'number' => 'número',
            'neighborhood' => 'bairro',
            'zip_code' => 'CEP',
            'state_id' => 'estado',
            'city_id' => 'cidade',
            'birthdate' => 'data de nascimento',
            'category_id' => 'categoria',
            'subcategory_id' => 'subcategoria',
            'email' => 'e-mail',
            'phone' => 'telefone',
            'credit_limit' => 'limite de crédito',
            'used_credit' => 'crédito utilizado',
            'activated' => 'ativado',
            'situation' => 'situação',
            'status' => 'status',
            'custom_field1' => 'campo personalizado 1',
            'custom_field2' => 'campo personalizado 2',
            'custom_field3' => 'campo personalizado 3',
            'notes' => 'observações',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'first_name.required' => 'O primeiro nome é obrigatório.',
            'last_name.required' => 'O sobrenome é obrigatório.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'state_id.exists' => 'O estado selecionado é inválido.',
            'city_id.exists' => 'A cidade selecionada é inválida.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
            'subcategory_id.exists' => 'A subcategoria selecionada é inválida.',
            'credit_limit.min' => 'O limite de crédito não pode ser negativo.',
            'used_credit.min' => 'O crédito utilizado não pode ser negativo.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'client_id' => Auth::user()->client_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Normalizar CPF (remover pontos e traço)
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
            ]);
        }

        // Normalizar CEP
        if ($this->has('zip_code')) {
            $this->merge([
                'zip_code' => preg_replace('/[^0-9]/', '', $this->zip_code),
            ]);
        }

        // Normalizar telefone
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^0-9]/', '', $this->phone),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    protected function passedValidation(): void
    {
        // Garantir que used_credit seja 0 se não fornecido
        if (!$this->has('used_credit')) {
            $this->merge(['used_credit' => 0]);
        }

        // Garantir que status seja true se não fornecido
        if (!$this->has('status')) {
            $this->merge(['status' => true]);
        }

        // Garantir que activated seja true se não fornecido
        if (!$this->has('activated')) {
            $this->merge(['activated' => true]);
        }

        // Garantir que archived seja false se não fornecido
        if (!$this->has('archived')) {
            $this->merge(['archived' => false]);
        }
    }
}