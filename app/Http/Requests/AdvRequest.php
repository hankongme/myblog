<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\Utils;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\Curl\Util;


class AdvRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return AuthTool::check();
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
            'position_id'   => 'required',
            'image_url'=>'required'
        ];
    }

    public function messages() {
        return [
            'title.required' => '请填写广告名称!',
            'position_id.required'   => '请填写名称!',
            'image_url.required'   => '请上传图片!'

        ];
    }


    /**
     * 验证未通过回调
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors) {
        if ($errors) {
            $error = current($errors);
            return BaseTools::ajaxError($error[0]);
        }

    }


    /**
     * 认证未通过回调
     * @return mixed
     */

    public function forbiddenResponse() {
        return BaseTools::ajaxError('权限认证失败!' );
    }

}
