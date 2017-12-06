<?php

//登录路由
Route::any('/'.__ADMIN_PATH__.'/login','Admin\LoginController@index');


//极验验证路由
Route::any('/auth/geetest','Admin\LoginController@getGeetest');
