<?php

define('__ADMIN_PATH__',env('ADMIN_HOST'));

//引入首页管理路由
require_once __DIR__ . '/admin/index_routes.php';

//引入登录部分路由(登录,极验)
require_once __DIR__ . '/admin/login_routes.php';

//引入adminuser 管理员路由
require_once __DIR__ . '/admin/admin_user_routes.php';

//引入permisssion权限管理路由
require_once __DIR__ . '/admin/permission_routes.php';

//引入role角色管理路由
require_once __DIR__ . '/admin/role_routes.php';

//引入系统配置管理路由
require_once __DIR__ . '/admin/systemconfig_routes.php';

//引入自定义菜单
require_once __DIR__ . '/admin/diymenu_routes.php';

//商品管理
require_once __DIR__ . '/admin/goods_routes.php';

//分类
require_once __DIR__ . '/admin/category_routes.php';

//用户
require_once __DIR__ . '/admin/users_routes.php';

//订单
require_once __DIR__ . '/admin/order_routes.php';

//广告
require_once __DIR__ . '/admin/adv_routes.php';


/**
 * 工具路由
 */

//引入ueditor管理路由
require_once __DIR__ . '/admin/ueditor_routes.php';

//引入工具路由
require_once __DIR__ . '/admin/tool_routes.php';
