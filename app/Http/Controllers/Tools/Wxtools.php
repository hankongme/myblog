<?php

namespace App\Http\Controllers\Tools;

use App\Repositries\UserRespository;
use App\User;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class Wxtools
{
    public $wechat;
    public $user;
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    public function userWxAuth($request)
    {
        $openid = session('openid');
//        if(Wxtools::isWeiXin()&&!$openid){
        if(!$openid){
            if($request->has('code')){
                $user = $this->userInfoByCode();
                if(!$user->getName()){
                    $user = $this->wechat->user->get($user->id);
                    if(!$user->get('nickname')){
                        return $this->getWeChatAuthUrl(1,$this->getTargetUrl($request));
                    }

                }else{
                    $user = collect($user->original);
                }
                $user_data = User::where('openid',$user->get('openid'))->first();
                if(!$user_data){
                    $user_data = UserTools::createWeChat($user,intval(request('invite_id')));
                }else{
                    $user_data->openid = $user->get('openid')?$user->get('openid'):'';
                    $user_data->nickname = $user->get('nickname')?$user->get('nickname'):'';
                    $user_data->avatar = $user->get('headimgurl')?$user->get('headimgurl'):'';
                    $user_data->is_subscribe = intval($user->get('subscribe'))?1:0;
                    $user_data->save();
                }

                session(['openid'=>$user['openid']]);
                session(['wx_user'=>$user]);
                session(['user'=> $user_data]);

                return redirect($this->getTargetUrl($request));
            }else{
                return $this->getWeChatAuthUrl(0,$this->getTargetUrl($request));
            }
        }
    }

   static function isWeiXin()
    {
        if(!isset($_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') ? true : false;
    }


    public function getWeChatAuthUrl($type = 0,$target_url = '')
    {
        if (Wxtools::isWeiXin() && !session('openid')) {

        }

        $url = $target_url?$target_url:url()->full();
        $scopes = $type?['snsapi_userinfo']:['snsapi_base'];
        return $this->wechat->oauth->scopes($scopes)->redirect($url);
    }

    public function userInfoByCode($refresh=false)
    {
        $user = $this->wechat->oauth->user();
        return $user;
    }



    /**
     * Build the target business url.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getTargetUrl($request)
    {
        $queries = array_except($request->query(), ['code', 'state']);
        return $request->url().(empty($queries) ? '' : '?'.http_build_query($queries));
    }

    /**
     * 微信日志信息
     *
     * @param string $data 接收消息
     * @param string $data_post 发送消息
     * @param boolean $wechat
     * @param string $log_type
     *            日志标识
     */
    static function addWeixinLog($data = '', $data_post = '', $wechat = false, $log_type = '')
    {
        if (env('WX_LOG_START') == 1) {
            $log['cTime'] = time();
            $log['cTime_format'] = date('Y-m-d H:i:s', $log['cTime']);
            $log['data'] = $data ? json_encode($data) : '';
            $log['data_post'] = $data_post ? json_encode($data_post) : '';
            $log['log_type'] = $log_type ? $log_type : ($log['data'] ? '接收消息' : '发送消息');
            DB::table('weixin_log')->insert($log);
        }
    }

}
