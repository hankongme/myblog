<?php

namespace App;


use App\Http\Controllers\Tools\BaseTools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class DiyMenu extends Model
{
    protected $table = 'diymenu';

    public $error      = '';
    public $timestamps = false;
    public $fillable   = [ 'pid', 'title', 'keyword','url','is_sohw','sort','wxsys','emoji_code','type'];


    public function save_data($data)
    {

        if ($data['type'] == 'click') {
            if (! $data['keyword']) {
                $this->set_error('请填写触发关键词!');
            }
        }

        if ($data['type'] == 'view') {
            if (!$data['url']) {
                $this->set_error('请填写链接信息!');
            }
        }



        if(BaseTools::checkEmptyString($data['id'])){
            if ($data['pid'] > 0) {
                $count = DiyMenu::where('pid',$data['pid'])->count();
                if ($count == 5) {
                    $this->set_error('二级菜单不能多于5条!');
                }
            } else {
                $count = DiyMenu::where('pid',0)->count();
                if ($count == 3) {
                    $this->set_error('一级菜单不能多于3条!');
                }
            }
            //添加
            self::create($data);
        }else{
            //修改
            self::where('id',$data['id'])->update($data);
        }

        //TODO::
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
