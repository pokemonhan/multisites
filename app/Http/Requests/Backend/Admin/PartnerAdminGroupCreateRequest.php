<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 13:58:27
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 13:58:53
 */
namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseFormRequest;

class PartnerAdminGroupCreateRequest extends BaseFormRequest
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
            'group_name' => 'required|unique:backend_admin_access_groups',
            'role' => 'required',
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
