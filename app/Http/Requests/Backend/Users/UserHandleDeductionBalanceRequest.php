<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 18:37:44
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 18:38:21
 */
namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;

class UserHandleDeductionBalanceRequest extends BaseFormRequest
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
            'user_id' => 'required|numeric|exists:frontend_users,id',
            'amount' => 'required|numeric',
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