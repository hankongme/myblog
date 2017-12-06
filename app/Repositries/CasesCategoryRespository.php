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
class CasesCategoryRespository
{
    public $table = 'dcnet_case_category';

    protected $error = '';

    public function getListForSearch(Request $request, $id = 0)
    {
        $map[0] = [$this->table . '.status', '>=', 0];

        $map[1] = [$this->table . '.parent_id', '=', $id];

        $search_form = AdminTools::searchFromFormat($request->all());

        $map = $this->getMapBySearch($search_form, $map);

        $page_count = AdminTools::getAdminPageNum();

        $data = CaseCategory::where($map)
            ->orderBy('sort')
            ->orderBy('id')
            ->paginate($page_count);


        $data                 = AdminTools::dataFormat($data, $search_form, $page_count);
        $data['data']['data'] = BaseTools::int_to_string($data['data']['data'], [
            'status' => [0 => '禁用', 1 => '正常']
        ]);
        return $data;
    }

    public function getMapBySearch(array $search, array $map = [])
    {
        if (isset($search_form['name']) && $search_form['name']) {
            $map[] = ['name', 'like', '%' . $search_form['name'] . '%'];
        }
        if (isset($search_form['status']) && ($search_form['status'] !== 10)) {
            $map[] = ['status', '=', $search_form['status']];
        }
        //发布时间
        if (isset($search_form['start_time']) && $search_form['start_time']) {
            $map[] = ['created_at', '>', $search_form['start_time']];
        }
        if (isset($search_form['end_time']) && $search_form['end_time']) {
            $map[] = ['created_at', '<', $search_form['end_time']];
        }

        return $map;
    }

    public function getOne($id)
    {
        return CaseCategory::where('id', $id)->first()->toArray();
    }

    /**
     * 保存或存储信息
     *
     * @param $data
     *
     * @return bool
     */
    public function save_data($data)
    {
        $data['status'] = isset($data['status']) ? 1:0;
        $data['is_best'] = isset($data['is_best']) ? 1:0;
        if(BaseTools::checkEmptyString($data['name'])){
            return $this->set_error("请填写分类名称!");
        }
        if ($data['parent_id']) {
            $parent = CaseCategory::where('id', $data['parent_id'])->count();
            if (!$parent) {
                $this->error = '未找到该分类';
                return false;
            }
        }
        if (!isset($data['id']) || !$data['id']) {

            CaseCategory::create($data);

        } else {

            CaseCategory::where('id', '=', $data['id'])->update($data);
        }
        return true;
    }

    public function delete_row($id)
    {
        $data['status'] = -1;
        CaseCategory::where('id', '=', $id)->update($data);
        return true;
    }


    public function set_error($error)
    {
        $this->error = $error;
        return false;
    }

    public function error(){
        return $this->error;
    }
    
}
