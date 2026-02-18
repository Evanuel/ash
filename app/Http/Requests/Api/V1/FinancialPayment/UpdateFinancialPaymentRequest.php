<?php

namespace App\Http\Requests\Api\V1\FinancialPayment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_date' => 'sometimes|date',
            'amount' => 'sometimes|numeric|min:0.01',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
            'bank_id' => 'nullable|exists:banks,id',
            'notes' => 'nullable|string',
            'is_manual' => 'boolean',
        ];
    }
}
