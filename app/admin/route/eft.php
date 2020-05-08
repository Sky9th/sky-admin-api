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

// 需要经过登录验证的接口，通过Auth中间件做登录鉴权
Route::group(function () {
    Route::resource('goods', 'eft.good');
    Route::rule('good/import', 'eft.good/import');
    Route::rule('good/fail', 'eft.good/fail');
    Route::rule('good/ignore/:id', 'eft.good/ignore');
    Route::rule('good/retry/:id', 'eft.good/retry');

    Route::resource('categories', 'eft.category');

    Route::resource('catalogues', 'eft.catalogue');
    Route::rule('catalogue/tree', 'eft.catalogue/tree');
    Route::rule('catalogue/relate', 'eft.catalogue/relate');
    Route::rule('catalogue/copyRelate', 'eft.catalogue/copy');
    Route::rule('catalogue/recognize', 'eft.catalogue/recognize');
    Route::rule('catalogue/getRelate/:id', 'eft.catalogue/getRelate');
    Route::rule('catalogue/match', 'eft.catalogue/match');

    Route::rule('combine/weapon', 'eft.combine/weapon');
    Route::rule('combine/create/:id', 'eft.combine/create');
    Route::rule('combine/count/:id', 'eft.combine/count');
})->middleware(\app\common\middleware\Auth::class);
