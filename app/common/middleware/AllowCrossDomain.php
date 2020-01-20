<?php

namespace app\common\middleware;

class AllowCrossDomain extends \think\middleware\AllowCrossDomain
{

    protected $header = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, Noncestr, Session-Key, Signature, Timestamp',

    ];
}
