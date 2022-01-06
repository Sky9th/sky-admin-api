<?php
use think\facade\Route;

Route::group(function () {
    Route::rule('index','index/index');
    Route::rule('people','index/people');
    Route::rule('tag','index/tag');
});

Route::group(function () {
    Route::rule('msg','index/msg');
})->middleware(\app\common\middleware\Auth::class);
