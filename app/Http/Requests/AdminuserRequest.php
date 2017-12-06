<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class AdminuserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return AuthTool::check();
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
            'account' => 'required|regex:/^[A-Za-z.0-9_@]+$/',
            'password' => 'required',
            'truename'=>'required',
            'email'=>'email'
        ];
    }

    public function messages()
    {
        return [
            'account.required' => '用户账号不能为空!',
            'account.regex' => '用户账号格式不正确!',
            'truename.required'=>'真实姓名不能为空!',
            'password.required' => '密码不能为空!',
            'email.email'=>'邮箱格式不正确!'
        ];
    }


    /**
     * 验证未通过回调
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors)
    {
        if ($errors) {
            $error = current($errors);
            return BaseTools::ajaxError($error[0]);
        }

    }


    /**
     * 认证未通过回调
     * @return mixed
     */

    public function forbiddenResponse()
    {
        return BaseTools::ajaxError('权限认证失败');
    }

}
