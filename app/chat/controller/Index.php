<?php

namespace app\chat\controller;

use app\common\model\sky9th\Chat;
use app\common\model\sky9th\ChatTag;
use app\common\model\sky9th\ChatUser;
use app\chat\logic\Index as ChatLogic;

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
        $list = ChatLogic::index(input('id'));
        return $list ? success('', $list) : error();
    }

    /**
     * 发送信息接口
     * @param $input
     * @param $user_id
     * @return array
     * @throws
     */
    public function msg ($input = [], $user_id = 0) {
        $input = $input ? : input('post.');
        $user_id = $user_id ? : $this->user_id;
        var_dump($input);
        var_dump($user_id);
        if(!$input || !$user_id){
            return error();
        }
        $chat = new Chat();
        $res = $chat->save([
            'user_id' => $user_id,
            'content' => $input['content'],
            'tag' => $input['tag']
        ]);
        var_dump($res);
        if ($res) {
            @ChatLogic::afterMsg($user_id, $input['tag']);
        }
        return success('', $chat->id);
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
