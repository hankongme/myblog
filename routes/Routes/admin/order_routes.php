<?php

Route::group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    Route::any('order/index', ['as' => 'admin.order.index', 'uses' => 'OrderController@index']);
    Route::any('order/bank', ['as' => 'admin.order.bank', 'uses' => 'OrderController@bank']);
    Route::any('order/{id}/print', ['as' => 'admin.order.print', 'uses' => 'OrderController@orderPrint']);
    Route::get('order/{id}/edit', ['as' => 'admin.order.edit', 'uses' => 'OrderController@edit']);
    Route::post('order/{id}/edit', ['as' => 'admin.order.edit', 'uses' => 'OrderController@update']);
    Route::get('order/{id}/confirm', ['as' => 'admin.order.edit', 'uses' => 'OrderController@confirm']);
    Route::post('order/{id}/confirm', ['as' => 'admin.order.edit', 'uses' => 'OrderController@confirmStore']);
    Route::get('order/{id}/del', ['as' => 'admin.order.edit', 'uses' => 'OrderController@delete']);
    Route::post('order/{id}/change', ['as' => 'admin.order.edit', 'uses' => 'OrderController@change']);


    Route::get('refund/index', ['as' => 'admin.refund.index', 'uses' => 'RefundController@index']);
    Route::get('refund/{id}/confirm', ['as' => 'admin.refund.edit', 'uses' => 'RefundController@confirm']);
    Route::get('refund/{id}/cancel', ['as' => 'admin.refund.edit', 'uses' => 'RefundController@cancel']);

    //导出订单
    Route::get('order/out_put', ['as' => 'admin.order.output', 'uses' => 'OrderController@excelOutPut']);


    //发货
    Route::any('order/shipping', ['as' => 'admin.shipping.index', 'uses' => 'OrderController@shipping']);
    Route::any('order/{id}/view', ['as' => 'admin.shipping.view', 'uses' => 'OrderController@orderView']);
    Route::get('order/{id}/deliver', ['as' => 'admin.shipping.deliver', 'uses' => 'OrderController@deliver']);
    Route::post('order/{id}/deliver', ['as' => 'admin.shipping.deliver', 'uses' => 'OrderController@deliverStore']);

}
);
