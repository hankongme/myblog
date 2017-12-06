<?php

namespace App;

use App\Http\Controllers\Tools\BaseTools;
use Illuminate\Database\Eloquent\Model;

class AdvPosition extends Model
{
    public $table = 'dcnet_adposition';
    public $fillable   = ['pname', 'created_at', 'varname','info',];


    public function save_data($data)
    {
        if (!$data) {
            return $this->set_error('请上传数据!');
        }

        if (isset($data['id']) && $data['id']) {

            $this->where('id', '=', $data['id'])->update($data);

            $result['id'] = $data['id'];

        } else {

            $data['created_at'] = BaseTools::getTime();
            $result             = $this->create($data)->toArray();

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
    public function setField($map = [ 'id', '=', 0 ], $field, $value = '')
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
