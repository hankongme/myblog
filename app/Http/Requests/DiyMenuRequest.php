<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class DiyMenuRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return AuthTool::check ();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            //
            'title' => 'required' ,
            'type'   => 'required',
        ];
    }

    public function messages() {
        return [
            'title.required' => '请填写菜单名称!',
            'type.required'   => '请选择类型!'
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

    public function forbiddenResponse() {
        return BaseTools::ajaxError('权限认证失败');
    }

}
