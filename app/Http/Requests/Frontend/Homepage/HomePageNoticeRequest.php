<?php

namespace App\Http\Requests\Frontend\Homepage;

use App\Http\Requests\BaseFormRequest;

class HomePageNoticeRequest extends BaseFormRequest
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
            'type' => 'required|integer|exists:frontend_message_notices_contents',
        ];
    }
}
