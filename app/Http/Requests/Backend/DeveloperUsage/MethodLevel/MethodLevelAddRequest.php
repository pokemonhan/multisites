<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 16:02:58
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 17:19:53
 */
namespace App\Http\Requests\Backend\DeveloperUsage\MethodLevel;

use App\Http\Requests\BaseFormRequest;

class MethodLevelAddRequest extends BaseFormRequest
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
            'method_id' => 'required|string|exists:lottery_methods,id',
            'level' => 'required|numeric|gt:0|lt:11',
            'position' => 'required|string',
            'count' => 'required|numeric',
            'prize' => 'required|numeric',
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
