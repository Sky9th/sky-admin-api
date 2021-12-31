<?php

namespace app\chat\controller;

use app\common\model\sky9th\Chat;

class Index
{
    protected $user_id ;

    public function __construct()
    {
        $this->user_id = request()->user_id;
    }

    /**
     * 查询条目
     * @return array
     * @throws
     */
    public function index () {
        $chat = new Chat();
        $id = input('id');
        $where = [];
        if($id) { $where['id'] = ['<=', $id]; }
        $where['pid'] = 0;
        $list = $chat->with(['user','reply'])->order('create_time asc')->where($where)->limit(15)->select();
        return success('', $list);
    }

    /**
     * 发送信息
     * @return array
     */
    public function msg () {
        $post = input('post.');
        $chat = new Chat();
        $chat->save([
            'user_id' => $this->user_id,
            'content' => $post['content'],
            'type' => $post['type']
        ]);
        $id = $chat->getLastInsID();
        return success('', $id);
    }

}
