<?php

define('SUPER_ADMIN_ID', 1);  //超级管理员ID,默认具备所有权限
define('SUPER_ROLE_ID', 1);  //超级管理组ID,默认具备所有权限

return [
    //秘钥
    'api_key' => '123',//秘钥：RSA加密解密配对
    'password_encrypt_key' => '123', //混合秘钥：各类密码混合md5加密使用

    //系统静态配置
    'list_row' => 15, //分页查询默认查询数
    'list_row_range' => [15, 30, 50, 100] //分页每页数量可选参数
];
