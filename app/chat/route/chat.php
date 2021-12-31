<?php
use think\facade\Route;

Route::group(function () {
    Route::rule('index','index/index');
});

Route::group(function () {
    Route::rule('msg','index/msg');
})->middleware(\app\common\middleware\Auth::class);;
