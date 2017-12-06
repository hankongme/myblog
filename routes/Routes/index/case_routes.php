<?php

//case路由调用
Route::group(['namespace' => 'Home'], function () {
    Route::any('case/info/{id?}', ['as' => 'index.case.info', 'uses' => 'CaseController@info']);
    Route::any('cases/{id?}', ['as' => 'index.case.index', 'uses' => 'CaseController@cases']);
}
);

