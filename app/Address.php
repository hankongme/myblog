<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $table='dcnet_address';
    public $fillable=[
        'id',
        'user_id',
        'province',
        'city',
        'district',
        'province_name',
        'city_name',
        'district_name',
        'user_name',
        'user_phone',
        'address',
        'created_at',
        'updated_at',
    ];
}
