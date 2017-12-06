<?php

//case路由调用
Route::group(['namespace' => 'Home'], function () {
    Route::any('news/info/{id?}', ['as' => 'index.article.info', 'uses' => 'ArticleController@info']);
    Route::any('news/{id?}', ['as' => 'index.article.index', 'uses' => 'ArticleController@lists']);
}
);

