<?php
// app/Http/Requests/Api/V1/User/StoreUserRequest.php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
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

        return app(\App\Services\PermissionService::class)
            ->has($user, 'create_user');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = Auth::user();
        $clientId = $user ? $user->client_id : 0;

        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                'unique:users,username,NULL,id,client_id,' . $clientId,
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,NULL,id,client_id,' . $clientId,
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'role_id' => [
                'nullable',
                'integer',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user && $value) {
                        $role = \App\Models\Role::find($value);
                        if ($role && $role->level >= 100 && !$user->isSuperAdmin()) {
                            $fail('Você não pode atribuir uma role de super administrador.');
                        }
                    }
                },
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'string',
                'max:100',
            ],
            'branch_id' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'supervisor_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user && $value && $value == $user->id) {
                        $fail('Um usuário não pode ser supervisor de si mesmo.');
                    }
                },
            ],
            'company_id' => [
                'nullable',
                'integer',
                'exists:companies,id',
            ],
            'people_id' => [
                'nullable',
                'integer',
                'exists:people,id',
            ],
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
            'profile_image' => [
                'nullable',
                'string',
                'max:500',
                'url',
            ],
            'active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'username' => 'nome de usuário',
            'name' => 'nome completo',
            'email' => 'e-mail',
            'password' => 'senha',
            'role_id' => 'cargo',
            'branch_id' => 'filial',
            'supervisor_id' => 'supervisor',
            'company_id' => 'empresa',
            'people_id' => 'pessoa',
            'profile_image' => 'imagem de perfil',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'O nome de usuário é obrigatório.',
            'username.unique' => 'Este nome de usuário já está em uso.',
            'username.regex' => 'O nome de usuário só pode conter letras, números, pontos, hífens e underscores.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'email.email' => 'Por favor, informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
            'role_id.exists' => 'O cargo selecionado é inválido.',
            'supervisor_id.exists' => 'O supervisor selecionado não existe.',
            'company_id.exists' => 'A empresa selecionada não existe.',
            'people_id.exists' => 'A pessoa selecionada não existe.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Transformar email e username para minúsculas
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }

        if ($this->has('username')) {
            $this->merge([
                'username' => strtolower(trim($this->username)),
            ]);
        }

        // Capitalizar nome
        if ($this->has('name')) {
            $this->merge([
                'name' => ucwords(strtolower(trim($this->name))),
            ]);
        }

        // Definir valores padrão
        $this->merge([
            'client_id' => $this->client_id ?? Auth::user()->client_id ?? 0,
            'branch_id' => $this->branch_id ?? 0,
            'active' => $this->active ?? true,
            'archived' => false,
        ]);
    }
}
