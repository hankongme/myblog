<?php

namespace App\Http\Requests;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\Utils;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\Curl\Util;


class ArticleRequest extends Request
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
            'category_id' => 'required|integer' ,
            'content'   => 'required'
        ];
    }

    public function messages() {
        return [
            'title.required' => '请填写文章名称!',
            'category_id.required'   => '请选择文章分类!',
            'category_id.integer'   => '请选择文章分类!',
            'content.required'   => '请填写文章内容!',

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
