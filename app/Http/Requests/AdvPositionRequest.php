<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\Utils;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\Curl\Util;


class AdvPositionRequest extends Request
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
            'varname' => 'required' ,
            'pname'   => 'required'
        ];
    }

    public function messages() {
        return [
            'varname.required' => '请填写广告位标识!',
            'pname.required'   => '请填写名称!',

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
