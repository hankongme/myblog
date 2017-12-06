<?php

namespace App\Repositries;


use App\Company;
use App\Goods;
use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\AdminTools;
use App\Http\Controllers\Tools\BaseTools;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryRespository
{
    public $error;

    public $table = 'dcnet_category';

    public function store($data,$id=0)
    {
        if (BaseTools::checkEmptyString($data['name'])) {
            return $this->setError('名称不能为空');
        }

        $data['level'] = 1;

        if (isset($data['parent_id']) && $data['parent_id']) {
            $parent = $this->getCategoryById($data['parent_id']);
            if (BaseTools::checkEmptyString($parent->status)) {
                return $this->setError('未找到该上级行业或已被禁用!');
            }
            $data['level'] = 2;
        }

        $data['status'] = isset($data['status']) ? $data['status'] : 0;

        if ($id) {
            if($data['parent_id'] == $id){
                return $this->setError('不能设置自己为上级行业!');
            }
            return Category::where('id',$id)->update($data);
        } else {

            return Category::create($data);
        }
    }

    public function getCategoryById($id)
    {

        return Category::where('id', $id)->first();

    }


    public function getCategoryForSearch(Request $request, $id = null)
    {

        $map[0] = [$this->table . '.status', '>=', 0];

        if (!is_null($id)) {
            $map[1] = [$this->table . '.parent_id', '=', $id];
        }

        $search_form = AdminTools::searchFromFormat($request->all());

        $map = $this->getMapBySearch($search_form, $map);

        $page_count = AdminTools::getAdminPageNum();

        $data = Category::where($map)->select()
            ->orderBy($this->table.'.created_at','desc')
            ->paginate($page_count);

        $data = AdminTools::dataFormat($data, $search_form, $page_count);

        $parent = [0 => '顶级分类'];
        $parent = $parent+$this->getCategoryByParentId(0, 1);
        $data['data']['data'] = BaseTools::int_to_string($data['data']['data'], [
            'status'    => [0 => '禁用', 1 => '启用'],
            'parent_id' => $parent
        ]);
        return $data;
    }


    public function getCategoryList(Request $request,$city=0)
    {
        $data = [];
        $company_sql = ' WHERE status=1 ';
        if($city){
            $company_sql.= " AND city={$city} ";
        }
        $industry = Category::where($this->table.'.status','=',1)
                                ->join(DB::raw("(select count(*) as company_count,parent_industry_id from dcnet_company {$company_sql} group by parent_industry_id) as industry_parent"),$this->table.'.id','=','industry_parent.parent_industry_id','left')
                                ->join(DB::raw("(select count(*) as company_count,industry_id from dcnet_company {$company_sql} group by industry_id) as industry"),$this->table.'.id','=','industry.industry_id','left')
                                ->select(
                                    $this->table.'.*',
                                    'industry_parent.company_count as parent_company_count',
                                    'industry.company_count'
                                )
                                ->get()->toArray();
        foreach ($industry as $k=>$v){
            $v['company_count'] = $v['company_count']?$v['company_count']:0;
            $v['parent_company_count'] = $v['parent_company_count']?$v['parent_company_count']:0;
            $data[$v['parent_id']][$v['id']] = $v;
        }

        return $data;
    }


    public function delete($id)
    {
        $category = Category::where('id',$id)->first();
        if(!$category){
            return $this->setError("未找到该分类!");
        }
        //检测分类下有没有子分类
        $child = $this->getCategoryByParentId($id);
        if(!empty($child)){
            return $this->setError('该分类下还有子分类,请先删除子分类!');
        }

        $goods_count = Goods::where('category_id',$id)->count();
        if($goods_count){
            return $this->setError('该分类下还有商品');
        }
        ActionLog::actionLog($id,"删除分类-{$category->name}");
        return Category::where('id',$id)->delete();
    }
    
    /**
     * 获取子分类ID(默认获取顶级分类ID)
     *
     * @param int $id
     * @param int $format 0:直接输出;1:id=>name;2:id=>array()
     *
     * @return array
     */
    public function getCategoryByParentId($id = 0, $format = 0)
    {
        $data = [];
        $category = Category::where('parent_id', $id)->get()->toArray();
        if ($format && $category) {
            if ($format == 1) {
                foreach ($category as $k => $v) {
                    $data[$v['id']] = $v['name'];
                }
            } else {
                foreach ($category as $k => $v) {
                    $data[$v['id']] = $v;
                }
            }

        } else {
            $data = $category;
        }

        return $data;
    }


    /**
     * 获取同级分类下所有分类
     * @param int $id
     * @param int $format
     *
     * @return array
     */
    public function getCategoryBySameLevel($id=0,$format=0)
    {

        $category = $this->getCategoryById($id);
        if(!$category){
            return [];
        }
        return $this->getCategoryByParentId($category->parent_id,$format);

    }

 
    public function getMapBySearch(array $search, array $map = [])
    {
        if (isset($search['name']) && $search['name']) {
            $map[] = [$this->table . '.name', 'like', '%' . $search['name'] . '%'];
        }

        if (isset($search['status']) && $search['status'] != 10) {
            $map[] = [$this->table . '.status', '=', $search['status']];
        }

        if (isset($search['start_time']) && $search['start_time']) {
            $map[] = [$this->table . '.created_at', '>', $search['start_time']];
        }
        if (isset($search['end_time']) && $search['end_time']) {
            $map[] = [$this->table . '.created_at', '<', $search['end_time']];
        }

        return $map;
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
