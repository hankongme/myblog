<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends Model
{
    protected $fillable = [ 'permission', 'current_name', 'adminuser_id', 'remark', 'ip', 'ids' ];
    protected $table    = 'admin_action_log';
    public    $error    = '';


}
