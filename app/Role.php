<?php
namespace App;

use App\Http\Controllers\Tools\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{

    public $error;

    /**
     * update
     * @param array $PermissionsId
     */
    public function givePermissionsTo(array $PermissionsId)
    {
        $this->detachPermissions($this->perms);
        $this->attachPermissionToId($PermissionsId);
    }

    /**
     * Attach multiple $PermissionsId to a user
     *
     *
     */
    public function attachPermissionToId($PermissionsId)
    {
        foreach ($PermissionsId as $pid) {
            $this->attachPermission($pid);
        }
    }


    /**
     * 保存数据
     * @param $data
     * @return bool
     */
    public function save_data($data)
    {
        if (!$data) {
            return $this->set_error('请提交相关参数!');
        }

        if (!isset($data['id']) || !$data['id']) {
            //增加数据
            $sql_data['name'] = $data['name'];

            $map[0] = ['name', '=', $data['name']];

            $name = $this->where($map)->select('id', 'status')->first();

            if ($name) {
                if ($name->status == -1) {
                 
                    return $this->set_error('该管理组已被删除,请先恢复');

                } else {

                    return $this->set_error('该标识已经被使用!');
                }

            }

            $sql_data['display_name'] = $data['display_name'];
            $sql_data['description'] = $data['description'];
            $sql_data['updated_at'] = date('Y-m-d H:i:s');
            $sql_data['status'] = isset($data['status']) ? intval($data['status']) : 1;
            $sql_data['created_at'] = date('Y-m-d H:i:s');


            if ($this->insert($sql_data)) {

                return true;

            } else {

                return $this->set_error('添加失败!');

            }
        } else {
            //更新数据
            $sql_data['name'] = $data['name'];
            $sql_data['display_name'] = $data['display_name'];
            $sql_data['description'] = $data['description'];
            $sql_data['updated_at'] = date('Y-m-d H:i:s');
//            $sql_data['status'] = isset($data['status'])?intval($data['status']):1;
            $map[0] = ['name', '=', $data['name']];
            $map[1] = ['status', '>=', 0];
            $name = $this->where($map)->select('id')->first();

            if ($name->id != $data['id']) {

                return $this->set_error('该标识名称已被使用!');

            }

            $map[0] = ['id', '=', $data['id']];
            $this->where($map)->update($sql_data);
            return true;

        }


    }

    public function deleteRow($id)
    {
        $sql_data['status'] = -1;
        if (is_array($id)) {
            $id = implode(',', $id);
            DB::beginTransaction();
            $role_delete = Role::where('id', 'in', $id)->update($sql_data);
            $permission_delete = PermissionRole::where('role_id', 'in', $id)->delete();
            $user_delete = RoleUser::where('role_id', 'in', $id)->delete();
            if ($role_delete) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return $this->set_error('删除失败!');
            }
        } else {
            DB::beginTransaction();
            $role_delete = Role::where('id', '=', $id)->update($sql_data);
            $permission_delete = PermissionRole::where('role_id', '=', $id)->delete();
            $user_delete = RoleUser::where('role_id', '=', $id)->delete();
            if ($role_delete) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return $this->set_error('删除失败!');
            }

        }
    }

    public function set_error($data)
    {
        $this->error = $data;
        return false;
    }
}
