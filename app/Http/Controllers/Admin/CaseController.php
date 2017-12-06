<?php

namespace App\Http\Controllers\Admin;

use App\Cases;
use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;


use App\Http\Requests\CaseRequest;
use App\BaseModel;
use App\Http\Controllers\Controller;
use App\CaseCategory;
use App\Repositries\CasesCategoryRespository;
use App\Repositries\CasesRespository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class CaseController extends Controller
{

    public $case;
    public $case_category;

    public function __construct(CasesRespository $case,CasesCategoryRespository $casesCategoryRespository)
    {
        $this->case = $case;
        $this->case_category = $casesCategoryRespository;
    }

    public function index(Request $request)
    {
        $data = $this->case->getListForSearch($request);
        return view('admin.case.index')->with(['data' => $data['data'], 'page' => $data['page'], 'search_form' => $data['search_form']]);

    }

    public function category(Request $request,$pid=0){
        $pid = intval($pid);
        $data = $this->case_category->getListForSearch($request,$pid);
        return view('admin.case_category.index')->with(['data' => $data['data'], 'page' => $data['page'], 'search_form' => $data['search_form'],'pid'=>$pid]);
    }

    public function categoryEdit($id)
    {
        $id = intval($id);
        $data = $this->case_category->getOne($id);
        $category = CaseCategory::where("id",'<>',$id)->where("status",1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = array_merge($tree,BaseTools::get_tree($category,0,0));
        $data['submit_url'] = url(__ADMIN_PATH__.'/case_category/update');
        return view('admin.case_category.edit')->with([
            'category'=>$tree,
            'data'=>$data
                                                      ]);
    }

    public function categoryUpdate(Request $request){
        $data = $request->all();
        unset($data['_token']);
        $return = $this->case_category->save_data($data);
        if($error = $this->case_category->error()){
            return BaseTools::ajaxError($error);
        }else{
            return BaseTools::ajaxSuccess("修改成功!");
        }
    }

    public function categoryCreate()
    {
        $data            = BaseModel::getColumnTable($this->case_category->table);
        $data['status']  = 1;
        $data['is_best']  = 0;
        $data['sort'] = 10;
        $category = CaseCategory::where('status',1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = array_merge($tree,BaseTools::get_tree($category,0,0));
        $data['submit_url'] = url(__ADMIN_PATH__.'/case_category/store');
        return view('admin.case_category.edit')->with(['data'=>$data,'category'=>$tree]);
    }

    public function categoryStore(Request $request){
        $data = $request->all();
        unset($data['_token']);
        $return = $this->case_category->save_data($data);
        if($error = $this->case_category->error()){
            return BaseTools::ajaxError($error);
        }else{
            return BaseTools::ajaxSuccess("添加成功!");
        }
    }

    public function destory($id)
    {
        $id = intval($id);
        if (!$id) {
            return BaseTools::ajaxError("未找到该信息!");
        }

        Cases::where('id', '=', $id)->update(['status' => -1]);

        //操作日志记录
        ActionLog::actionLog($id);
        return BaseTools::ajaxError("删除成功!");

    }


    /**
     * 创建案例
     *
     * @return mixed
     */
    public function create()
    {

        $data            = BaseModel::getColumnTable($this->case->table);
        $data['status']  = 1;
        $data['is_must'] = 0;
        $data['sort']    = 10;
        $data['is_best'] = 0;
        $data['is_top']  = 0;

        $category           = CaseCategory::where('status', 1)->get()->toArray();
        $tree               = BaseTools::get_tree($category, 0, 0);
        $data['submit_url'] = url(__ADMIN_PATH__ . '/case/store');

        return view('admin.case.edit')->with(['data' => $data, 'category' => $tree]);
    }

    /**
     * 保存案例
     *
     * @param CaseRequest $request
     *
     * @return mixed
     */

    public function store(CaseRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $this->case->save_data($data);
        if ($error = $this->case->error()) {
            return BaseTools::ajaxError($error);
        }
        //操作日志记录
        ActionLog::actionLog('添加案例' . $data['title']);
        return BaseTools::ajaxSuccess('添加成功!');
    }


    /**
     * 修改问题
     *
     * @param int $id
     *
     * @return mixed
     */
    public function edit($id = 0)
    {
        $id   = intval($id);
        $data = Cases::where('id', '=', $id)->first();
        if (!$data) {
            return BaseTools::error('未找到该信息!');
        }
        $data               = $data->toArray();
        $data['submit_url'] = url(__ADMIN_PATH__ . '/case/update');
        $category           = CaseCategory::where('status', 1)->get()->toArray();
        $tree               = BaseTools::get_tree($category, 0, 0);
        return view('admin.case.edit')->with(['data' => $data, 'category' => $tree]);
    }


    public function update(CaseRequest $request,$id=0)
    {
        $data = $request->all();
        unset($data['_token']);
        if(!$id){
            $id = $data['id'];
        }
        $this->case->save_data($data,$id);
        if ($error = $this->case->error()) {
            return BaseTools::ajaxError($error);
        }

        //操作日志记录
        ActionLog::actionLog($data['id']);
        return BaseTools::ajaxSuccess('修改成功!');

    }


}
