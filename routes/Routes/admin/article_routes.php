<?php

//article路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {


    //文章管理路由
    Route::any('article/index', ['as' => 'admin.article.index', 'uses' => 'ArticleController@index']);

    //文章编辑
    Route::get('article/edit/id/{id?}', ['as' => 'admin.article.edit', 'uses' => 'ArticleController@edit']); //修改
    Route::post('article/update', ['as' => 'admin.article.edit', 'uses' => 'ArticleController@update']); //修改


    //文章添加
    Route::get('article/create/{id?}', ['as' => 'admin.article.create', 'uses' => 'ArticleController@create']); //添加
    Route::post('article/store', ['as' => 'admin.article.create', 'uses' => 'ArticleController@store']); //添加

    //文章删除
    Route::get('article/{id?}/del', ['as' => 'admin.article.del', 'uses' => 'ArticleController@destory']); //文章删除

}
);
