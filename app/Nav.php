<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    public $table='nav';
    public $fillable = [
        'name',
        'parent_id',
        'url',
        'url_type',
        'status',
        'enname',
        'class_name',
        'sort',
        'created_at',
        'updated_at'
    ];

}
