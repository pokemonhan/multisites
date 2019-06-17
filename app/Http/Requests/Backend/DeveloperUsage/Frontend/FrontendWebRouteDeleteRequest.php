<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 15:54:24
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 17:18:54
 */
namespace App\Http\Requests\Backend\DeveloperUsage\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendWebRouteDeleteRequest extends BaseFormRequest
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
            'id' => 'required|numeric|unique:frontend_web_routes,id',
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