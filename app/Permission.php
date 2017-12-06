<?php
namespace App;

use Illuminate\Support\Facades\Cache;

class Permission extends BaseModel
{
    protected $fillable = ['name', 'display_name', 'cid', 'description', 'status', 'sort', 'level'];
    protected $table = 'permissions';
    public $error = '';


    /**
     *保存或存储信息
     * @param $data
     * @return bool
     */
    public function save_data($data)
    {
        $data['status'] = isset($data['status']) ? $data['status'] : 1;

        if (!isset($data['id']) || !$data['id']) {
            if ($this->where('name', '=', $data['name'])->first()) {

                $this->error = '该规则已经存在了!';
                return false;

            }
        }


        if ($data['cid']) {
            $cpermission = $this->where('id', '=', $data['cid'])->first();
            if (!$cpermission) {
                $this->error = '该上级权限不存在!';
                return false;
            }


            $data['level'] = $cpermission->level + 1;
        }

        if (!isset($data['id']) || !$data['id']) {
            $data['id'] = null;
            $this->insert($data);

        } else {

            $this->where('id', '=', $data['id'])->update($data);
        }


        return true;

    }


    public function delete_row($id)
    {
        $data['status'] = -1;
        $this->where('id', '=', $id)->update($data);
        return true;
    }

}
