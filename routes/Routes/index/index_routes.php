<?php
Route::any('/', 'Home\IndexController@index');
Route::any('index', 'Home\IndexController@index');
Route::any('service', 'Home\IndexController@service');
Route::any('personal', 'Home\IndexController@personal');
Route::any('contact', 'Home\IndexController@contact');
Route::post('/message', 'Home\IndexController@message');
