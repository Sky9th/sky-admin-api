<?php
// 全局中间件定义文件
return [
    \app\common\middleware\AllowCrossDomain::class,
    \app\common\middleware\Filter::class,
    \app\common\middleware\Rsa::class,
    \app\common\middleware\Json::class
];
