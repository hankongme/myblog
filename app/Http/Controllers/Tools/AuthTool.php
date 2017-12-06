<?php

namespace App\Http\Controllers\Tools;

use App\AdminUser;
use App\Agency;
use App\Permission;
use App\PermissionRole;
use App\RoleUser;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;


class AuthTool
{


    /**
     * 检查用户
     *
     * @return bool
     */
    static public function checkUser()
    {
        return !!AuthTool::userId();
    }

    static public function checkCompany()
    {
        $user = self::user();
        return intval($user->user_type) == 1 ? true : false;
    }

    /**
     * 获取用户ID
     *
     * @return mixed
     */
    static public function userId()
    {

        $user = AuthTool::user();
        return $user ? $user->id : false;

    }

    /**
     * 检测是否是代理用户
     * @return bool
     */
    static public function isAgency(){
        return AuthTool::userLevel()>0?true:false;
    }

    /**
     * 检测用户的代理级别
     * @return int
     */
    static public function userLevel(){
        $user = AuthTool::user();
        return $user ? $user->user_level : 0;
    }

    /**
     * 获取微信用户
     *
     * @return mixed
     */
    static public function wxUserInfo()
    {
        return session('wx_user');
    }


    /**
     * 获取微信openid
     *
     * @return mixed
     */
    static public function userOpenId()
    {
        return session('openid');
    }


    /**
     * 获取用户信息
     *
     * @return mixed
     */
    static public function user()
    {
        return session('user');
    }


    /**
     * 用户登出
     *
     * @return mixed
     */
    static public function userLogOut()
    {
        session(['wx_user' => NULL]);
        session(['openid' => NULL]);
        session(['user' => NULL]);
        return redirect(url('/'));
    }


    /**
     * 获取管理员ID
     *
     * @return mixed
     */
    static public function id()
    {
        return session('admin_user_id');
    }


    /**
     * 检查管理员权限
     *
     * @return bool
     */
    static public function check()
    {
        return AuthTool::id() ? true : false;
    }


    /**
     * 管理员登录
     *
     * @param array $data
     *
     * @return bool
     */
    static public function attempt(array $data)
    {
        $user = AdminUser::where('account', $data['account'])->first();
        if ($user && Hash::check($data['password'], $user->password)) {
            session(['admin_user_id' => $user->id]);
            return true;
        } else {
            return false;
        }
    }


    /**
     * 管理员登出
     *
     * @return mixed
     */
    static public function signOut()
    {
        session(['admin_user_id' => NULL]);
        session(['menu_list' => NULL]);
        session(['menu_time' => NULL]);
        session(['auth_list' => NULL]);
        return redirect(url(__ADMIN_PATH__ . '/login'));
    }


    /**
     * 获得管理员信息
     *
     * @return mixed
     */
    static public function AdminUser()
    {
        return AdminUser::where('id', self::id())->first();
    }

    /**
     *
     * 查看管理员权限
     *
     * @param $current
     *
     * @return bool
     */

    static function check_auth_admin($current)
    {
        $auth_list = session('auth_list');
        session(['auth_list' => '']);
        if (!$auth_list || self::check_auth_update()) {
            $permission_list = self::getAuthList();
            $auth_list = [];
            if ($permission_list) {
                foreach ($permission_list as $k => $v) {
                    $auth_list[$k]              = $v['name'];
                    $auth_name_list[$v['name']] = $v['display_name'];
                }
                session(['auth_name_list' => $auth_name_list]);
                session(['auth_list' => $auth_list]);
            }

        }
        //添加无需审核权限
        $auth_list[] = 'admin.index';
        $auth_list[] = 'admin.index.welcome';
        $auth_list[] = 'admin.index.logout';
        $auth_list[] = 'admin.index.repass';
        $auth_list[] = 'admin.ueditor.index';
        $auth_list[] = 'admin.admin.tool.uploadimage';

        if (($auth_list && in_array($current, $auth_list)) || AuthTool::id() == 1) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 检测权限
     *
     * @param $current
     *
     * @return bool
     */
    static function can($current)
    {
        return self::check_auth_admin($current);
    }


    /**
     * 检测权限更新
     */
    static function check_auth_update()
    {
        $menu_time        = session('menu_time');
        $menu_update_time = Cache::store('file')->get('menu_update_time');

        if ($menu_time && $menu_update_time && $menu_time < $menu_update_time) {

            return true;
        } else {
            return false;
        }

    }


    /**
     * 获取菜单
     */
    static function get_menu()
    {
        AuthTool::check();
        $menu_list = session('menu_list');
        if (!$menu_list || self::check_auth_update()) {
            $menu_list = self::get_menu_list();
            session(['menu_list' => $menu_list]);
            session(['menu_time' => time()]);
        }
        return $menu_list;
    }


    /**
     * 获取菜单信息
     *
     * @return array
     */
    static function get_menu_list()
    {
        $id = AuthTool::id();
        if ($id == 1) {
            $map[0]         = ['status', '=', '1'];
            $map[1]         = ['is_show', '=', 1];
            $permission_list = Permission::where($map)->orderby('sort')->get();
            if ($permission_list) {
                $permission_list = $permission_list->toArray();
            }
        } else {

            $admin_user = AdminUser::getAdmininfo($id);

            if (!$admin_user) {
                return false;
            }
            $map[0] = ['role_user.user_id', '=', $id];
            $map[1] = ['permissions.is_show', '=', 1];
            $map[2] = ['permissions.status', '=', 1];
            $map[3] = ['roles.status', '=', 1];
            $permission_list = RoleUser::leftJoin('roles', function ($join) {
                $join->on('role_user.role_id', '=', 'roles.id');
            }
            )->leftjoin('permission_role', function ($join) {
                $join->on('roles.id', '=', 'permission_role.role_id');
            }
            )->leftjoin('permissions', function ($join) {
                $join->on('permissions.id', '=', 'permission_role.permission_id');
            }
            )->select('permissions.*')->where($map)->orderby('permissions.sort')->get();

            if ($permission_list) {
                $permission_list = $permission_list->toArray();
            } else {
                return false;
            }

        }

        $permission_level = [];
        $permission_child = [];
        foreach ($permission_list as $k => $v) {
            $permission_level[$v['level']][$k] = $v;
            $permission_child[$v['cid']][$k]   = $v;
        }

        $permission_parent_data = [];
        if (isset($permission_level[0]) && $permission_level[0]) {
            foreach ($permission_level[0] as $k => $v) {
                $permission_parent_data[$k]          = $v;
                $permission_parent_data[$k]['child'] = isset($permission_child[$v['id']]) ? $permission_child[$v['id']] : [];

            }
        }
        return $permission_parent_data;
    }


    /**
     * 获取管理权限信息
     *
     * @return bool
     */

    static function getAuthList()
    {
        $id = AuthTool::id();
        if (!$id) {
            return false;
        }

        //获取该用户下的角色
        if ($id != 1) {

            $role_list = RoleUser::where('user_id', '=', $id)->select('role_id')->get();

            if (!$role_list) {
                return false;
            }

            //获取该角色下的权限,并以字符串形式返回
            $role_list = $role_list->toArray();
            $role_ids  = [];
            foreach ($role_list as $k => $v) {
                $role_ids[$k] = $v['role_id'];
            }

            $permission = PermissionRole::whereIn('role_id', $role_ids)->select('permission_id')->get();
            if (!$permission) {
                return false;
            }

            $permission      = $permission->toArray();
            $permission_data = [];
            foreach ($permission as $k => $v) {
                $permission_data[$k] = $v['permission_id'];
            }

            $permission_list = Permission::whereIn('id', $permission_data)->get();

        } else {
            $permission_list = Permission::get();
        }

        if ($permission_list) {
            $permission_list = $permission_list->toArray();
            return $permission_list;
        } else {
            return false;
        }


    }

}
