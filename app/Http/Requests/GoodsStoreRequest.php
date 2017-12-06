<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\Request;


class GoodsStoreRequest extends Request
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
            'goods_name' => 'required',
            'goods_sn'    => 'regex:/^[a-zA-Z0-9]{10,30}$/',
            'category_id' => 'required',
            'goods_price' => 'required'
       ];
    }

    public function messages()
    {
        return [
            'goods_name.required'  =>  '商品名称不能为空!',
            'goods_sn.regex'  =>  '货号格式不正确!',
            'category_id.required'  =>  '请选择商品分类!',
            'goods_price.required'  =>  '请填写价格!'
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
