<?php

//role路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {


    //角色管理路由
    Route::any('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);


    //角色编辑
    Route::get('role/edit/id/{id?}', ['as' => 'admin.role.edit', 'uses' => 'RoleController@edit']); //修改
    Route::post('role/update', ['as' => 'admin.role.edit', 'uses' => 'RoleController@update']); //修改


    //角色添加
    Route::get('role/create', ['as' => 'admin.role.create', 'uses' => 'RoleController@create']); //添加
    Route::post('role/store', ['as' => 'admin.role.create', 'uses' => 'RoleController@store']); //添加

    //角色删除
    Route::get('role/del/id/{id?}', ['as' => 'admin.role.del', 'uses' => 'RoleController@destory']); //角色删除

    //角色权限配置
    Route::get('role/permission/id/{id?}', ['as' => 'admin.role.permission', 'uses' => 'RoleController@get_permission']); //角色删除
    Route::post('role/permission', ['as' => 'admin.role.permission', 'uses' => 'RoleController@get_permission']); //角色删除

}
);
