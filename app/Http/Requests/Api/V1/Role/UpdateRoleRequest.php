<?php

namespace App\Http\Requests\Api\V1\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = Auth::user();
        $roleId = $this->route('id');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($user) {
                    return $query->where('client_id', $user->client_id);
                })->ignore($roleId)
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'level' => ['nullable', 'integer', 'min:0', 'max:200'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
            'active' => ['nullable', 'boolean'],
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
            'name' => 'nome da role',
            'description' => 'descrição',
            'level' => 'nível de acesso',
            'permissions' => 'permissões',
            'active' => 'ativo',
        ];
    }
}
