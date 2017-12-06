<?php

//permission路由调用
Route::group(['prefix' => __ADMIN_PATH__, 'namespace' => 'Admin', 'middleware' => ['authAdmin']], function () {

    //权限管理路由
    Route::any('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
    Route::any('permission/index/cid/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询

//    Route::resource('admin/permission', 'PermissionController');

    //修改
    Route::get('permission/edit/{id?}', ['as' => 'admin.permission.edit', 'uses' => 'PermissionController@edit']); //修改
    Route::post('permission/update', ['as' => 'admin.permission.edit', 'uses' => 'PermissionController@update']); //修改

    //添加
    Route::get('permission/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    Route::get('permission/create/cid/{cid?}', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);

    Route::post('permission/store', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@store']);


    //删除
    Route::any('permission/destory/{id?}', ['as' => 'admin.permission.delete', 'uses' => 'PermissionController@destory']); //添加


}
);
