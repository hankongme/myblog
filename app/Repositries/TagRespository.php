<?php
namespace App\Repositries;
use App\ArticleTags;
use App\CaseCategory;
use App\Cases;
use App\Comment;
use App\Company;
use App\Http\Controllers\Tools\AdminTools;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\RegionTools;
use App\Tags;
use App\User;
use Illuminate\Http\Request;

/**
 * Class CasesRespository
 *
 * @package App\Repositries
 */
class TagRespository
{
    public $table = 'tags';
    public $error;

    public function getListForSearch(Request $request,$id=0){
        $map[0] = [$this->table.'.status','>=',0];
        $search = AdminTools::searchFromFormat($request->all());
        $map = $this->getMapBySearch($search,$map);
        $page_count = AdminTools::getAdminPageNum();
        $data = Tags::where($map)
                        ->select()
                        ->paginate($page_count);
        $data = AdminTools::dataFormat($data,$search,$page_count);
        $data['data']['data'] = BaseTools::int_to_string($data['data']['data'],[
            'status'=>[0=>'禁用',1=>'正常',-1=>'已删除']
        ]);
        return $data;
    }

    public function getMapBySearch(array $search,array $map=[])
    {
        if (isset($search[ 'name' ]) && $search[ 'name' ]){
            $map[] = [ $this->table.'.name' , 'like' , '%' . $search[ 'name' ] . '%' ];
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

    public function find($id)
    {
        return Tags::where('id',$id)->first();
    }

    public function save_data($data,$id=0){

        $data['status'] = isset($data['status']) ? $data['status'] : 1;

        $is_confirm = Tags::where('name','=',$data['name'])->where('id','!=',$id)->first();

        if($is_confirm){

            return $this->setError("该标签已经存在!");
        }

        if (!$id) {
            return Tags::create($data);
        } else {
            return Tags::where('id', '=', $id)->update($data);
        }
        return true;
    }

    public function destory($id){
        Tags::where('id',$id)->delete();
        ArticleTags::where('tag_id',$id)->delete();
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
