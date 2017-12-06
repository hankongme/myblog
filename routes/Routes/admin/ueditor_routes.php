<?php

//ueditor路由调用
Route::group(['prefix' => __ADMIN_PATH__, 'namespace' => 'Admin\Ueditor', 'middleware' => ['authAdmin']], function () {

    //文件上传路由
    Route::any('ueditor/index', ['as' => 'admin.ueditor.index', 'uses' => 'UeditorController@index']); //查询

}
);
