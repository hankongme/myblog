<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Tools\AuthTool;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;
use Closure;
use Route, URL, Auth;

class AuthenticateAdmin
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
        //return $next($request);
        if (!AuthTool::check()) {
            return redirect(env('ADMIN_HOST').'/login');
        }



        C(BaseTools::get_system_config());

        if (AuthTool::id() === 1) {
            return $next($request);
        }

        if (!AuthTool::check_auth_admin(Route::currentRouteName())) {
           
            if ($request->ajax() && ($request->getMethod() != 'GET')) {
                return response()->json([
                    'status' => -1,
                    'code' => 403,
                    'msg' => '您没有权限执行此操作'
                ]
                );
            } else {
                return response()->view('common.error', ['data' => '您没有权限执行此操作']);
            }
        }

        return $next($request);
    }
}
