<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 11:55:30
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-19 11:56:47
 */
namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseFormRequest;

class BackendAuthDeletePartnerAdminRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|numeric|exists:backend_admin_users',
            'name' => 'required|string|exists:backend_admin_users',
        ];
    }

    /*public function messages()
{
return [
'lottery_sign.required' => 'lottery_sign is required!',
'trace_issues.required' => 'trace_issues is required!',
'balls.required' => 'balls is required!'
];
}*/
}
