<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Requests\NavStoreRequest;
use App\Repositries\NavRespository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NavController extends Controller
{

    public $nav;

    public function __construct(NavRespository $navRespository)
    {
        $this->nav = $navRespository;
    }

    public function index(Request $request)
    {
        $data = $this->nav->getNavForSearch($request);
        return view('admin.nav.index')->with($data);
    }


    public function create(){
        $data = BaseModel::getColumnTable($this->nav->table);
        $data['sort']   =   50;
        $data['submit_url'] = url(__ADMIN_PATH__.'/nav');
        return view('admin.nav.edit')->with(['data'=>$data]);
    }

    public function store(NavStoreRequest $request){
        $data = $this->nav->store($request->all());
        return BaseTools::ajaxSuccess('添加成功!');
    }

    public function update(NavStoreRequest $request,$id){
        $data = $this->nav->store($request->all(),$id);
        if($error = $this->nav->error()){
            return BaseTools::ajaxError($error);
        }
        return BaseTools::ajaxSuccess('修改成功!');
    }

    public function edit($id)
    {
        $data = $this->nav->getNavById($id)->toArray();
        if(!$data){
            return BaseTools::error('未找到该菜单!');
        }

        $data['submit_url'] = url(__ADMIN_PATH__."/nav/{$id}");
        return view('admin.nav.edit')->with(['data'=>$data]);
    }


    public function delete($id){
        $this->nav->delete($id);
        return BaseTools::ajaxSuccess('删除成功!');
    }


}
