<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class AddressStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return AuthTool::checkUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name' => 'required|regex:/^[\x{4e00}-\x{9fa5}a-z0-9A-Z]{1,10}$/u',
            'address'    => 'required|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_——～@\-\~]{1,1000}$/u',
            'user_phone' => 'required|regex:/^[1][3-9][0-9]{9}$/',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required'
       ];
    }

    public function messages()
    {
        return [
            'user_name.required'  =>  '请填写姓名!',
            'user_name.regex'  =>  '姓名格式不正确!',
            'address.required'  =>  '请填写详细地址!',
            'address.regex'  =>  '地址格式不正确(请不要填写特殊字符)!',
            'province.required'  =>  '请选择省!',
            'city.required'  =>  '请选择市!',
            'district.required'  =>  '请选择县/区!',
        ];
    }


    /**
     * 验证未通过回调
     *
     * @param array $errors
     *
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
     *
     * @return mixed
     */

    public function forbiddenResponse()
    {
        return BaseTools::ajaxError('权限认证失败');
    }

}
