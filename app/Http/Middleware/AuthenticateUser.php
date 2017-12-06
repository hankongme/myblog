<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\UserTools;
use App\Http\Controllers\Tools\Wxtools;
use Closure;
use EasyWeChat\Foundation\Application;
use Route, URL, Auth;

class AuthenticateUser
{
    private $wechat;

    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(!session('openid')){
            $wx_tools = new Wxtools($this->wechat);
            return $wx_tools->userWxAuth($request);
        }

        define('USER_ID',AuthTool::userId());
        define('USER_LEVEL',AuthTool::userLevel());
        return $next($request);
    }
}
