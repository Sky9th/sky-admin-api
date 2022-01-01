<?php

namespace app\chat\controller;

use app\common\model\sky9th\Chat;
use app\common\model\sky9th\ChatTag;
use app\common\model\sky9th\ChatUser;

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
     * 发送信息接口
     * @return array
     * @throws
     */
    public function msg () {
        $post = input('post.');
        $chat = new Chat();
        $res = $chat->save([
            'user_id' => $this->user_id,
            'content' => $post['content'],
            'tag' => $post['tag']
        ]);
        $id = $chat->getLastInsID();
        if ($res) {
            @\app\chat\logic\Index::afterMsg($this->user_id, $post['tag']);
        }
        return success('', $id);
    }

    /**
     * 人群接口
     * @return array
     * @throws
     */
    public function people () {
        $chat = new ChatUser();
        $list = $chat->paginate(15);
        return success('', $list);
    }

    /**
     * 人群接口
     * @return array
     * @throws
     */
    public function Tag () {
        $chat = new ChatTag();
        $list = $chat->where('status', 1)->select();
        return success('', $list);
    }

}
