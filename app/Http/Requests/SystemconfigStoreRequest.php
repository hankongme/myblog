<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class SystemconfigStoreRequest extends Request
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
            'name' => 'required|unique:system_config|regex:/^[0-9a-zA-Z_]+$/',
            'title' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入配置标识!',
            'name.regex' => '配置标识格式不正确!',
            'name.unique' => '该配置标识已经存在!',
            'title.required' => '请填写配置标题!'
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
            $error = current(current($errors));
            return BaseTools::ajaxError($error);
        }

    }


    /**
     * 认证未通过回调
     * @return mixed
     */

    public function forbiddenResponse()
    {
        BaseTools::ajaxError('权限认证失败');
    }

}
