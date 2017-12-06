<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;


use App\Http\Requests\ArticleRequest;
use App\Article;
use App\BaseModel;
use App\Http\Controllers\Controller;
use App\ArticleCategory;
use Illuminate\Support\Facades\Input;


class ArticleController extends Controller
{

    public function index()
    {
        $map[0]     = [ 'dcnet_article.status', '>=', '0' ];
        $search_form = Input::get ();
        foreach ($search_form as $k => $v) {
            if ($v == ''){
                unset($search_form[ $k ]);
            } else{
                $search_form[ $k ] = trim ($v);
            }
        }

        if (isset($search_form[ 'title' ]) && $search_form[ 'title' ]){
            $map[] = [ 'dcnet_article.title' , 'like' , '%' . $search_form[ 'title' ] . '%' ];
        }

        if (isset($search_form[ 'status' ]) && ($search_form[ 'status' ]!=10)){
            $map[] = [ 'dcnet_article.status' , '=' , $search_form[ 'status' ]];
        }

        //发布时间
        if (isset($search_form[ 'start_time' ]) && $search_form[ 'start_time' ]){
            $map[] = [ 'dcnet_article.created_at' , '>' , $search_form[ 'start_time' ] ];
        }
        if (isset($search_form[ 'end_time' ]) && $search_form[ 'end_time' ]){
            $map[] = [ 'dcnet_article.created_at' , '<' , $search_form[ 'end_time' ] ];
        }

        $page_count = 10;
        $data       = Article::where($map)->leftjoin('dcnet_article_category','dcnet_article.category_id','=','dcnet_article_category.id')->orderBy('dcnet_article.sort','desc')->orderBy('dcnet_article.id','desc')->select('dcnet_article.*','dcnet_article_category.name as category_name')->paginate($page_count);

        $datalist = [ ];

        $page = $data->render();

        if ($data) {
            $datalist = $data->toArray();

        }

        $page_num         = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $datalist['data'] = BaseTools::get_key_list($datalist['data'], $page_num, $page_count);

        $list_map['status']      = [ 0 => '禁用', 1 => '启用' ];

        $datalist['data'] = BaseTools::int_to_string($datalist['data'], $list_map);

        return view('admin.article.index')->with([ 'data' => $datalist, 'page' => $page,'search_form'=>$search_form]);

    }


    public function destory($id)
    {
        $id = intval($id);
        if (!$id) {
            return BaseTools::jsonResponse('未找到该信息!', StringConstants::Code_Failed, '未找到该信息!');
        }

        Article::where('id', '=', $id)->update(['status'=>-1]);

        //操作日志记录
        ActionLog::actionLog($id);
        return BaseTools::jsonResponse('删除成功!', StringConstants::Code_Succeed, '删除成功!');

    }

    /**
     * 创建文章
     * @return mixed
     */
    public function create()
    {

        $data            = BaseModel::getColumnTable('dcnet_article');
        $data['status']  = 1;
        $data['is_must'] = 0;
        $data['sort'] = 10;
        $data['is_best'] = 1;
        $data['is_top'] = 0;

        $category = ArticleCategory::where('status',1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = array_merge($tree,BaseTools::get_tree($category,0,0));
        $data['submit_url'] = url(__ADMIN_PATH__.'/article/store');

        return view('admin.article.edit')->with([ 'data' => $data,'category'=>$tree]);
    }

    /**
     * 分类树
     * @param $arr
     * @param $pid
     * @param $step
     * @return array
     */
    public function get_tree($arr,$pid,$step){
        global $tree;
        foreach($arr as $key=>$val) {
            if($val['parent_id'] == $pid) {
                $flg = str_repeat('————',$step);
                $val['name'] = $flg.$val['name'];
                $tree[] = $val;
                $this->get_tree($arr , $val['id'] ,$step+1);
            }
        }
        return $tree;
    }


    /**
     * 保存文章
     * @param ArticleRequest $request
     * @return mixed
     */

    public function store(ArticleRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);

        $model = new Article();
        $model->save_data($data);
        if ($model->error) {
            return BaseTools::ajaxError($model->error);
        }
        //操作日志记录
        ActionLog::actionLog('添加文章'.$data['title']);
        return BaseTools::ajaxSuccess('添加成功!');
    }


    /**
     * 修改问题
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {
        $id   = intval($id);
        $data = Article::where('id', '=', $id)->first();
        if (!$data) {
            return BaseTools::error('未找到该信息!');
        }
        $data               = $data->toArray();
        $data['submit_url'] = url(__ADMIN_PATH__.'/article/update');
        $category = ArticleCategory::where('status',1)->get()->toArray();
        $tree[0] = '顶级分类';
        $tree = array_merge($tree,BaseTools::get_tree($category,0,0));
        return view('admin.article.edit')->with([ 'data' => $data,'category'=>$tree ]);
    }


    public function update(ArticleRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $question = new Article();
        $question->save_data($data);
        if ($question->error) {
            return BaseTools::ajaxError($question->error);
        }

        //操作日志记录
        ActionLog::actionLog($data['id']);
        return BaseTools::ajaxSuccess('修改成功!');

    }




}
