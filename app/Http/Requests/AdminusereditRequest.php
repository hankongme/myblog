<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class AdminusereditRequest extends Request
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
            'account' => 'required',
            'truename'=>'required',
            'email'=>'email'
        ];
    }

    public function messages()
    {
        return [
            'account.required' => '用户账号不能为空!',
            'truename.required'=>'真实姓名不能为空!',
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
