<?php

namespace app\chat\socket;

use app\common\logic\UserAuth;
use think\Container;
use think\swoole\Websocket;

class Base {

    public Websocket $websocket;
    public array $session = [];
    public $userInfo = [];
    public array $userList = [];

    public function __construct(Container $container){
        $this->websocket = $container->make(\think\swoole\Websocket::class);
        $userList = cache('socket_user_list');
        $this->userList = $userList ? $userList : [];
    }

    public function isLogin ($sessionKey) {
        $this->session = UserAuth::checkSession($sessionKey);
    }

    public function userInfo () {
        var_dump($this->session);
        if(!!$this->session) $this->userInfo = UserAuth::info($this->session['user_id']);
    }

    public function setUserList($uid) {
        $this->userList[$uid] = array_merge([
            "create_time" => time(),
            "isLogin" => !!$this->session,
        ], $this->userInfo);
        cache('socket_user_list', $this->userList);
    }

}
