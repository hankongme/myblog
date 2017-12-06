<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\Category;
use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\BaseTools;
use App\Repositries\CategoryRespository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public $category;


    public function __construct(CategoryRespository $categoryRespository)
    {
        $this->category = $categoryRespository;

    }


    public function index(Request $request,$id=0)
    {
        $data = $this->category->getCategoryForSearch($request,$id);
        $data['pid'] = $id;
        return view('admin.category.index')->with($data);
    }

    public function create()
    {
        $data = BaseModel::getColumnTable($this->category->table);
        $data['submit_url'] = url(__ADMIN_PATH__.'/category');
        $data['status'] = 1;
        $data['sort'] = 100;
        $category = Category::where("status",1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = $tree+BaseTools::get_tree($category,0,0);
        return view('admin.category.edit')->with(['data'=>$data,'category'=>$tree]);
    }
    
    public function store(Request $request)
    {
        $return = $this->category->store($request->all());
        if($error = $this->category->error()){
            return BaseTools::ajaxError($error);
        }
        ActionLog::actionLog($return->id,"添加分类-{$return->name}");
        return BaseTools::ajaxSuccess('添加成功!');

    }

    public function edit($id)
    {
        $data = $this->category->getCategoryById($id)->toArray();
        if(!$data){
            return BaseTools::error('未找到该分类信息!');
        }
        $data['submit_url'] = url(__ADMIN_PATH__.'/category',[$id]);
        $category = Category::where("id",'<>',$id)->where('parent_id','<>',$id)->where("status",1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = $tree+BaseTools::get_tree($category,0,0);
        return view('admin.category.edit')->with(['data'=>$data,'category'=>$tree]);
    }

    public function update(Request $request,$id){
        $data = $request->all();
        unset($data['_token']);
        $return = $this->category->store($data,$id);
        if($error = $this->category->error()){
            return BaseTools::ajaxError($error);
        }
        ActionLog::actionLog($id,"修改分类-{$data['name']}");
        return BaseTools::ajaxSuccess('修改成功!');

    }

    public function selectCategory(Request $request,$id=0){

        $data = $this->category->getCategoryForSearch($request,$id);
        $data['category_id'] = intval($request->get('category_id'));
        $data['id'] = $id;
        return view('admin.category.selectCategory')->with($data);
    }

    public function delete($id)
    {
        $return = $this->category->delete($id);
        if($error = $this->category->error()){
            return BaseTools::ajaxError($error);
        }
        return BaseTools::ajaxSuccess('删除成功!');
    }
}
