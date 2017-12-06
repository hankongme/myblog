<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class ShopStoreRequest extends Request
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
            'true_name' => 'required|regex:/^[\x{4e00}-\x{9fa5}]{1,10}$/u',
            'wechat' => 'required|regex:/^[_0-9a-zA-Z\-]{1,30}$/u',
            'phone'    => 'required|regex:/^[1][3-9][0-9]{9}$/',
            'user_level'  => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.required'  =>  '手机号码不能为空!',
            'phone.regex'     =>  '手机号码格式不正确!',
            'user_level.required'     =>  '请选择代理等级!',
            'province.required'     =>  '请选择所属省!',
            'city.required'     =>  '请选择所属市!',
            'district.required'     =>  '请选择所属县/区!',
            'address.required'     =>  '请填写详细地址!',
            'true_name.regex'     =>  '姓名格式不正确!',
            'true_name.required'     =>  '请填写姓名!',
            'wechat.regex'     =>  '微信号码格式不正确!',
            'wechat.required'     =>  '请填写微信号!',
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
