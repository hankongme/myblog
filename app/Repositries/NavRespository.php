<?php
namespace App\Repositries;

use App\Comment;
use App\Company;
use App\Http\Controllers\Tools\AdminTools;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\RegionTools;
use App\Industry;
use App\Nav;
use App\User;
use Illuminate\Http\Request;

/**
 * Class UserRespository
 *
 * @package App\Repositries
 */
class NavRespository
{
    public $table  = 'nav';
    public $error;

    public function getNavForSearch(Request $request, $id = 0)
    {

        $map[0] = [$this->table . '.status', '>=', 0];

        $search_form = AdminTools::searchFromFormat($request->all());

        $map = $this->getMapBySearch($search_form, $map);

        $page_count = AdminTools::getAdminPageNum();

        $data = Nav::where($map)->select(
                $this->table . '.*'
            )->orderBy('sort','desc')->paginate($page_count);

        $data = AdminTools::dataFormat($data, $search_form, $page_count);

        $data['data']['data'] = BaseTools::int_to_string($data['data']['data'], [
            'status' => [0=>'禁用',1=>'启用'],
            'url_type' => [0=>'内部链接',1=>'外部链接'],
        ]);
        return $data;
    }

    public function getMapBySearch(array $search, array $map = [])
    {
        if (isset($search['name']) && $search['name']) {
            $map[] = [$this->table . '.name', 'like', '%' . $search['name'] . '%'];
        }

        if (isset($search['url']) && $search['url']) {
            $map[] = [$this->table . '.url', 'like', '%' . $search['url'] . '%'];
        }

        if (isset($search['url_type'])&&$search['url_type']!=10) {
            $map[] = [$this->table . '.url_type', '=', $search['url_type']];
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

    public function store($data,$id=0){

        unset($data['_token']);

        $data['status'] = isset($data['status'])?$data['status']:0;
        $data['url_type'] = BaseTools::is_http($data['url'])?1:0;
        $data['sort']  = $data['sort']?$data['sort']:0;

        if($id){
            $menu = $this->getNavById($id);
            if(!$menu){
                return $this->setError('未找到该菜单!');
            }
            return $menu->update($data);
        }else{
            return Nav::create($data);
        }

    }

    public function getNavById($id){
        return Nav::where($this->table.'.id',$id)
                        ->select($this->table.'.*')
                        ->first();
    }

    public function delete($id){
        return Nav::where('id',$id)->delete();
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
