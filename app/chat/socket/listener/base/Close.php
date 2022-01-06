<?php

namespace app\chat\socket\listener\base;

use app\chat\socket\Base;

class Close extends Base {

    public function handle() {
        var_dump('-------Close event------');
        var_dump($this->fd);
        $this->broadcast($this->getUserList($this->fd), 'leaveCb');
       $this->setUserList($this->fd, true);
    }

}
