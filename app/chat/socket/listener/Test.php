<?php

namespace app\chat\socket\listener;

use app\chat\socket\Base;

class Test extends Base {

    public function handle($event) {
        $this->websocket->emit("testcallback", 'aaaa');
    }

}
