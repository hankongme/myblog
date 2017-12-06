<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Codes\CodesStatus;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\PermissionRequest;
use App\BaseModel;
use App\Permission;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


class PermissionController extends Controller
{

    public function index($cid = 0)
    {
        //获取所有权限信息

        $map[]        = [ 'status', '>=', '0' ];
        $map[]        = [ 'cid', '=', intval($cid) ];
        $data         = Permission::where($map)->orderby('sort')->paginate(10);
        $page         = $data->render();
        $data         = $data->toArray();
        $data['cid']  = intval(intval($cid));
        $data['data'] = BaseTools::int_to_string($data['data'], array ( 'status' => array ( '0' => '禁用', 1 => '正常' ) ));

        return view('admin.permission.index')->with([ 'data' => $data, 'page' => $page ]);

    }

    public function trash($cid = 0)
    {
        //获取所有权限信息

        $map[]        = [ 'status', '=', '-1' ];
        $map[]        = [ 'cid', '=', intval($cid) ];
        $data         = Permission::where($map)->orderby('sort')->paginate(10);
        $page         = $data->render();
        $data         = $data->toArray();
        $data['cid']  = intval(intval($cid));
        $data['data'] = BaseTools::int_to_string($data['data'], array ( 'status' => array ( '0' => '禁用', 1 => '正常' ) ));

        return view('admin.permission.index')->with([ 'data' => $data, 'page' => $page ]);

    }


    /**
     * 删除权限
     */
    public function destory($id)
    {
        $id = intval($id);
        if (!$id) {
            return BaseTools::ajaxError('请选择需要删除的权限');
        }

        $permission = new Permission();
        $permission->delete_row($id);
        S('menu_update_time', time());
        //操作日志记录
        ActionLog::actionLog($id);
        return BaseTools::ajaxSuccess('删除成功');

    }

    /**
     * 编辑权限
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {
        $id    = intval($id);
        $map[] = [ 'id', '=', $id ];
        $map[] = [ 'status', '>=', '0' ];
        $data  = Permission::where($map)->first()->toArray();

        if (!$data) {
            return BaseTools::error('该权限不存在!');
        }

        $permission_data = [ ];

        if ($data['cid'] == 0) {
            $permission_data = [ 0 => '顶级权限' ];
        } else {
            $c_permission    = Permission::where('id', '=', $data['cid'])->select('cid')->first();
            $topcid          = $c_permission ? $c_permission->cid : 0;
            $permission_list = Permission::where([ 0 => [ 'cid', '=', $topcid ], 1 => [ 'status', '=', '1' ] ])
                                         ->select('id', 'display_name')->get()->toArray();

            foreach ($permission_list as $k => $v) {
                $permission_data[$v['id']] = $v['display_name'];
            }
        }


        $data['submit_url'] = url(__ADMIN_PATH__.'/permission/update');
        return view('admin.permission.edit')->with([ 'data' => $data, 'permission_data' => $permission_data ]);

    }

    public function update(PermissionRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $permission = new Permission();
        $permission->save_data($data);
        if ($permission->error) {
            return BaseTools::ajaxError($permission->error);

        } else {
            Cache::store('file')->put('menu_update_time', time(), 3600 * 30 * 24);
            //操作日志记录
            ActionLog::actionLog($data['id']);
            return BaseTools::ajaxSuccess('修改成功');
        }

    }


    public function create($cid = 0)
    {

        $data               = BaseModel::getColumnTable('permissions');
        $data['submit_url'] = url(__ADMIN_PATH__.'/permission/store');
        $data['cid']        = intval($cid);
        $data['status']     = 1;
        $data['is_show']    = 1;
        $data['sort']    = 50;
        $permission_data    = [];
        if ($cid == 0) {
            $permission_data = [ 0 => '顶级权限' ];
        } else {
            $c_permission    = Permission::where('id', '=', $cid)->select('cid')->first();
            $topcid          = $c_permission ? $c_permission->cid : 0;
            $permission_list = Permission::where([ 0 => [ 'cid', '=', $topcid ], 1 => [ 'status', '=', '1' ] ])
                                         ->select('id', 'display_name')->get()->toArray();

            foreach ($permission_list as $k => $v) {
                $permission_data[$v['id']] = $v['display_name'];
            }
        }

        return view('admin.permission.edit')->with([ 'data' => $data, 'permission_data' => $permission_data ]);
    }

    /**
     * 权限添加
     * @param PermissionRequest $request
     * @return mixed
     */
    public function store(PermissionRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $permission = new Permission();
        $permission->save_data($data);
        if ($permission->error) {
            return BaseTools::ajaxError($permission->error);
        } else {
            S('menu_update_time',time(),3600 * 30 * 24);
            ActionLog::actionLog($data['name']);
            return BaseTools::ajaxSuccess('添加成功');
        }

    }


}
