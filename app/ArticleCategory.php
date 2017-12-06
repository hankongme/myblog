<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{

    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'sort',
        'created_at',
        'updated_at'
    ];

    protected $table = 'dcnet_article_category';

    public $error = '';


    public static function getOne($id){
        return ArticleCategory::where('id',$id)->first()->toArray();
    }

    /**
     * 保存或存储信息
     * @param $data
     * @return bool
     */
    public function save_data($data)
    {
        $data['status'] = isset($data['status']) ? $data['status'] : 1;

        if ($data['parent_id']) {
            $parent = self::where('id',$data['parent_id'])->count();
            if(!$parent){
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
