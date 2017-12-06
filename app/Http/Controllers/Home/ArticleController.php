<?php

namespace App\Http\Controllers\Home;

use App\ArticleCategory;
use App\Article;
use App\Http\Controllers\Tools\BaseTools;
use App\Repositries\ArticleCategoryRespository;
use App\Repositries\ArticleRespository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends CommonController
{
    private $article;
    private $article_category;

    public function __construct()
    {
        parent::__construct();
    }

    public function lists($id=0,$page_count=9){

        $category = ArticleCategory::where('status',1)
                                    ->orderBy('sort','asc')
                                    ->orderBy('created_at','desc')
                                    ->get()
                                    ->toArray();
        $first_category = $category[0];
        if(!$id){
            $id = $first_category['id'];
        }
        $page = [];
        $list = [];
        $map[0] = ['status',1];
        $map[1] = ['category_id',intval($id)];
        $data = Article::where($map)
                    ->orderBy('is_best','desc')
                    ->orderBy('sort','desc')
                    ->orderBy('created_at','asc')
                    ->paginate($page_count);

        if ($data){
            $page = $data->render ('pagination::home');
            $list = $data->toArray ();
        }

        return view('home.article.list')->with(['data'=>$list,'page'=>$page,'id'=>$id,'category'=>$category]);
    }

    public function info($id){
        $data = $this->caseInfo($id);
        if(!$data){
            return BaseTools::error('未找到案例!');
        }
        return view('home.article.info')->with($data);
    }


    /**
     * 获取案例详情
     * @param $id
     * @return array
     */
    public function caseInfo($id){

        $return  = [];
        $map[0] = ['id','=',intval($id)];
        $map[1] = ['status','=',1];
        $case = Article::where($map)->first();
        if($case){

            $info = $case->toArray();
            //增加浏览量
            Article::where($map)->increment('view_count',1);
            $prev_case = Article::where([
                                               ['id','!=',$info['id']],
                                               ['id','>',$info['id']],
                                               ['sort','=',$info['sort']],
                                               ['is_top','>=',$info['is_top']],
                                               ['status','=',1],
                                               ['category_id','=',$info['category_id']]
                                           ])->orderBy('is_top','asc')->orderBy('id','asc')->first();
            if(!$prev_case){
                $prev_case = Article::where([
                                                   ['id','!=',$info['id']],
                                                   ['sort','>',$info['sort']],
                                                   ['status','=',1],
                                                   ['is_top','>=',$info['is_top']],
                                                   ['category_id','=',$info['category_id']]
                                               ])
                    ->orderBy('is_top','asc')->orderBy('sort','asc')->orderBy('id','asc')->first();
            }

            $prev_id = 0;
            if($prev_case){
                $prev_case = $prev_case->toArray();
                $prev_id = $prev_case['id'];
            }
            $next_case = Article::where([
                                               ['id','!=',$info['id']],
                                               ['id','!=',$prev_id],
                                               ['sort','=',$info['sort']],
                                               ['id','<',$info['id']],
                                               ['status','=',1],
                                               ['is_top','<=',$info['is_top']],
                                               ['category_id','=',$info['category_id']]
                                           ])->orderBy('is_top','desc')->orderBy('sort','desc')->orderBy('id','desc')->first();

            if(!$next_case){
                $next_case = Article::where([
                                                   ['id','!=',$info['id']],
                                                   ['id','!=',$prev_id],
                                                   ['sort','<',$info['sort']],
                                                   ['status','=',1],
                                                   ['is_top','<=',$info['is_top']],
                                                   ['category_id','=',$info['category_id']]
                                               ])->orderBy('is_top','desc')->orderBy('sort','desc')->orderBy('id','desc')->first();
            }

            if($next_case){
                $next_case = $next_case->toArray();
            }

            $return['info'] = $info;
            $return['prev'] = $prev_case;
            $return['next'] = $next_case;
            $return['web_title'] = $info['title'].'|'.$this->config['WEB_NAME'];

        }


        return $return;

    }

}
