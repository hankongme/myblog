<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\BaseTools;
use Germey\Geetest\GeetestCaptcha;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class LoginController extends Controller
{
    use GeetestCaptcha;

    public function index(Request $request)
    {

        $input = $request->all();

        if (!$input) {
            return view('admin.login.login');
        } else {

            if (!BaseTools::geetestValidate($input)) {
                return BaseTools::ajaxError('验证码不正确,请刷新重试!');
            }

            if (AuthTool::attempt(array ( 'account' => $request->get('name'), 'password' => $request->get('pass') ))) {

                ActionLog::actionLog(0, '登录系统!', 'login', 'login', 1);
                return BaseTools::ajaxSuccess('登录成功!',url(__ADMIN_PATH__.'/index'));
            } else {
                return BaseTools::ajaxError('账号密码不正确!');
            }

        }
    }



}
