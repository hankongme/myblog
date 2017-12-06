<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Codes\CodesStatus;
use App\Http\Controllers\Tools\BaseTools;

use App\Http\Requests\RoleRequest;
use App\BaseModel;
use App\Permission;
use App\PermissionRole;
use App\Role;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class RoleController extends Controller
{

    public function index()
    {
        //获取所有管理员信息

        $map[] = ['status', '>=', '0'];
        $data = Role::where('status', '>=', '0')->paginate(10);

        if ($data) {
            $page = $data->render();
            $data = $data->toArray();
        }

        $data['data'] = BaseTools::int_to_string($data['data'], array('status' => array('0' => '禁用', 1 => '正常')));
        return view('admin.role.index')->with(['data' => $data, 'page' => $page]);

    }

    /**
     * 删除管理员
     */
    public function destory($id)
    {
        if (!$id) {
            return BaseTools::ajaxError('请选择需要删除的信息');
        }

        $role = new Role();
        $role->deleteRow($id);
        if ($role->error) {
            return BaseTools::ajaxError($role->error);
        } else {
            //操作日志记录
            ActionLog::actionLog($id);
            return BaseTools::ajaxSuccess('删除成功');
        }


    }


    /**
     * 管理员编辑
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        if (!$id) {

            return BaseTools::error('请选择需要修改的信息!');

        }

        $map[0] = ['id', '=', $id];
        $map[1] = ['status', '>=', '0'];

        $data = Role::where($map)->first();

        if (!$data) {

            return BaseTools::error('请选择需要编辑的信息!');

        }

        $data['submit_url'] = url(__ADMIN_PATH__.'/role/update');

        return view('admin.role.edit')->with(['data' => $data]);


    }


    /**
     * 存储创建管理员组
     * @param RoleRequest $request
     * @return mixed
     */
    public function store(RoleRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $model = new Role();
        $model->save_data($data);
        if ($model->error) {
            return BaseTools::ajaxError($model->error);
        } else {
            //操作日志记录
            ActionLog::actionLog($data['display_name']);
            return BaseTools::ajaxSuccess('添加成功!');
        }


    }



    public function update(RoleRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $model = new Role();
        $model->save_data($data);
        if ($model->error) {

            return BaseTools::ajaxError($model->error);
        } else {
            //操作日志记录
            ActionLog::actionLog($data['id']);
            return BaseTools::ajaxSuccess('修改成功');
        }

    }

    /**
     * 创建管理员组
     *
     */
    public function create()
    {
        $data = BaseModel::getColumnTable('roles');


        $data['submit_url'] = url(__ADMIN_PATH__.'/role/store');
        return view('admin.role.edit')->with(['data' => $data]);

    }


    /**
     * 设置组权限
     * @param int $id
     * @return mixed
     */
    public function get_permission($id = 0)
    {
        $input = Input::all();
        if (!$input) {
            if (!$id) {
                return BaseTools::error('请选择需要修改的信息!');
            }

            $map[0] = ['id', '=', $id];
            $map[1] = ['status', '=', 1];
            $data = Role::where($map)->first();
            if (!$data) {
                return BaseTools::error('该管理组不存在!');
            }

            $role = PermissionRole::where('role_id',$id)->get();

            $role_permissions = [];

            if (!empty($role)) {
                foreach ($role as $v) {
                    $role_permissions[] = $v->permission_id;
                }
            }

            $role_permissions = implode(',', $role_permissions);


            $data = $data->toArray();

            //获取所有权限信息

            $permission = [];

            $arr_parent = Permission::where([['status', '=', '1'], ['cid', '=', 0]])->orderBy('sort')->get()->toArray();
            $arr_child = Permission::where([['status', '=', '1'], ['level', '=', 1]])->orderBy('sort')->get()->toArray();
            $arr_last_child = Permission::where([['status', '=', '1'], ['level', '=', 2]])->orderBy('sort')->get()->toArray();

            foreach ($arr_last_child as $k => $v) {
                $arr_last_child_data[$v['cid']][$k] = $v;

            }


            foreach ($arr_child as $k => $v) {
                $v['child'] = isset($arr_last_child_data[$v['id']]) ? $arr_last_child_data[$v['id']] : [];

                $arr_child_data[$v['cid']][$k] = $v;

            }


            foreach ($arr_parent as $k => $v) {

                $arr_parent[$k]['child'] = isset($arr_child_data[$v['id']]) ? $arr_child_data[$v['id']] : [];
            }

            $permission = $arr_parent;
            $data['submit_url'] = url(__ADMIN_PATH__.'/role/permission');

            return view('admin.role.getpermission')->with(['data' => $data, 'permission' => $permission, 'role_permissions' => $role_permissions]);


        } else {
            $id = intval(isset($input['id']) ? $input['id'] : 0);
            if (!isset($input['rules']) || empty($input['rules'])) {
                return BaseTools::ajaxError('请选择需要添加的权限');
            }

            $permission = PermissionRole::where('role_id', '=', $id)->first();



            if ($permission) {
                //如果有权限设置先清空
                PermissionRole::where('role_id', '=', $id)->delete();
            }

            foreach ($input['rules'] as $k => $v) {
                $temp = [];
                $temp['permission_id'] = intval($v);
                $temp['role_id'] = $id;
                $sql_data[] = $temp;

            }

            if (PermissionRole::insert($sql_data)) {
                //操作日志记录
                ActionLog::actionLog($id);
                return BaseTools::ajaxSuccess('权限修改成功');
            } else {
                return BaseTools::ajaxError('权限修改失败');
            }


        }


    }


    public function postCreate()
    {

    }


}
