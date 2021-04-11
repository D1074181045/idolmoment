<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string', 'min:8', 'max:32'],
            'new_password' => ['required', 'string', 'min:8', 'max:32', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            //
            'old_password.required' => '錯誤：舊密碼為空',
            'old_password.min' => '錯誤：舊密碼必須介於8~32字元之間',
            'old_password.max' => '錯誤：舊密碼必須介於8~32字元之間',
            'new_password.required' => '錯誤：新密碼為空',
            'new_password.min' => '錯誤：新密碼必須介於8~32字元之間',
            'new_password.max' => '錯誤：新密碼必須介於8~32字元之間',
            'new_password.confirmed' => '錯誤：二次密碼需相同',
        ];
    }
}
