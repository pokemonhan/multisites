<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 11:14:51
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-19 11:21:33
 */
namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesIssueHistoryRequest extends BaseFormRequest
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
            'lottery_sign' => 'required|string|min:4|max:10|exists:lottery_lists,en_name',
            'count' => 'integer',
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
