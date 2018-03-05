<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTags extends Model
{
    //
    public $table = 'article_tags';
    public $fillable = [
        'article_id',
        'tag_id'
    ];
}
