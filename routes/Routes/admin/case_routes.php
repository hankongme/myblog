<?php

//case路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {


    //文章管理路由
    Route::any('case/index', ['as' => 'admin.case.index', 'uses' => 'CaseController@index']);

    //文章编辑
    Route::get('case/edit/id/{id?}', ['as' => 'admin.case.edit', 'uses' => 'CaseController@edit']); //修改
    Route::post('case/update', ['as' => 'admin.case.edit', 'uses' => 'CaseController@update']); //修改

    //文章添加
    Route::get('case/create', ['as' => 'admin.case.create', 'uses' => 'CaseController@create']); //添加
    Route::post('case/store', ['as' => 'admin.case.create', 'uses' => 'CaseController@store']); //添加

    //文章删除
    Route::get('case/del/id/{id?}', ['as' => 'admin.case.del', 'uses' => 'CaseController@destory']); //文章删除

}
);
