<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 14:30:45
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 10:31:22
 */
namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAuthResetUserPasswordRequest extends BaseFormRequest
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
            // 'id' => 'required|numeric|exists:frontend_users',
            // 'username' => 'required|exists:frontend_users',
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'confirm_password' => 'required|string',
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