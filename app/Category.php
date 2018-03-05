<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table='category';
    public $fillable=[
        'parent_id',
        'name',
        'status',
        'level',
        'is_home',
        'category_img',
        'created_at',
        'updated_at'
    ];


    static public function checkCategory($parent_id,$id)
    {
        return !!self::where('parent_id',$parent_id)->where('id',$id)->first();
    }
}
