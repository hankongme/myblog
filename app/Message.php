<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    public $table ='dcnet_message';
    public $fillable = [
        'id',
        'session_id',
        'user_name',
        'user_phone',
        'message_content',
        'is_read',
        'client_ip',
        'created_at',
        'updated_at'
    ];
}
