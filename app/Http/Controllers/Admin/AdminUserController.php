<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Codes\CodesStatus;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\AdminusereditRequest;
use App\Http\Requests\AdminuserRequest;
use App\AdminUser;
use App\BaseModel;
use App\Http\Controllers\Controller;
use App\Role;
use App\RoleUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;


class AdminUserController extends Controller
{

    public function index()
    {
        //获取所有管理员信息
        $map[] = ['status', '>=', '0'];
        $data = AdminUser::where('status', '>=', '0')->paginate(10);
        $page = $data->render();
        $data = $data->toArray();
        $data['data'] = BaseTools::int_to_string($data['data'], array('status' => array('0' => '禁用', 1 => '正常')));
        return view('admin.adminuser.index')->with(['data' => $data, 'page' => $page]);

    }

    /**
     * 删除管理员
     * @param $id
     *
     * @return mixed
     */
    public function destory($id)
    {
        if ($id == 1) {
            return BaseTools::ajaxError('该管理员不能删除');
        }

        if (AdminUser::deleterow($id)) {
            //记录操作日志
            ActionLog::actionLog($id);
            return BaseTools::ajaxSuccess('删除成功');

        } else {
            return BaseTools::ajaxError('删除失败');

        }


    }

    public function edit($id)
    {
        $id = intval($id);
        $data = AdminUser::where('id', '=', $id)->first()->toArray();
        if (!$data) {
            return BaseTools::error('该管理员信息不存在!');
        }
        $data['submit_url'] = url(__ADMIN_PATH__.'/admin_user/update');
        return view('admin.adminuser.edit')->with(['data' => $data]);
    }


    public function update(AdminusereditRequest $request)
    {

        $data = $request->all();
        unset($data['_token']);
        $model = new AdminUser();
        $model->save_data($data);

        if ($model->error) {

            return BaseTools::jsonReturn($model->error, CodesStatus::Code_Failed, $model->error);
        } else {
            //记录操作日志
            ActionLog::actionLog($data['id']);
            return BaseTools::jsonReturn('修改成功!', CodesStatus::Code_Succeed, '修改成功');
        }

    }


    public function store(AdminuserRequest $request)
    {

        $data = $request->all();
        unset($data['_token']);
        $model = new AdminUser();
        $model->save_data($data);
        if ($model->error) {

            return BaseTools::jsonReturn($model->error, CodesStatus::Code_Failed, $model->error);
        } else {
            //记录操作日志
            ActionLog::actionLog();
            return BaseTools::jsonReturn('添加成功!', CodesStatus::Code_Succeed, '添加成功');
        }

    }


    public function admin_group($id = 0)
    {


        $input = Input::all();
        if (!$input) {

            $map[0] = ['id', '=', $id];
            $map[1] = ['status', '>=', 0];
            $data = AdminUser::where($map)->first();
            if (!$data) {
                return BaseTools::error('未找到该用户!');
            }

            $data = $data->toArray();
            $data['role_id'] = 1;
            $role = RoleUser::where('user_id', '=', $id)->first();
            if ($role) {
                $data['role_id'] = $role->role_id;
            }
            $role_list_data = [];
            $role_list = Role::where('status', '=', 1)->get();
            if ($role_list) {
                $role_list = $role_list->toArray();
                foreach ($role_list as $k => $v) {
                    $role_list_data[$v['id']] = $v['display_name'];
                }
                $data['submit_url'] = url(__ADMIN_PATH__.'/admin_user/group');
                return view('admin.adminuser.group')->with(['data' => $data, 'role' => $role_list_data]);


            } else {
                return BaseTools::error('请先添加用户组!');
            }

        } else {

            if (!isset($input['id']) || !$input['id']) {
                return BaseTools::jsonReturn('请选择需要修改的管理员!', CodesStatus::Code_Failed, '请选择需要修改的管理员!');
            }

            if (!isset($input['role_id']) || !$input['role_id']) {
                return BaseTools::jsonReturn('请选择用户组!', CodesStatus::Code_Failed, '请选择请选择用户组!');
            }

            $role = RoleUser::where('user_id', '=', $input['id'])->first();
            if ($role) {
                RoleUser::where('user_id', '=', $input['id'])->update(['role_id' => $input['role_id']]);
            } else {
                RoleUser::insert(['user_id' => $input['id'], 'role_id' => $input['role_id']]);
            }
            //记录操作日志
            ActionLog::actionLog('用户:'.$input['id'].'=>用户组:'.$input['role_id']);
            return BaseTools::jsonReturn('修改成功!', CodesStatus::Code_Succeed, '修改成功!');
        }


    }


    public function create()
    {
        $data = BaseModel::getColumnTable('adminuser');

        $data['submit_url'] = url(__ADMIN_PATH__.'/admin_user/store');

        return view('admin.adminuser.edit')->with(['data' => $data]);
    }

    public function repass($id = '')
    {
        $input = Input::all();
        if (isset($input['password']) && $input['password']) {
            if (!$input['password']) {
                return BaseTools::jsonReturn('请输入密码!', CodesStatus::Code_Failed, '请输入密码!');
            }

            if ($input['repass'] !== $input['password']) {
                return BaseTools::jsonReturn('确认密码不正确!', CodesStatus::Code_Failed, '确认密码不正确!');
            }

            $admin_user = AdminUser::getAdmininfo(intval($input['id']));

            if (!$admin_user) {

                return BaseTools::jsonReturn('未找到该用户!', CodesStatus::Code_Failed, '未找到该用户!');

            }

            $sql_data['password'] = Hash::make($input['repass']);
            $sql_data['api_token'] = md5(time()).md5($sql_data['password']);
          
            if (AdminUser::where('id', '=', $input['id'])->update($sql_data)) {

                //添加操作日志
                ActionLog::actionLog($input['id']);
                return BaseTools::jsonReturn('密码修改成功!', CodesStatus::Code_Succeed, '密码修改成功!');

            } else {
                return BaseTools::jsonReturn('密码修改失败!', CodesStatus::Code_Failed, '密码修改失败!');

            }


        } else {
            if ($_POST){
                return BaseTools::ajaxError('请输入密码!');
            }
            $id = intval($id);
            $admin_user = AdminUser::getAdmininfo($id);
            $admin_user['submit_url'] = url(__ADMIN_PATH__.'/admin_user/repass');
            if (!$admin_user) {
                $data = '未找到该管理员!';
                return view('admin.common.error')->with(['data' => $data]);
                exit();
            } else {


                return view('admin.adminuser.repass')->with(['data' => $admin_user]);
            }
        }
    }


    public function postCreate()
    {

    }


}
