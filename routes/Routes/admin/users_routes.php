<?php

Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    //用户列表
    Route::any('user/index/{id?}', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
    Route::get('user', ['as' => 'admin.user.create', 'uses' => 'UserController@create']);
    Route::post('user', ['as' => 'admin.user.create', 'uses' => 'UserController@store']);
    Route::get('user/{id}/edit', ['as' => 'admin.user.edit', 'uses' => 'UserController@show']);
    Route::post('user/{id}/edit', ['as' => 'admin.user.edit', 'uses' => 'UserController@update']);
    Route::get('user/money/{id}', ['as' => 'admin.user.edit', 'uses' => 'UserController@modifyUserMoney']);
    Route::post('user/money/{id}', ['as' => 'admin.user.edit', 'uses' => 'UserController@modifyUserMoneyStore']);

    Route::get('message', ['as' => 'admin.message.index', 'uses' => 'UserController@message']);
    Route::get('message/{id}', ['as' => 'admin.message.edit', 'uses' => 'UserController@messageShow']);




}
);
