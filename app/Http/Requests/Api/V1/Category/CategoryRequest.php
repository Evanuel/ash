<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // A autorização é feita no controller
    }

    public function rules(): array
    {
        $rules = [
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,' . $this->route('category')],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:account,product,client,general,financial,transaction,company,person'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['boolean'],
            'client_id' => ['nullable', 'exists:users,id'],
            'metadata' => ['nullable', 'array'],
        ];

        // Para criação, garantir unicidade
        if ($this->isMethod('POST')) {
            $rules['slug'][] = 'unique:categories,slug';
            
            // Regra de unicidade combinada
            $rules['name'][] = function ($attribute, $value, $fail) {
                $exists = \App\Models\Category::where('client_id', $this->client_id)
                    ->where('type', $this->type)
                    ->where('name', $value)
                    ->where('parent_id', $this->parent_id)
                    ->exists();
                
                if ($exists) {
                    $fail('Já existe uma categoria com este nome para este tipo e cliente.');
                }
            };
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da categoria é obrigatório',
            'type.required' => 'O tipo da categoria é obrigatório',
            'type.in' => 'O tipo deve ser: account, product, client, general, financial, transaction, company ou person',
            'parent_id.exists' => 'A categoria pai selecionada não existe',
            'client_id.exists' => 'O cliente selecionado não existe',
        ];
    }
}