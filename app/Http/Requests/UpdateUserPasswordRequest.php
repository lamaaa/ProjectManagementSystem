<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUserPasswordRequest extends Request
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
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '请填写密码',
            'password.confirmed' => '两次密码不一致',
            'password.min' => '密码长度最少为6位',
        ];
    }
}
