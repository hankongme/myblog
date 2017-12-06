<?php
namespace App\Repositries;
use App\CaseCategory;
use App\Cases;
use App\Comment;
use App\Company;
use App\Http\Controllers\Tools\AdminTools;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\RegionTools;
use App\User;
use Illuminate\Http\Request;

/**
 * Class CasesRespository
 *
 * @package App\Repositries
 */
class CasesRespository
{
    public $table = 'dcnet_case';

    public $error;

    public function getListForSearch(Request $request,$id=0){
        $map[0] = [$this->table.'.status','>=',0];

        if($id){
            $map[1] = [$this->table.'.category_id','=',intval($id)];
        }

        $search = AdminTools::searchFromFormat($request->all());
        $map = $this->getMapBySearch($search,$map);

        $page_count = AdminTools::getAdminPageNum();

        $data = Cases::where($map)
                        ->leftjoin('dcnet_case_category','dcnet_case.category_id','=','dcnet_case_category.id')
                        ->orderBy('dcnet_case.sort','desc')
                        ->orderBy('dcnet_case.id','desc')
                        ->select('dcnet_case.*','dcnet_case_category.name as category_name')
                        ->paginate($page_count);


        $data = AdminTools::dataFormat($data,$search,$page_count);
        $data['data']['data'] = BaseTools::int_to_string($data['data']['data'],[
            'status'=>[0=>'禁用',1=>'正常']
        ]);
        return $data;
    }

    public function getMapBySearch(array $search,array $map=[])
    {
        if (isset($search[ 'title' ]) && $search[ 'title' ]){
            $map[] = [ $this->table.'.title' , 'like' , '%' . $search[ 'title' ] . '%' ];
        }

        if (isset($search[ 'status' ]) && ($search[ 'status' ]!=10)){
            $map[] = [ $this->table.'.status' , '=' , $search[ 'status' ]];
        }

        //发布时间
        if (isset($search[ 'start_time' ]) && $search[ 'start_time' ]){
            $map[] = [ $this->table.'.created_at' , '>' , $search[ 'start_time' ] ];
        }
        if (isset($search[ 'end_time' ]) && $search[ 'end_time' ]){
            $map[] = [ $this->table.'.created_at' , '<' , $search[ 'end_time' ] ];
        }

        return $map;
    }


    public function save_data($data,$id=0){
        $data['status'] = isset($data['status']) ? $data['status'] : 1;
        if ($data['category_id']) {
            $category = CaseCategory::where('id',$data['category_id'])->count();
            if(!$category){
                $this->error = '未找到该分类';
                return false;
            }
        }
        if (!$id) {
            Cases::create($data);
        } else {
            Cases::where('id', '=', $id)->update($data);
        }

        return true;
    }

    public function setError($error)
    {
        $this->error = $error;
        return false;
    }

    public function error()
    {
        return $this->error;
    }
}
