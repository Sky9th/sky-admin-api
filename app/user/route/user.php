<?php
use think\facade\Route;

Route::rule('upload','index/general');

Route::group(function () {
    Route::rule('getVerify','auth/aliVerifyCode');

    Route::rule('register','auth/register');
    Route::rule('login','auth/login');
    Route::rule('captcha','auth/captcha');
});

Route::group(function () {
    Route::rule('info','user/userInfo');
})->middleware(\app\common\middleware\Auth::class);;
