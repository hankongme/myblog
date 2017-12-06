<?php

//systemconfig路由调用
Route::group(['prefix' => __ADMIN_PATH__, 'namespace' => 'Admin', 'middleware' => ['authAdmin']], function () {

    //系统配置管理路由
    Route::any('systemconfig/index', ['as' => 'admin.systemconfig.index', 'uses' => 'SystemconfigController@index']); //查询
    Route::any('systemconfig/group', ['as' => 'admin.systemconfig.group', 'uses' => 'SystemconfigController@group']); //系统配置
//    Route::any('systemconfig/index/cid/{cid?}', ['as' => 'admin.systemconfig.index', 'uses' => 'SystemconfigController@index']); //查询

//    Route::resource('admin/systemconfig', 'SystemconfigController');

    //修改
    Route::get('systemconfig/edit/id/{id?}', ['as' => 'admin.systemconfig.edit', 'uses' => 'SystemconfigController@edit']); //修改
    Route::post('systemconfig/update', ['as' => 'admin.systemconfig.edit', 'uses' => 'SystemconfigController@update']); //修改
    Route::post('systemconfig/savegroup', ['as' => 'admin.systemconfig.savegroup', 'uses' => 'SystemconfigController@save_group']); //修改

    //添加
    Route::get('systemconfig/create', ['as' => 'admin.systemconfig.create', 'uses' => 'SystemconfigController@create']);
    Route::get('systemconfig/create/cid/{cid?}', ['as' => 'admin.systemconfig.create', 'uses' => 'SystemconfigController@create']);

    Route::post('systemconfig/store', ['as' => 'admin.systemconfig.create', 'uses' => 'SystemconfigController@store']);


    //删除
    Route::any('systemconfig/del/id/{id?}', ['as' => 'admin.systemconfig.delete', 'uses' => 'SystemconfigController@destory']); //添加


}
);
