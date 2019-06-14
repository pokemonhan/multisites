<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 17:59:31
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 18:00:03
 */
namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Config;

class LotteriesGenerateIssueRequest extends BaseFormRequest
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
            'lottery_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d',
            'end_time' => 'required|date_format:Y-m-d',
//            'start_issue' => 'required|numeric',//
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
