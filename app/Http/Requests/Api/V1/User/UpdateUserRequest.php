<?php
// app/Http/Requests/Api/V1/UpdateUserRequest.php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
            ->has($user, 'edit_user');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = Auth::user();
        $clientId = $user ? $user->client_id : 0;
        $userId = $this->route('id') ?? $this->route('user');

        return [
            'username' => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                Rule::unique('users', 'username')
                    ->ignore($userId)
                    ->where('client_id', $clientId),
            ],
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($userId)
                    ->where('client_id', $clientId),
            ],
            'password' => [
                'sometimes',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'current_password' => [
                'required_with:password',
                'string',
                'current_password:sanctum',
            ],
            'role_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($user, $userId) {
                    if ($user && $value) {
                        $role = \App\Models\Role::find($value);

                        // Não permitir que não-super-admins atribuam roles de super admin
                        if ($role && $role->level >= 100 && !$user->isSuperAdmin()) {
                            $fail('Você não pode atribuir uma role de super administrador.');
                        }

                        // Não permitir que um usuário remova sua própria role de admin
                        if ($userId == $user->id && $user->role_id != $value && $user->isAdmin()) {
                            $fail('Você não pode remover seus próprios privilégios de administrador.');
                        }
                    }
                },
            ],
            'permissions' => [
                'sometimes',
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'string',
                'max:100',
            ],
            'branch_id' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'supervisor_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user, $userId) {
                    if ($user && $value) {
                        // Não permitir que um usuário seja supervisor de si mesmo
                        if ($value == $userId) {
                            $fail('Um usuário não pode ser supervisor de si mesmo.');
                        }

                        // Verificar se o supervisor existe e é do mesmo cliente
                        $supervisor = \App\Models\User::find($value);
                        if ($supervisor && $supervisor->client_id != $user->client_id && !$user->isSuperAdmin()) {
                            $fail('O supervisor deve ser do mesmo cliente.');
                        }
                    }
                },
            ],
            'company_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:companies,id',
            ],
            'people_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:people,id',
            ],
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
            'profile_image' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
                'url',
            ],
            'active' => [
                'sometimes',
                'boolean',
            ],
            'archived' => [
                'sometimes',
                'boolean',
                function ($attribute, $value, $fail) use ($user, $userId) {
                    if ($value && $userId == $user->id) {
                        $fail('Você não pode arquivar sua própria conta.');
                    }
                },
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
            'password' => 'nova senha',
            'current_password' => 'senha atual',
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
            'username.unique' => 'Este nome de usuário já está em uso.',
            'username.regex' => 'O nome de usuário só pode conter letras, números, pontos, hífens e underscores.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'email.email' => 'Por favor, informe um e-mail válido.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
            'current_password.required_with' => 'A senha atual é necessária para alterar a senha.',
            'current_password.current_password' => 'A senha atual está incorreta.',
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

        // Não permitir alterar client_id
        if ($this->has('client_id')) {
            $this->offsetUnset('client_id');
        }

        // Não permitir alterar created_by
        if ($this->has('created_by')) {
            $this->offsetUnset('created_by');
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Adicionar updated_by após validação
        if (Auth::check()) {
            $this->merge([
                'updated_by' => Auth::id(),
            ]);
        }
    }
}
