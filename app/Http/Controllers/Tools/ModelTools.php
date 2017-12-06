<?php


namespace App\Http\Controllers\Tools;


use Illuminate\Support\Facades\DB;

class ModelTools
{

    /**
     * 去重
     * @param     $model
     * @param     $data
     * @param int $id
     *
     * @return array
     */
    static public function checkUnique($model,$data,$id=0)
    {
        $error = [];

        if(!$data||!is_array($data)){
            return $error;
        }

        $model = DB::table($model)->where('id','!=',$id)->where(function($query)use($data){
            foreach ($data as $k=>$v){
                    $query = $query->orWhere($v[0],$v[1]);
            }
        });

        $model = $model->select()->get();
        if($model){
            foreach ($model as $key=>$item){
                foreach ($data as $k=>$v){
                    $temp = $v[0];
                    if($item->$temp == $v[1]){
                        $error[] = $v[2];
                    }
                }
            }

        }

        return $error;

    }
}
