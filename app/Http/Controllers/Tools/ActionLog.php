<?php
/**
 * Created by PhpStorm.
 * User: Robot
 * Date: 17/8/14
 * Time: 上午11:37
 * Position: ShenZhen
 */
namespace App\Http\Controllers\Tools;

use App\AdminActionLog;
use App\AgencyActionLog;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\IpBaseTools;

class ActionLog
{
    /**
     * @param int    $ids
     * @param string $remark
     * @param string $current
     * @param string $permission
     * @param int    $type 0:控制器操作 1:登录操作
     */
    static function actionLog($ids = 0, $remark = '', $current = '', $permission = '', $type = 0)
    {
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }
        if (is_numeric($ids)) {
            $ids = 'ID:' . $ids;
        }

        $current_name = Route::currentRouteName();
        if (!$remark) {

            $auth_name_list = self::getAuthNameList();

            if (isset($auth_name_list[$current_name])) {
                $remark = '操作:' . $auth_name_list[$current_name];
            } else {
                $remark = '操作请求:' . $_SERVER['REQUEST_URI'];
            }


        }
        $data['ids']          = $ids;
        $data['permission']   = $permission ? $permission : $current_name;
        $data['adminuser_id'] = AuthTool::id();
        $data['remark']       = $remark;
        $data['current_name'] = $current ? $current : Route::currentRouteName();
        $data['ip']           = BaseTools::get_client_ip();
        $data['type']         = $type;
        AdminActionLog::insert($data);

    }


    /**
     * 获取权限名称列表
     */
    static function getAuthNameList()
    {
        $auth_name_list = session('auth_name_list');

        if (!$auth_name_list || AuthTool::check_auth_update()) {
            $permission_list = AuthTool::getAuthList();

            $auth_name_list = [];
            if ($permission_list) {

                foreach ($permission_list as $k => $v) {

                    $auth_name_list[$v['name']] = $v['display_name'];
                }
                session(['auth_name_list' => $auth_name_list]);
            }
        }
        return $auth_name_list;
    }


}
