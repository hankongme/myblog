<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseCategory extends Model
{

    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'is_best',
        'created_at',
        'updated_at'
    ];

    protected $table = 'dcnet_case_category';

    public $error = '';


}
