<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 17:05:40
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-19 11:30:09
 */
namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;

class UserHandleCreateUserRequest extends BaseFormRequest
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
        // ############################################
        // $min $max   需要处理
        // $min = $this->currentPlatformEloq->prize_group_min;
        // $max = $this->currentPlatformEloq->prize_group_max;
        return [
            'username' => 'required|unique:frontend_users',
            'password' => 'required',
            'fund_password' => 'required',
            'is_tester' => 'required|numeric',
            'prize_group' => 'required|numeric|between:' . $min . ',' . $max,
            'type' => 'required|numeric',
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
