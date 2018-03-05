<?php

//tag路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    //标签管理路由
    Route::any('tag/index', ['as' => 'admin.tag.index', 'uses' => 'TagController@index']);
    Route::any('tag/key', ['as' => 'admin.tag.key', 'uses' => 'TagController@getTagByKeyWords']);

    //标签编辑
    Route::get('tag/{id}/edit', ['as' => 'admin.tag.edit', 'uses' => 'TagController@edit']); //修改
    Route::post('tag/{id}/update', ['as' => 'admin.tag.edit', 'uses' => 'TagController@update']); //修改

    //标签添加
    Route::get('tag/create/{id?}', ['as' => 'admin.tag.create', 'uses' => 'TagController@create']); //添加
    Route::post('tag/store', ['as' => 'admin.tag.create', 'uses' => 'TagController@store']); //添加

    //标签删除
    Route::get('tag/{id?}/del', ['as' => 'admin.tag.del', 'uses' => 'TagController@destory']); //标签删除

}
);
