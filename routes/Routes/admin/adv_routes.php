<?php

//adv路由调用
Route::group([ 'prefix' => __ADMIN_PATH__, 'namespace' => 'Admin', 'middleware' => [ 'authAdmin' ] ], function () {

    //列表分类路由
    Route::any('adv/index', [ 'as' => 'admin.adv.index', 'uses' => 'AdvController@index' ]);
    Route::get('adv/create', [ 'as' => 'admin.adv.create', 'uses' => 'AdvController@create' ]);
    Route::post('adv/store', [ 'as' => 'admin.adv.create', 'uses' => 'AdvController@store' ]);
    Route::get('adv/edit/id/{id?}', [ 'as' => 'admin.adv.edit', 'uses' => 'AdvController@edit' ]);
    Route::post('adv/update', [ 'as' => 'admin.adv.edit', 'uses' => 'AdvController@update' ]);
    Route::get('adv/{id}/del', [ 'as' => 'admin.adv.delete', 'uses' => 'AdvController@del' ]);
    Route::any('adv/list/id/{id?}', [ 'as' => 'admin.adv.list', 'uses' => 'AdvController@advlist' ]);
    Route::get('adv/createadv/id/{id?}', [ 'as' => 'admin.adv.create', 'uses' => 'AdvController@createadv' ]);
    Route::post('adv/storeadv', [ 'as' => 'admin.adv.create', 'uses' => 'AdvController@storeadv' ]);
    Route::get('adv/editadv/id/{id?}', [ 'as' => 'admin.adv.edit', 'uses' => 'AdvController@editadv' ]);
    Route::post('adv/updateadv', [ 'as' => 'admin.adv.edit', 'uses' => 'AdvController@updateadv' ]);
    Route::get('adv/deladv/id/{id?}', [ 'as' => 'admin.adv.deleteadv', 'uses' => 'AdvController@deladv' ]);
    Route::get('adv/searchtask', [ 'as' => 'admin.adv.searchtask', 'uses' => 'AdvController@search_task' ]);

}
);
