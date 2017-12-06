<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;
use Closure;
use Route, URL, Auth;

class AuthenticateWeChat
{
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

        

        return $next($request);
    }
}
