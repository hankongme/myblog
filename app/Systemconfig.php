<?php

namespace App;


use App\Http\Controllers\Tools\Utils;
use Illuminate\Database\Eloquent\Model;


class Systemconfig extends Model
{
    protected $table = 'system_config';
    protected $fillable = ['name', 'title', 'type', 'group', 'sort', 'value', 'extra', 'remark', 'status'];

    public $error = '';

    public function save_data($data)
    {
        if (!$data) {
            return $this->set_error('请上传数据!');
        }


        $data['status'] = 1;

        if (isset($data['id']) && $data['id']) {
            $config = $this->where('name', '=', $data['name'])->select('id','name')->first();
            if ($config && $config->id != $data['id']) {
                return $this->set_error('该配置标识已经存在!');
            }
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $this->where('id', '=', $data['id'])->update($data);


        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $this->create($data);
        }


    }


    /**
     * 批量保存数据
     * @param $data
     */
    public function save_group($data)
    {

        if ($data && is_array($data)) {
            foreach ($data['config'] as $name => $value) {
                $map = ['name' => $name];

                $this->setField($map, 'value', $value);
            }
        }


        return true;

    }


    /**
     * 设置记录的某个字段值
     * 支持使用数据库字段和方法
     * @access public
     * @param string|array $field 字段名
     * @param string $value 字段值
     * @return boolean
     */
    public function setField($map = ['id', '=', 0], $field, $value = '')
    {
        if (is_array($field)) {
            $data = $field;
        } else {
            $data[$field] = $value;
        }
        return $this->where($map)->update($data);
    }


    public function set_error($data)
    {
        $this->error = $data;
        return false;
    }
}
