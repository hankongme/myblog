<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class PermissionRequest extends Request
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
            'name' => 'required|regex:/^[a-z._0-9]+$/',
            'display_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '规则不能为空!',
            'name.regex' => '规则格式不正确!',
            'display_name.required' => '规则名称不能为空!'
        ];
    }


    public function response(array $errors)
    {
        if ($errors) {
            dd($errors);
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
