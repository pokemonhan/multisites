<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-13 21:16:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-13 21:17:00
 */
namespace App\Http\Requests\Backend\Admin\FundOperate;

use App\Http\Requests\BaseFormRequest;

class BankEditBankRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_system_banks,id',
            'title' => 'required|string',
            'code' => 'required|alpha',
            'status' => 'required|in:0,1',
            'min_recharge' => 'required|numeric',
            'max_recharge' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'max_withdraw' => 'required|numeric',
            'remarks' => 'required|string',
            'allow_user_level' => 'required|string',
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