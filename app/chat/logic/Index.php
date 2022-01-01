<?php

namespace app\chat\logic;

use app\common\model\sky9th\ChatTag;
use app\common\model\sky9th\ChatUser;
use think\Db;

class Index
{
    static public function afterMsg ($user_id, $tag_id) {
        $chatUser = new ChatUser();
        $exist = $chatUser->where('user_id', $user_id)->find();
        if ($exist) {
            $exist->post++;
            $result1 = $exist->save();
        } else {
            $result1 = $chatUser->save([
                'user_id' => $user_id
            ]);
        }

        $chatTag = new ChatTag();
        $exist = $chatTag->where('id', $tag_id)->find();
        $exist->sum++;
        $result2 = $exist->save();

        return $result1 && $result2 ? success() : error();
    }
}
