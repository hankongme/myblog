<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;
use App\Article;
use App\BaseModel;
use App\Http\Controllers\Controller;
use App\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class ArticleCategoryController extends Controller
{

    public function index($pid = 0)
    {

        $map[0]     = [ 'status', '>=', '0' ];
        $map[1]     = [ 'parent_id', '=', $pid ];

        $search_form = Input::get ();
        foreach ($search_form as $k => $v) {
            if ($v == ''){
                unset($search_form[ $k ]);
            } else{
                $search_form[ $k ] = trim ($v);
            }
        }

        if (isset($search_form[ 'name' ]) && $search_form[ 'name' ]){
            $map[] = [ 'name' , 'like' , '%' . $search_form[ 'name' ] . '%' ];
        }

        if (isset($search_form[ 'status' ]) && ($search_form[ 'status' ]!==10)){
            $map[] = [ 'status' , '=' , $search_form[ 'status' ]];
        }

        //发布时间
        if (isset($search_form[ 'start_time' ]) && $search_form[ 'start_time' ]){
            $map[] = [ 'created_at' , '>' , $search_form[ 'start_time' ] ];
        }
        if (isset($search_form[ 'end_time' ]) && $search_form[ 'end_time' ]){
            $map[] = [ 'created_at' , '<' , $search_form[ 'end_time' ] ];
        }



        $page_count = 10;
        $data       = ArticleCategory::where($map)->orderBy('sort')->orderBy('id')->paginate($page_count);

        $data_list = [ ];

        $page = $data->render();

        if ($data) {
            $data_list = $data->toArray();
        }

        $page_num         = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $data_list['data'] = BaseTools::get_key_list($data_list['data'], $page_num, $page_count);
        $list_map['status']      = [ 0 => '禁用', 1 => '启用' ];
        $data_list['data'] = BaseTools::int_to_string($data_list['data'], $list_map);
        return view('admin.article_category.index')->with([ 'data' => $data_list, 'page' => $page,'search_form'=>$search_form,'pid'=>$pid]);

    }


    public function destory($id)
    {
        $id = intval($id);
        if (!$id) {
            return BaseTools::ajaxError('未找到该信息!', StringConstants::Code_Failed, '未找到该信息!');
        }

        ArticleCategory::where('id', '=', $id)->update(['status'=>-1]);

        //操作日志记录
        ActionLog::actionLog($id);
        return BaseTools::ajaxSuccess('删除成功!');

    }


    /**
     * 创建文章
     * @return mixed
     */
    public function create()
    {

        $data            = BaseModel::getColumnTable('article_category');
        $data['status']  = 1;
        $data['is_must'] = 0;
        $data['sort'] = 10;

        $category = ArticleCategory::where('status',1)->get()->toArray();

        $tree[0] = '顶级分类';
        $tree = array_merge($tree,BaseTools::get_tree($category,0,0));

        $data['submit_url'] = url(__ADMIN_PATH__.'/article_category/store');

        return view('admin.article_category.edit')->with([ 'data' => $data,'category'=>$tree]);
    }




    /**
     * 保存文章
     * @param ArticleRequest $request
     * @return mixed
     */

    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        $model = new ArticleCategory();
        $model->save_data($data);
        if ($model->error) {
            return BaseTools::ajaxError($model->error);
        }
        //操作日志记录
        ActionLog::actionLog('添加分类'.$data['name']);
        return BaseTools::ajaxSuccess('添加成功!');
    }


    /**
     * 修改分类
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {
        $id   = intval($id);
        $data = ArticleCategory::where('id', '=', $id)->first();
        if (!$data) {
            return BaseTools::error('未找到该信息!');
        }
        $data               = $data->toArray();
        $data['submit_url'] = url(__ADMIN_PATH__.'/article_category/update');
        $category = ArticleCategory::where('status',1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = array_merge($tree,BaseTools::get_tree($category,0,0));
        return view('admin.article_category.edit')->with([ 'data' => $data,'category'=>$tree ]);
    }


    public function update(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $question = new ArticleCategory();
        $question->save_data($data);
        if ($question->error) {
            return BaseTools::ajaxError($question->error);
        }

        //操作日志记录
        ActionLog::actionLog($data['id']);
        return BaseTools::ajaxSuccess('修改成功!');

    }



}
