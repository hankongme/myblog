<?php

//article路由调用
Route::group(['namespace' => 'Home'], function () {
    Route::any('a/{id?}', ['as' => 'index.article.info', 'uses' => 'ArticleController@info']);
    Route::any('c/{id?}', ['as' => 'index.article.category', 'uses' => 'ArticleController@categoryList']);
    Route::any('t/{tag?}', ['as' => 'index.article.tag', 'uses' => 'ArticleController@tagList']);
    Route::any('d/{id?}', ['as' => 'index.article.date', 'uses' => 'ArticleController@dateList']);
}
);

