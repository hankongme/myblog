<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    public $table = 'tags';
    public $fillable = [
        'name','status','created_at','updated_at'
    ];

    public function article()
    {
        return $this->belongsToMany(Article::class,'article_tags','tag_id','article_id')->withTimestamps();

    }
}
