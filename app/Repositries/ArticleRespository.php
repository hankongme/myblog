<?php
namespace App\Repositries;
use App\Article;
use App\ArticleCategory;
use App\Comment;
use App\Company;
use App\Http\Controllers\Tools\BaseTools;
use App\Tags;
use App\User;
use Illuminate\Support\Facades\DB;

/**
 * Class CasesRespository
 *
 * @package App\Repositries
 */
class ArticleRespository
{
    public $table = 'article';

    public $error;


    public function getCategory()
    {
        $category = ArticleCategory::join(
            DB::raw("(SELECT COUNT(id) as article_num,category_id from article where status=1 group by category_id) as article_count"),
            'article_count.category_id','=','article_category.id'
        )->where('article_category.status',1)->orderBy('article_category.sort','desc')->orderBy('article_category.created_at','asc')->get()->toArray();

        return $category;
    }

    public function getArticleDate(){
        $date = Article::where('status',1)->select(DB::raw("count(id) as counts"),DB::raw('DATE_FORMAT(created_at,"%Y-%m") as date'))->groupBy(DB::raw('DATE_FORMAT(created_at,"%Y-%m")'))->orderBy("date",'desc')->get()->toArray();
        return $date;
    }

    public function normalizeTag(array $tags){
        return collect($tags)->map(function($tag){
            if(is_numeric($tag)){
                return (int)$tag;
            }
            if($find = Tags::where('name','=',$tag)->first()){
                return $find->id;
            }
            $newTag = Tags::create(['name'=>$tag]);
            return $newTag->id;
        })->toArray();
    }

    public function create(array $attributes)
    {
        return Article::create($attributes);
    }

    public function update(array $data,$id){
        return Article::where('id',$id)->update($data);
    }
    /**
     * 保存或存储信息
     * @param $data
     * @return bool
     */
    public function save_data($data)
    {
        $data['status'] = isset($data['status']) ? $data['status'] : 0;
        $data['is_best'] = isset($data['is_best']) ? $data['is_best'] : 0;
        $data['is_md'] = isset($data['is_md']) ? $data['is_md'] : 0;
        if ($data['category_id']) {
            $category =ArticleCategory::where('id',$data['category_id'])->count();
            if(!$category){
                $this->error = '未找到该分类';
                return false;
            }
        }

        $tags_id = $this->normalizeTag($data['tags']);

        $data['content_to_html'] =$data['is_md']?$data['content-html-code']:$data['content'];
        $data['brief_to_html'] =$data['is_md']?$data['brief-html-code']:$data['brief'];

        unset($data['content-html-code']);
        unset($data['brief-html-code']);
        unset($data['tags']);

        if (!isset($data['id']) || !$data['id']) {
            $article =  $this->create($data);
            $article->tags()->attach($tags_id);
            return $article;
        } else {
            $article = $this->byId($data['id']);
            $article->tags()->sync($tags_id);
            return Article::where('id', '=', $data['id'])->update($data);
        }
        return true;
    }

    public function byId($id){
        return Article::find($id);
    }

    public function delete_row($id)
    {
        $data['status'] = -1;
        $this->where('id', '=', $id)->update($data);
        return true;
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
