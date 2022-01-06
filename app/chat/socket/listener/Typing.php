<?php

namespace app\chat\socket\listener;

use app\chat\socket\Base;

class Typing extends Base {

    public $typingList = [];

    public function handle($data) {
        var_dump('-------Typing event--------');
        $typing = $data[0];
        $this->typingList = cache(Base::$CACHE_CHAT_TYPING_LIST);
        if ($typing) {
            $this->typingList[$this->fd] = $typing;
        } else {
            unset($this->typingList[$this->fd]);
        }
        cache(Base::$CACHE_CHAT_TYPING_LIST, $this->typingList);
        $this->broadcast($this->typingList, 'typingCb');
    }

}
