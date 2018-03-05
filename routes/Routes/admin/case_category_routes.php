<?php

//案例路由调用
Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    //案例管理路由
    Route::any('case_category/index/{id?}', ['as' => 'admin.case_category.index', 'uses' => 'CaseController@category']);

    Route::get('case_category/create', ['as' => 'admin.case_category.create', 'uses' => 'CaseController@categoryCreate']);
    //案例编辑
    Route::get('case_category/edit/id/{id?}', ['as' => 'admin.case_category.edit', 'uses' => 'CaseController@categoryEdit']); //修改

    Route::post('case_category/update', ['as' => 'admin.case_category.edit', 'uses' => 'CaseController@categoryUpdate']); //修改
    //案例添加
    Route::post('case_category/store', ['as' => 'admin.case_category.create', 'uses' => 'CaseController@categoryStore']); //添加
    //案例删除
    Route::get('case_category/del/id/{id?}', ['as' => 'admin.case_category.del', 'uses' => 'CaseController@categoryDestory']); //案例删除
    //案例状态
    Route::get('case_category/change/{id}/{status}', ['as' => 'admin.case_category.edit', 'uses' => 'CaseController@categoryChange']); //案例删除

}
);
