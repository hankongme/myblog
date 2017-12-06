<?php

namespace App\Http\Controllers\Admin;

use App\Goods;
use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Codes\CodesStatus;
use App\Http\Controllers\Tools\BaseTools;
use App\AdminUser;
use App\Order;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;



class IndexController extends Controller
{


    /**
     * 首页
     *
     * @return mixed
     */
    public function index()
    {
        $data = AdminUser::getAdminInfo();
        return view('admin.index.index')->with(['adminInfo' => $data, 'MenuData' => AuthTool::get_menu()]);
    }

    /**
     * content欢迎页部分
     *
     * @return mixed
     */
    public function content()
    {
        //TODO::暂时用 欢迎使用
        return view('admin.index.welcome');
    }

    public function welcome()
    {
        if (AuthTool::id() == 1) {
            return view('admin.index.welcome');
        } else {
            return view('admin.index.welcome');
        }
    }


    /**
     * 管理员修改密码
     */
    public function rePass()
    {
        $input = Input::all();
        if (isset($input['password']) && $input['password']) {

            if (!$input['password']) {
                return BaseTools::jsonReturn('请输入密码!', CodesStatus::Code_Failed, '请输入密码!');
            }

            if ($input['repass'] !== $input['password']) {
                return BaseTools::jsonReturn('确认密码不正确!', CodesStatus::Code_Failed, '确认密码不正确!');
            }

            $adminuser = AdminUser::getAdminInfo(intval($input['id']));


            $sql_data['password']  = Hash::make($input['repass']);
            $sql_data['api_token'] = md5(time()) . md5($sql_data['password']);

            if (AdminUser::where('id', '=', AuthTool::id())->update($sql_data)) {

                return BaseTools::ajaxSuccess('密码修改成功');

            } else {
                return BaseTools::ajaxError('密码修改失败');

            }


        } else {
            if ($_POST) {
                return BaseTools::ajaxError('请输入密码!');
            }

            $id                      = intval(AuthTool::id());
            $adminuser               = AdminUser::getAdminInfo($id);
            $adminuser['submit_url'] = url(__ADMIN_PATH__ . '/index/repass');
            if (!$adminuser) {
                $data = '未找到该管理员!';
                return view('admin.common.error')->with(['data' => $data]);
            } else {
                return view('admin.index.repass')->with(['data' => $adminuser]);
            }
        }
    }


    //管理员登出
    public function logout()
    {

        return AuthTool::signOut();

    }


}
