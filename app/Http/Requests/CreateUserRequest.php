<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserRequest extends Request
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
            'username' => 'required|max:255|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '请填写用户名',
            'username.unique' => '该用户名已被注册',
            'username.max:255' => '用户名不能超过255个字符',
            'name.required' => '请填写昵称',
            'name.max:255' => '昵称不能超过255个字符',
            'email.email' => '请填写有效的邮箱地址',
            'email.required' => '请填写邮箱',
            'email.max:255' => '邮箱不能超过255个字符',
            'email.unique' => '该邮箱已被注册',
            'role.required' => '请填写身份',
            'password.required' => '请填写密码',
            'password.confirmed' => '两次密码不一致',
            'password.min:6' => '密码长度最少为6位',
        ];
    }
}
