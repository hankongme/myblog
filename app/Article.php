<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        'title',
        'content',
        'brief',
        'cover_img',
        'status',
        'description',
        'keywords',
        'status',
        'created_at',
        'category_id',
        'updated_at',
        'is_best',
        'is_top'
    ];
    protected $table = 'dcnet_article';
    public $error = '';


    /**
     * 保存或存储信息
     * @param $data
     * @return bool
     */
    public function save_data($data)
    {
        $data['status'] = isset($data['status']) ? $data['status'] : 1;

        if ($data['category_id']) {
            $category =ArticleCategory::where('id',$data['category_id'])->count();
            if(!$category){
                $this->error = '未找到该分类';
                return false;
            }
        }

        if (!isset($data['id']) || !$data['id']) {

            $this->create($data);

        } else {

            $this->where('id', '=', $data['id'])->update($data);
        }


        return true;

    }


    public function delete_row($id)
    {
        $data['status'] = -1;
        $this->where('id', '=', $id)->update($data);
        return true;
    }


    

}
