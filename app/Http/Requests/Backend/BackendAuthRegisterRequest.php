<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 11:47:56
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-19 11:48:34
 */
namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseFormRequest;

class BackendAuthRegisterRequest extends BaseFormRequest
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
            'name' => 'required|unique:backend_admin_users',
            'email' => 'required|email|unique:backend_admin_users',
            'password' => 'required',
            'is_test' => 'required|numeric',
            'group_id' => 'required|numeric',
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