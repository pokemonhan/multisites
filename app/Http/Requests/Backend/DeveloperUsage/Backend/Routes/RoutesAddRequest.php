<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 15:26:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 17:17:46
 */
namespace App\Http\Requests\Backend\DeveloperUsage\Backend\Routes;

use App\Http\Requests\BaseFormRequest;

class RoutesAddRequest extends BaseFormRequest
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
            'route_name' => 'required|string|unique:backend_admin_routes,route_name',
            'controller' => 'required|string',
            'method' => 'required|string',
            'menu_group_id' => 'required|numeric|exists:backend_system_menus,id',
            'title' => 'required|string|unique:backend_admin_routes,title',
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
