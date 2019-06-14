<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 14:04:33
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 14:12:17
 */
namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseFormRequest;

class PartnerAdminGroupDestroyRequest extends BaseFormRequest
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
            'group_name' => 'required|exists:backend_admin_access_groups,group_name',
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
