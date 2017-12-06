<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class RoleRequest extends Request
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
            'name' => 'required|regex:/^[A-Za-z0-9._]+$/',
            'display_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '管理组标识不能为空!',
            'name.regex' => '管理组标识格式不正确(字母和数字组成)!',

            'display_name.required' => '管理组名称不能为空!'
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
            $error = current($errors['display_name']);
            return BaseTools::ajaxError($error);
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
