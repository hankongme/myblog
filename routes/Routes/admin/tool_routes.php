<?php

//工具类路由调用
Route::group(['prefix' => __ADMIN_PATH__, 'namespace' => 'Admin', 'middleware' => ['authAdmin']], function () {

    //文件上传路由
    Route::any('tool/upload_image', ['as' => 'admin.tool.uploadimage', 'uses' => 'ToolController@upload_image']); //查询

}
);
