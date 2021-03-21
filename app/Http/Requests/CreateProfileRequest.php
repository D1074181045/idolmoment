<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'nickname' => ['required', 'regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u', 'string', 'max:12']
        ];
    }

    public function messages()
    {
        return [
            //
            'nickname.required' => '錯誤：暱稱為空',
            'nickname.regex' => '錯誤：暱稱不能有特殊字元',
            'nickname.max' => '錯誤：暱稱超過12字元'
        ];
    }
}
