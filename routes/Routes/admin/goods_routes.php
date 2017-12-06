<?php

Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    //行业
    Route::get('goods/index/{id?}', ['as' => 'admin.goods.index', 'uses' => 'GoodsController@index']);
    Route::get('goods', ['as' => 'admin.goods.create', 'uses' => 'GoodsController@create']);
    Route::post('goods', ['as' => 'admin.goods.create', 'uses' => 'GoodsController@store']);
    Route::get('goods/{id}/edit', ['as' => 'admin.goods.edit', 'uses' => 'GoodsController@edit']);
    Route::post('goods/{id}/edit', ['as' => 'admin.goods.edit', 'uses' => 'GoodsController@update']);
    Route::get('goods/{id}/del', ['as' => 'admin.goods.del', 'uses' => 'GoodsController@delete']);

//    产品相册
    Route::get('goods/{id}/photos', ['as' => 'admin.goods.edit', 'uses' => 'GoodsController@goodsPhotos']);
    Route::post('goods/{id}/photos', ['as' => 'admin.goods.edit', 'uses' => 'GoodsController@goodsPhotosStore']);
    Route::post('goods/photo/{id}/del', ['as' => 'admin.goods.edit', 'uses' => 'GoodsController@goodsPhotosDel']);
    Route::post('goods/photo/{id}/edit', ['as' => 'admin.goods.edit', 'uses' => 'GoodsController@goodsPhotosUpdate']);

    //分配库存
    Route::get('goods/{id}/distribution', ['as' => 'admin.goods.distribution', 'uses' => 'GoodsController@distribution']);
    Route::get('goods/{id}/distribution/{user_id}', ['as' => 'admin.goods.distribution', 'uses' => 'GoodsController@distributionToUser']);
    Route::post('goods/{id}/distribution/{user_id}', ['as' => 'admin.goods.distribution', 'uses' => 'GoodsController@distributionToUserStore']);


}
);
