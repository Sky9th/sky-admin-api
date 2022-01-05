<?php

namespace app\chat\socket\listener;

use app\chat\controller\Index as ChatController;
use app\chat\logic\Index as ChatLogic;
use app\chat\socket\Base;

class ToAll extends Base {

    /**
     * @param $data
     * @throws
     */

    public function handle($data) {
        var_dump('-------To all event-------');
        $chat = new ChatController();
        $res = $chat->msg($data[0], $this->user_id);
        if ($res['code'] == '0') {
            $msg = ChatLogic::index(0, ['id' => $res['data']]);
            $this->broadcast($msg[0],'toAllCb');
        } else {
            $this->websocket->emit('toAllError', $res);
        }
    }

}
