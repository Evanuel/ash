<?php

namespace App\Http\Requests\Api\V1\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('id') ?? $this->route('category');

        return [
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => [
                'sometimes',
                'string',
                'max:255',
                // Unicidade combinada para update (excluindo o próprio ID)
                function ($attribute, $value, $fail) use ($user, $categoryId) {
                    $exists = \App\Models\Category::where('client_id', $user->client_id)
                        ->where('type', $this->type ?? $this->category_type_fallback($categoryId))
                        ->where('name', $value)
                        ->where('parent_id', $this->parent_id ?? $this->category_parent_fallback($categoryId))
                        ->where('id', '!=', $categoryId)
                        ->exists();

                    if ($exists) {
                        $fail('Já existe outra categoria com este nome neste contexto.');
                    }
                }
            ],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($user) {
                    return $query->where('client_id', $user->client_id);
                })->ignore($categoryId)
            ],
            'description' => ['nullable', 'string'],
            'type' => ['sometimes', 'string', 'in:account,product,client,general,financial,transaction,company,person'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * Helper para buscar o tipo atual se não enviado no request
     */
    private function category_type_fallback($id)
    {
        return \App\Models\Category::where('id', $id)->value('type');
    }

    /**
     * Helper para buscar o pai atual se não enviado no request
     */
    private function category_parent_fallback($id)
    {
        return \App\Models\Category::where('id', $id)->value('parent_id');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'updated_by' => Auth::id(),
        ]);
    }
}
