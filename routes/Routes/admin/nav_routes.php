<?php

//nav路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {


    Route::any('nav/index/{id?}', ['as' => 'admin.nav.index', 'uses' => 'NavController@index']);
    Route::get('nav/{id}', ['as' => 'admin.nav.edit', 'uses' => 'NavController@edit']); //修改
    Route::post('nav/{id}', ['as' => 'admin.nav.edit', 'uses' => 'NavController@update']); //修改
    Route::get('nav', ['as' => 'admin.nav.create', 'uses' => 'NavController@create']); //添加
    Route::post('nav', ['as' => 'admin.nav.create', 'uses' => 'NavController@store']); //添加
    Route::get('nav/{id?}/del', ['as' => 'admin.nav.del', 'uses' => 'NavController@destory']); //删除

}
);
