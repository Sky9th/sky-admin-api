<?php

namespace app\chat\socket\listener\base;

use app\chat\socket\Base;

class Open extends Base {

    public function handle($request) {
        var_dump('--------Open Event-------');
        $this->setUserList($this->fd);
    }

}
