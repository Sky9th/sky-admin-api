<?php

use think\swoole\websocket\socketio\Handler;

return [
    'http'       => [
        'enable'     => true,
        'host'       => env('swoole.swoole_ip','0.0.0.0'),
        'port'       => env('swoole.swoole_port',8080),
        'worker_num' => swoole_cpu_num(),
        'options'    => [],
    ],
    'websocket'  => [
        'enable'        => true,
        'handler'       => Handler::class,
        'ping_interval' => 25000,
        'ping_timeout'  => 60000,
        'room'          => [
            'type'  => 'table',
            'table' => [
                'room_rows'   => 8192,
                'room_size'   => 2048,
                'client_rows' => 4096,
                'client_size' => 2048,
            ],
            'redis' => [
                'host'          => '127.0.0.1',
                'port'          => 6379,
                'max_active'    => 3,
                'max_wait_time' => 5,
            ],
        ],
        'listen'        => [
            'Open' => \app\chat\socket\listener\base\Open::class,
            'Connect' => \app\chat\socket\listener\base\Connect::class,
            'Message' => \app\chat\socket\listener\base\Message::class,
            'Close' => \app\chat\socket\listener\base\Close::class,

            'Event' => \app\chat\socket\Dispatcher::class,
            'Event.ToAll' => \app\chat\socket\listener\ToAll::class,
            'Event.Typing' => \app\chat\socket\listener\Typing::class,
        ],
        'subscribe'     => [
        ],
    ],
    'rpc'        => [
        'server' => [
            'enable'     => false,
            'host'       => env('swoole.swoole_rpc_ip','0.0.0.0'),
            'port'       => env('swoole.swoole_rpc_port',9090),
            'worker_num' => swoole_cpu_num(),
            'services'   => [],
        ],
        'client' => [],
    ],
    //队列
    'queue'      => [
        'enable'  => false,
        'workers' => [],
    ],
    'hot_update' => [
        'enable'  => env('APP_DEBUG', false),
        'name'    => ['*.php'],
        'include' => [app_path()],
        'exclude' => [],
    ],
    //连接池
    'pool'       => [
        'db'    => [
            'enable'        => true,
            'max_active'    => 3,
            'max_wait_time' => 5,
        ],
        'cache' => [
            'enable'        => true,
            'max_active'    => 3,
            'max_wait_time' => 5,
        ],
        //自定义连接池
    ],
    'tables'     => [],
    //每个worker里需要预加载以共用的实例
    'concretes'  => [],
    //重置器
    'resetters'  => [],
    //每次请求前需要清空的实例
    'instances'  => [],
    //每次请求前需要重新执行的服务
    'services'   => [],
];
