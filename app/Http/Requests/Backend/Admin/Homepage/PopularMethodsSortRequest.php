<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:39:41
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:40:01
 */
namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;

class PopularMethodsSortRequest extends BaseFormRequest
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
            'front_id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
            'rearways_id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
            'front_sort' => 'required|numeric|gt:0',
            'rearways_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|numeric|in:1,2',
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