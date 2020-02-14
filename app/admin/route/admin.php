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

Route::rest('structure', ['GET', 'structure', 'structure']);
Route::rest('delete', ['DELETE', '<id?>', 'delete']);

Route::group(function () {
    Route::rule('login', 'sys.auth/login');
});

// 需要经过登录验证的接口，通过Auth中间件做登录鉴权
Route::group(function () {
    Route::resource('roles', 'sys.role');
    Route::rule('role/savePermission', 'sys.role/savePermission');
    Route::rule('role/modifyUser', 'sys.role/modifyUser');
    Route::rule('role/indexByUserId/:user_id', 'sys.role/indexByUserId');

    Route::resource('menus', 'sys.menu');
    Route::rule('menu/indexByRoleId/:role_id', 'sys.menu/indexByRoleId');
    Route::rule('menu/indexWithApi', 'sys.menu/indexWithApi');

    Route::resource('apis', 'sys.api');
    Route::rule('api/indexByMenuId/:menu_id', 'sys.api/indexByMenuId');
    Route::rule('api/indexByRoleId/:role_id', 'sys.api/indexByRoleId');
    Route::rule('api/modifyMenu', 'sys.api/modifyMenu');

    Route::resource('users', 'sys.user');
    Route::rule('user/getPermissionInfo', 'sys.user/getPermissionInfo');
    Route::rule('user/indexByRoleId/:role_id', 'sys.user/indexByRoleId');

    Route::resource('routes', 'sys.route');


    Route::resource('projects', 'sky9th.project');
    Route::resource('techs', 'sky9th.tech');
})->middleware(\app\common\middleware\Auth::class);
