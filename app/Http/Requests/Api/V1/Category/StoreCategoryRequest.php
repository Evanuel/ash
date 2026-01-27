<?php

namespace App\Http\Requests\Api\V1\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreCategoryRequest extends FormRequest
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
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                // Unicidade combinada: mesmo cliente, tipo, nome e pai
                function ($attribute, $value, $fail) use ($user) {
                    $exists = \App\Models\Category::where('client_id', $user->client_id)
                        ->where('type', $this->type ?? 'general')
                        ->where('name', $value)
                        ->where('parent_id', $this->parent_id)
                        ->exists();

                    if ($exists) {
                        $fail('Já existe uma categoria com este nome para este tipo e contexto.');
                    }
                }
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($user) {
                    return $query->where('client_id', $user->client_id);
                })
            ],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:account,product,client,general,financial,transaction,company,person'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'parent_id' => 'categoria pai',
            'name' => 'nome',
            'slug' => 'slug',
            'description' => 'descrição',
            'type' => 'tipo',
            'order' => 'ordem',
            'active' => 'ativo',
            'metadata' => 'metadados',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da categoria é obrigatório',
            'type.required' => 'O tipo da categoria é obrigatório',
            'type.in' => 'O tipo deve ser um dos valores permitidos',
            'parent_id.exists' => 'A categoria pai selecionada não existe',
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

        if (!$this->has('slug') && $this->has('name')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    protected function passedValidation(): void
    {
        if (!$this->has('active')) {
            $this->merge(['active' => true]);
        }

        if (!$this->has('order')) {
            $this->merge(['order' => 0]);
        }
    }
}
