<?php

namespace app\chat\socket;

use app\common\logic\UserAuth;
use think\Container;
use think\swoole\Websocket;

class Base {

    public static string $CACHE_CHAT_USER_LIST = 'chat_user_list';
    public static string $CACHE_CHAT_TYPING_LIST = 'chat_typing_list';

    public Websocket $websocket;
    public $session = [];
    public $userInfo = [];
    public $fd;
    public $user_id;
    public array $userList = [];

    public function __construct(Container $container){
        $this->websocket = $container->make(\think\swoole\Websocket::class);
        $userList = cache(Base::$CACHE_CHAT_USER_LIST);
        $this->userList = $userList ? $userList : [];
        $this->fd = $this->websocket->getSender();
        if (isset($this->userList[$this->fd])) {
            $this->userInfo = $this->userList[$this->fd];
            $this->user_id = isset($this->userInfo['id']) ? $this->userInfo['id'] : 0;
        }
    }

    public function isLogin ($sessionKey) {
        $this->session = UserAuth::checkSession($sessionKey);
        if ($this->session) {
            $this->session['sessionKey'] = $sessionKey;
            $this->user_id = $this->session['user_id'];
            $this->userInfo = UserAuth::info($this->session['user_id'])->toArray();
            $this->setUserList($this->fd);
        }
    }

    public function setUserList($fd, $remove = false) {
        foreach ($this->userList as $key => $value){
            if (isset($value['id']) && $this->user_id == $value['id']) {
                unset($this->userList[$key]);
            }
        }
        if($remove){
            unset($this->userList[$fd]);
        } else {
            $this->userList[$fd] = array_merge([
                "create_time" => time(),
                "isLogin" => !!$this->session,
                "sessionKey" => $this->session ? $this->session['sessionKey'] : ''
            ], $this->userInfo);
        }
        cache(Base::$CACHE_CHAT_USER_LIST, $this->userList);
    }

    public function getUserList($fd = null) {
        return $fd && isset($this->userList[$fd]) ? [$fd=>$this->userList[$fd]] : $this->userList;
    }

    public function broadcast($data, $event = null) {
        var_dump('---------broadcast--------');
        $senders = array_keys($this->userList);
        foreach ($senders as $sender) {
            var_dump('---------sender:'.$sender);
            if($event) {
                $this->websocket->to($sender)->emit($event, $data);
            } else {
                $this->websocket->to($sender)->push($data);
            }
        }
    }

}
