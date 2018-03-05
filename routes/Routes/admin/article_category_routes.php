<?php

//article_category路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {


    //分类管理路由
    Route::any('article_category/index/{id?}', ['as' => 'admin.article_category.index', 'uses' => 'ArticleCategoryController@index']);


    //分类编辑
    Route::get('article_category/edit/id/{id?}', ['as' => 'admin.article_category.edit', 'uses' => 'ArticleCategoryController@edit']); //修改
    Route::post('article_category/update', ['as' => 'admin.article_category.edit', 'uses' => 'ArticleCategoryController@update']); //修改


    //分类添加
    Route::get('article_category/create', ['as' => 'admin.article_category.create', 'uses' => 'ArticleCategoryController@create']); //添加
    
    Route::post('article_category/store', ['as' => 'admin.article_category.create', 'uses' => 'ArticleCategoryController@store']); //添加

    //分类删除
    Route::get('article_category/del/id/{id?}', ['as' => 'admin.article_category.del', 'uses' => 'ArticleCategoryController@destory']); //分类删除

}
);
