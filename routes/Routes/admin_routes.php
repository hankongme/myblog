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

//引入文章路由
require_once  __DIR__.'/admin/article_routes.php';

//文章分类
require_once  __DIR__.'/admin/article_category_routes.php';

//标签管理
require_once  __DIR__.'/admin/tag_routes.php';

//导航管理
require_once  __DIR__.'/admin/nav_routes.php';


/**
 * 工具路由
 */

//引入ueditor管理路由
require_once __DIR__ . '/admin/ueditor_routes.php';

//引入工具路由
require_once __DIR__ . '/admin/tool_routes.php';
