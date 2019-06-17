<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:56:23
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:57:09
 */
namespace App\Http\Requests\Backend\Admin\Notice;

use App\Http\Requests\BaseFormRequest;

class NoticeDeleteRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_message_notices,id',
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