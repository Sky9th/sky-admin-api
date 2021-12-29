<?php
use think\facade\Route;

Route::rule('upload','index/general');

Route::group(function () {
    Route::rule('index','index/index');
});

Route::group(function () {
})->middleware(\app\common\middleware\Auth::class);;
