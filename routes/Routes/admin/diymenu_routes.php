<?php

//diymenu路由调用
Route::group (
    [ 'prefix' => __ADMIN_PATH__ , 'namespace' => 'Admin' , 'middleware' => [ 'authAdmin' ] ] , function () {

    //列表分类路由
    Route::any ('diymenu/index' , [ 'as' => 'admin.diymenu.index' , 'uses' => 'DiyMenuController@index' ]);


    Route::get ('diymenu/create' , [ 'as' => 'admin.diymenu.create' , 'uses' => 'DiyMenuController@create' ]);
    Route::post ('diymenu/store' , [ 'as' => 'admin.diymenu.create' , 'uses' => 'DiyMenuController@store' ]);

    Route::get ('diymenu/edit/id/{id?}' , [ 'as' => 'admin.diymenu.edit' , 'uses' => 'DiyMenuController@edit' ]);
    Route::post ('diymenu/update' , [ 'as' => 'admin.diymenu.edit' , 'uses' => 'DiyMenuController@update' ]);
    Route::any ('diymenu/del/id/{id}' , [ 'as' => 'admin.diymenu.delete' , 'uses' => 'DiyMenuController@del' ]);

    //发布自定义菜单
    Route::get ('diymenu/publish' , [ 'as' => 'admin.diymenu.publish' , 'uses' => 'DiyMenuController@publish' ]);

}
);
