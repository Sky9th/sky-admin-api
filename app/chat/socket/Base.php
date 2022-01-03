<?php

namespace app\chat\socket;

use think\Container;

class Base {
    public $websocket;

    public function __construct(Container $container){
        $this->websocket = $container->make(\think\swoole\Websocket::class);
    }

    public function isLogin() {
        $this->websocket->push();
    }

}
