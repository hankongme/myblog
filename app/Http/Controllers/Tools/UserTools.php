<?php
namespace App\Http\Controllers\Tools;


use App\Order;
use App\RebateLog;
use App\User;

class UserTools
{
   static public function createWeChat($user,$invite_id=0){

       $data['openid'] = $user->get('openid');
       $data['nickname'] = $user->get('nickname');
       $data['user_name'] = $user->get('nickname');
       $data['sex'] = $user->get('sex');
       $data['avatar'] = $user->get('headimgurl');
       $data['parent_id'] = $invite_id;
       $data['is_subscribe'] = intval($user->get('subscribe'))?1:0;
       return User::create($data);

   }

}
