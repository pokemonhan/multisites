<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-13 20:47:22
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-15 15:56:40
 */
namespace App\Http\Requests\Backend\Admin\Article;

use App\Http\Requests\BaseFormRequest;

class ArticlesTopRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_message_articles,id',
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