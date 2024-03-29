<?php
use think\facade\Route;

Route::rule('upload','index/general');

Route::group(function () {
    Route::rule('getVerify','auth/aliVerifyCode');
    Route::rule('setPassword','auth/setPassword');

    Route::rule('register','auth/register');
    Route::rule('login','auth/login');
    Route::rule('captcha','auth/captcha');
});

Route::group(function () {
    Route::rule('info','user/userInfo');
    Route::rule('avatar','user/avatar');
    Route::rule('setNickname','user/setNickname');
    Route::rule('setAvatar','user/setAvatar');
})->middleware(\app\common\middleware\Auth::class);;
