<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    public $table='dcnet_case';
    public $fillable=[
        'title',
        'content',
        'brief',
        'cover_img',
        'banner_img',
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
}
