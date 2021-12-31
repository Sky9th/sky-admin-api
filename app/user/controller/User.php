<?php

namespace app\user\controller;

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
        $user = \app\common\model\common\User::where('id', $this->user_id)->find();
        return $user ? success('', $user) : error();
    }

}
