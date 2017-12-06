<?php

//admin_user路由调用
Route::group([ 'prefix' => __ADMIN_PATH__, 'namespace' => 'Admin', 'middleware' => [ 'authAdmin' ] ], function () {

    //管理员管理路由
    Route::any('admin_user/index', [ 'as' => 'admin.adminuser.index', 'uses' => 'AdminUserController@index' ]);

//    Route::resource('admin_user', 'AdminUserController');  垃圾resource,不用

    //修改
    Route::get('admin_user/edit/id/{id?}', [ 'as' => 'admin.adminuser.edit', 'uses' => 'AdminUserController@edit' ]); //修改
    Route::post('admin_user/update', [ 'as' => 'admin.adminuser.edit', 'uses' => 'AdminUserController@update' ]); //修改POST

    //增加
    Route::get('admin_user/create', [ 'as' => 'admin.adminuser.create', 'uses' => 'AdminUserController@create' ]); //增加
    Route::post('admin_user/store', [ 'as' => 'admin.adminuser.create', 'uses' => 'AdminUserController@store' ]); //增加POST

    //删除
    Route::any('admin_user/del/id/{id?}', [ 'as' => 'admin.adminuser.del', 'uses' => 'AdminUserController@destory' ]);//删除

    //重置密码
    Route::any('admin_user/repass/id/{id?}', [ 'as' => 'admin.adminuser.repass', 'uses' => 'AdminUserController@rePass' ]);//删除
    Route::any('admin_user/repass', [ 'as' => 'admin.adminuser.repass', 'uses' => 'AdminUserController@rePass' ]);//删除

    //设置所属组
    Route::any('admin_user/group/id/{id?}', [ 'as' => 'admin.adminuser.group', 'uses' => 'AdminUserController@admin_group' ]);
    Route::any('admin_user/group', [ 'as' => 'admin.adminuser.group', 'uses' => 'AdminUserController@admin_group' ]);

    //设置管理员日志路由
    Route::get('actionlog/index', [ 'as' => 'admin.actionlog.index', 'uses' => 'ActionlogController@index' ]);
    Route::get('actionlog/{id}/del', [ 'as' => 'admin.actionlog.del', 'uses' => 'ActionlogController@destory' ]);

}
);
