<?php

namespace app\chat\socket\listener\base;

use app\chat\socket\Base;

class Connect extends Base {

    public function handle($data) {
        var_dump('--------Connect event---------');
        $this->isLogin($data['Session-Key']);
        if ($this->session) {
            var_dump('--------authCb, joinCb event---------');
            $this->websocket->emit('authCb', ['self' => $this->userList[$this->websocket->getSender()], 'user' => $this->getUserList()]);
            $this->broadcast($this->getUserList($this->fd), 'joinCb');
        }
    }

}
