<?php

Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    //用户列表
    Route::any('user/index/{id?}', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
    Route::get('user', ['as' => 'admin.user.create', 'uses' => 'UserController@create']);
    Route::post('user', ['as' => 'admin.user.create', 'uses' => 'UserController@store']);
    Route::get('user/{id}/edit', ['as' => 'admin.user.edit', 'uses' => 'UserController@edit']);
    Route::post('user/{id}/edit', ['as' => 'admin.user.edit', 'uses' => 'UserController@update']);
    Route::get('message', ['as' => 'admin.message.index', 'uses' => 'UserController@message']);
    Route::get('message/{id}', ['as' => 'admin.message.edit', 'uses' => 'UserController@messageShow']);

    Route::any('shop_apply/index', ['as' => 'admin.apply.index', 'uses' => 'ShopApplyController@index']);
    Route::any('shop_apply/{id}/edit', ['as' => 'admin.apply.edit', 'uses' => 'ShopApplyController@edit']);
    Route::any('shop_apply/{id}/agree', ['as' => 'admin.apply.edit', 'uses' => 'ShopApplyController@agree']);
    Route::any('shop_apply/{id}/reject', ['as' => 'admin.apply.edit', 'uses' => 'ShopApplyController@reject']);

    //修改代理等级
    Route::get('user/{id}/level', ['as' => 'admin.user.edit', 'uses' => 'UserController@level']);
    Route::get('user/top_user/{id}', ['as' => 'admin.user.edit', 'uses' => 'UserController@topUser']);
    Route::post('user/{id}/level', ['as' => 'admin.user.edit', 'uses' => 'UserController@levelStore']);

    Route::get('user/{id}/stock_log', ['as' => 'admin.user.edit', 'uses' => 'UserController@stockLog']);

});
