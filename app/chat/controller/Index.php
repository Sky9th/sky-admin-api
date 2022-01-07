<?php

namespace app\chat\controller;

use app\common\model\sky9th\Chat;
use app\common\model\sky9th\ChatTag;
use app\common\model\sky9th\ChatUser;
use app\chat\logic\Index as ChatLogic;
use think\Db;

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
        if(!$input || !$user_id){
            return error();
        }
        $chat = new Chat();
        $res = $chat->save([
            'user_id' => $user_id,
            'content' => $input['content'],
            'tag' => $input['tag']
        ]);
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
        $nickname = input('get.nickname','','');
        $where = [];
        if ($nickname) {
            $where[] = ['b.nickname','like','%'.$nickname.'%'];
        }
        $chat = new ChatUser();
        $list = $chat->field('b.id,nickname,avatar')->where($where)->alias('a')->join('common_user b', 'a.user_id = b.id')->paginate(15);
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
