<?php

namespace app\user\controller;

use app\common\logic\UserAuth;
use \app\common\model\common\User as UserModel;

class User
{
    protected $user_id ;

    public function __construct()
    {
        $this->user_id = request()->user_id;
    }

    /**
     * 用户信息
     * @return array
     * @throws
     */
    public function userInfo () {
        $user = UserAuth::info($this->user_id);
        return $user ? success('', $user) : error();
    }

    /**
     * 设置昵称
     * @return array|\think\response\Json
     */
    public function setNickName () {
        $post = input('post.');
        $user = UserModel::where('id', $this->user_id)->find();
        $user->nickname = $post['nickname'];
        return $user->save() ? success() : error();
    }

}
