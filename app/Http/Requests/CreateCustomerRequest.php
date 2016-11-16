<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateCustomerRequest extends Request
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
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'phone' => 'required',
            //'source' => 'required',
            'status' => 'required',
            'priority' => 'required|digits:1',
            'customerManagers' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请填写客户姓名',
            'name.max' => '客户姓名不能超过255个字符',
            'company.required' => '请填写客户公司',
            'phone.required' => '请填写联系电话',
            //'source.required' => '请选择客户来源',
            'status.required' => '请选择进度',
            'priority.required' => '请选择优先级',
            'priority.digits' => '请选择优先级',
            'customerManagers.required' => '请选择客户经理',
        ];
    }
}
