<?php

namespace App\Http\Resources\Api\V1;

use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreCompanyRequest extends FormRequest
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

        return app(PermissionService::class)
            ->has($user, 'create.company');
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        $user = Auth::user();
        $clientId = $user ? $user->client_id : 0;

        return [
            'client_id' => [
                'required',
                'integer',
            ],
            'type' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_.-]+$/',
            ],
            'cnpj' => [
                'required',
                'string',
                'min:18',
                'max:18',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                'unique:users,username,NULL,id,client_id,' . $clientId,
            ],
            'trade_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                'unique:users,username,NULL,id,client_id,' . $clientId,
            ],
            'company_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                'unique:users,username,NULL,id,client_id,' . $clientId,
            ],
            'state_registration' => [],
            'municipal_registration' => [],
            'street' => [],
            'number' => [],
            'neighborhood' => [],
            'zip_code' => [],
            'state_id' => [],
            'city_id' => [],
            'logo' => [],
            'cnae' => [],
            'opening_date' => [],
            'is_headquarters' => [],
            'headquarters_code' => [],
            'is_branch' => [],
            'branch_code' => [],
            'category_id' => [],
            'subcategory_id' => [],
            'tax_regime' => [],
            'contacts' => [],
            'credit_limit' => [],
            'used_credit' => [],
            'activated' => [],
            'situation' => [],
            'status' => [],
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
            'created_by' => [],
            'updated_by' => [],
            'archived' => [],
            'archived_by' => [],
            'archived_at' => [],
        ];
    }
}
