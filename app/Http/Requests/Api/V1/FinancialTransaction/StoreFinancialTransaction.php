<?php
// app/Http/Requests/Api/V1/FinancialTransaction/StoreFinancialTransactionRequest.php

namespace App\Http\Requests\Api\V1\FinancialTransaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;

class StoreFinancialTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Usar PermissionService para verificar permissão
        return app(PermissionService::class)->has($user, 'financial-transaction.create');
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
            ],
            // Adicione outras regras de validação conforme necessário
        ];
    }
}
