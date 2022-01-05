<?php

namespace app\chat\logic;

use app\common\model\sky9th\Chat as ChatModel;
use app\common\model\sky9th\ChatTag;
use app\common\model\sky9th\ChatUser;
use think\Collection;

class Index
{
    /**
     * @param $id
     * @param $where
     * @return Collection
     * @throws
     */
    static public function index ($id = 0, $where = []){
        $chat = new ChatModel();
        if($id) { $where['id'] = ['<=', $id]; }
        $where['pid'] = 0;
        return $chat->with(['user','reply'])->order('create_time asc')->where($where)->limit(15)->select();
    }

    /**
     * @param $user_id
     * @param $tag_id
     * @return bool
     * @throws
     */
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

        return $result1 && $result2;
    }
}
