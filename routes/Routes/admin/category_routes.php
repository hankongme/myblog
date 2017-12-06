<?php

Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {
    //行业
    Route::get('category/index/{id?}', ['as' => 'admin.category.index', 'uses' => 'CategoryController@index']);
    Route::get('category', ['as' => 'admin.category.create', 'uses' => 'CategoryController@create']);
    Route::post('category', ['as' => 'admin.category.create', 'uses' => 'CategoryController@store']);
    Route::get('category/{id}', ['as' => 'admin.category.edit', 'uses' => 'CategoryController@edit']);
    Route::post('category/{id}', ['as' => 'admin.category.edit', 'uses' => 'CategoryController@update']);
    Route::get('category/{id}/del', ['as' => 'admin.category.del', 'uses' => 'CategoryController@delete']);
}
);
