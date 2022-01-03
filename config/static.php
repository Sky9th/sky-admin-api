<?php

return [
    //秘钥
    'api_key' => env('encrypt.api_key',''),//秘钥：RSA加密解密配对
    'password_encrypt_key' => env('encrypt.password_salt',''), //混合秘钥：各类密码混合md5加密使用

    //系统静态配置
    'list_row' => 15, //分页查询默认查询数
    'list_row_range' => [15, 30, 50, 100] //分页每页数量可选参数
];
