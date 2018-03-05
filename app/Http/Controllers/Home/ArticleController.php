<?php

namespace App\Http\Controllers\Home;

use App\ArticleCategory;
use App\Article;
use App\Category;
use App\Http\Controllers\Tools\BaseTools;
use App\Repositries\ArticleCategoryRespository;
use App\Repositries\ArticleRespository;
use App\Tags;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class ArticleController extends CommonController
{
    private $article;
    private $article_category;

    public function __construct(ArticleRespository $articleRespository)
    {
        parent::__construct();
        $this->article = $articleRespository;
    }

    public function tagList(Request $request,$tag){
        $page_count = 10;
        //获取首页推荐分类
        $tag = Tags::where('name',htmlspecialchars(BaseTools::compile_str($tag)))->first();
        $tag_id = $tag?$tag->id:0;
        $article = Article::published()->where('article_tags.tag_id',$tag_id)->join('article_tags',function($join){
                $join->on('article.id','=','article_tags.article_id');
        })->with(['tags'=>function($query){
            $query->select('name as tag_name');
        }])->orderBy('article.sort','desc')->orderBy('article.created_at','desc')->orderBy('article.id','desc')->paginate($page_count);
        $page = $article->render('pagination::bootstrap-4');
        if ($article) {
            $data = $article->toArray();
        }
        return view('home.article.tag_list')->with([
                                                  'category'=>$this->article->getCategory(),
                                                  'article_date'=>$this->article->getArticleDate(),
                                                  'data'=>$data['data'],
                                                  'page'=>$page
                                              ]);
    }

    public function categoryList(Request $request,$id,$page_count=10){
        $category = ArticleCategory::where('id',$id)->where('status',1)->first();
        if($category){
            View::share('web_title', $category->name);
            View::share('web_keywords',$category->name);
            View::share('web_description', $category->name);
        }
        $page = [];
        $list = [];
        $map[] = ['status',1];
        $map[] = ['category_id',intval($id)];
        $data = Article::published()->where($map)->with(['tags'=>function($query){
            $query->select('name as tag_name');
        }])->orderBy('is_best','desc')->orderBy('sort','desc')->orderBy('created_at','desc')->orderBy('id','desc')->paginate($page_count);

        if ($data){
            $page = $data->render('pagination::bootstrap-4');
            $list = $data->toArray ();
        }

        return view('home.article.list')->with([
                                                   'data'=>$list['data'],
                                                   'page'=>$page,
                                                   'id'=>$id,
                                                   'category'=>$this->article->getCategory(),
                                                   'article_date'=>$this->article->getArticleDate(),
                                               ]);
    }


    public function dateList(Request $request,$date,$page_count=10){
        $page = [];
        $list = [];
        $map[] = ['status',1];
        $start_time =date('Y-m-01',strtotime($date.'-01'.'00:00:00'));
        $end_time = date('Y-m-01',strtotime($date.'-01'.'00:00:00'.'+1 month'));
        $map[] = ['created_at','>=',$start_time];
        $map[] = ['created_at','<',$end_time];
        $data = Article::published()->where($map)->with(['tags'=>function($query){
            $query->select('name as tag_name');
        }])->orderBy('is_best','desc')->orderBy('sort','desc')->orderBy('created_at','desc')->orderBy('id','desc')->paginate($page_count);

        if ($data){
            $page = $data->render('pagination::bootstrap-4');
            $list = $data->toArray ();
        }

        return view('home.article.list')->with([
                                                   'data'=>$list['data'],
                                                   'page'=>$page,
                                                   'date'=>$date,
                                                   'category'=>$this->article->getCategory(),
                                                   'article_date'=>$this->article->getArticleDate(),
                                               ]);
    }


    public function info($id){
        $data = $this->getInfo($id);
        if(!$data){
            return BaseTools::error('未找到案例!');
        }
        $data['category'] = $this->article->getCategory();
        $data['article_date'] = $this->article->getArticleDate();
        return view('home.article.info')->with($data);
    }


    /**
     * 获取案例详情
     * @param $id
     * @return array
     */
    public function getInfo($id){

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
                                           ])->orderBy('is_top','desc')->orderBy('sort','desc')->orderBy('id','desc')->orderBy('created_at','desc')->first();

            if(!$next_case){
                $next_case = Article::where([
                                                   ['id','!=',$info['id']],
                                                   ['id','!=',$prev_id],
                                                   ['sort','<',$info['sort']],
                                                   ['status','=',1],
                                                   ['is_top','<=',$info['is_top']],
                                                   ['category_id','=',$info['category_id']]
                                               ])->orderBy('is_top','desc')->orderBy('sort','desc')->orderBy('id','desc')->orderBy('created_at','desc')->first();
            }

            if($next_case){
                $next_case = $next_case->toArray();
            }

            $return['info'] = $info;
            $return['prev'] = $prev_case;
            $return['next'] = $next_case;
            $return['web_title'] = $info['title'].'_'.$this->config['WEB_NAME'];
            if($info['description']){
                $return['web_description'] = $info['description'];
            }
            if($info['keywords']){
                $return['web_keywords'] = $info['keywords'];
            }
        }

        return $return;

    }

}
