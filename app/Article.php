<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        'title',
        'content',
        'content_to_html',
        'brief_to_html',
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
        'is_md',
        'is_top'
    ];
    protected $table = 'article';
    public $error = '';

    public function tags(){
        return $this->belongsToMany(Tags::class,'article_tags','article_id','tag_id')->withTimestamps();
    }

    public function scopePublished($query){
        return $query->where('status',1);
    }


    

}
