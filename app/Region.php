<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $table='dcnet_region';
    public $fillable = [
        'region_id',
        'region_name',
        'parent_id',
        'region_type',
        'is_kaitong',
        'first_char'
    ];
    public $primaryKey = 'region_id';
    public $timestamps = false;
}
