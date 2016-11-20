<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAndUpdateCustomerRequest extends Request
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
            'customerManagers.required' => '请选择客户经理',
        ];
    }
}
