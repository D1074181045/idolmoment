<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'username' => ['required', 'regex:/^[0-9A-Za-z]+$/', 'string', 'min:5', 'max:15'],
            'password' => ['required', 'regex:/^[0-9A-Za-z]+$/', 'string', 'min:8', 'max:32'],
            'password_confirm' => ['required', 'pwd_equal:password']
        ];
    }

    public function messages()
    {
        return [
            //
            'username.required' => '錯誤：使用者名稱為必填',
            'password.required' => '錯誤：密碼為必填',
            'username.min' => '錯誤：使用者名稱必須介於5~15字元之間',
            'password.min' => '錯誤：密碼必須介於8~32字元之間',
            'username.max' => '錯誤：使用者名稱必須介於5~15字元之間',
            'password.max' => '錯誤：密碼必須介於8~32字元之間',
            'password_confirm.required' => '錯誤：二次密碼不能為空',
            'password_confirm.pwd_equal' => '錯誤：二次密碼需相同',
            'username.regex' => '錯誤：使用者名稱中含有特殊字元',
            'password.regex' => '錯誤：密碼中含有特殊字元',
        ];
    }
}
