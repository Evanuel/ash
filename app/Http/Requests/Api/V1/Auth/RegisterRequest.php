<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            'password_confirmation' => ['required', 'string', 'same:password'],
            'role_id' => ['required', 'exists:roles,id'],
            'client_id' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'O nome de usuário é obrigatório',
            'username.unique' => 'O nome de usuário (username) já está em uso',
            'username.max' => 'O nome de usuário não pode ter mais de 255 caracteres',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Informe um email válido',
            'email.unique' => 'O email já está em uso',
            'password.required' => 'A senha é obrigatória',
            'password_confirmation.required' => 'A confirmação da senha é obrigatória',
            'password_confirmation.same' => 'A confirmação da senha não corresponde à senha',
            'role_id.required' => 'O papel é obrigatório',
            'role_id.exists' => 'O papel selecionado é inválido',
        ];
    }
}
