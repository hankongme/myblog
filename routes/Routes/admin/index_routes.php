<?php


Route::get(__ADMIN_PATH__ . '/index', ['as' => 'admin.index', 'middleware' => ['authAdmin'], 'uses' => 'Admin\IndexController@index']);

$this->group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {

    Route::any('/logout', ['as' => 'admin.index.logout', 'uses' => 'IndexController@logout']); //添加
    Route::any('index/repass', ['as' => 'admin.index.repass', 'uses' => 'IndexController@repass']); //添加
    Route::any('welcome', ['as' => 'admin.index.welcome', 'uses' => 'IndexController@welcome']); //添加

    Route::get('/', function () {
        return redirect('/' . __ADMIN_PATH__ . '/index');
    }
    );

    Route::any('/admin/index/content', 'Admin\IndexController@content');

}
);


//数据修正路由,都放在这个group里面

$this->group(['namespace' => 'Admin', 'middleware' => ['authAdmin'], 'prefix' => __ADMIN_PATH__], function () {
    Route::any('region_first', 'HotfixController@regionFormat');
    Route::any('order_add/{id}/{money}/{company}', 'HotfixController@order_add');
    Route::any('clear_order', 'HotfixController@clear_order');
}
);


