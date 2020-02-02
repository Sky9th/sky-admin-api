<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::rest('delete', ['DELETE', '/<id>', 'delete']);

Route::group(function () {
    Route::rule('login', 'auth/login');
});

// 需要经过登录验证的接口，通过Auth中间件做登录鉴权
Route::group(function () {
    Route::rule('user/getPermissionInfo', 'user/getPermissionInfo');
    Route::resource('roles', 'role');
    Route::resource('menus', 'menu');
    Route::resource('apis', 'api');
    Route::resource('users', 'user');
    Route::resource('routes', 'route');
})->middleware(\app\common\middleware\Auth::class);
