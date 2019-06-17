<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 14:14:19
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-17 19:33:37
 */
namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseFormRequest;

class PartnerAdminGroupSpecificGroupUsersReques extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_access_groups,id',
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