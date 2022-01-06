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
     * 头像列表
     * @return array
     */
    public function avatar () {
        $path = public_path().'/static/avatar';
        $folders = scandir($path);
        $avatars = [];
        unset($folders[0]);
        unset($folders[1]);
        foreach ($folders as $folder) {
            $list = scandir($path.DIRECTORY_SEPARATOR.$folder);
            unset($list[0]);
            unset($list[1]);
            $avatars[$folder] = $list;
        }
        return success('', $avatars);
    }

    /**
     * 设置昵称
     * @return array|\think\response\Json
     * @throws
     */
    public function setNickName () {
        $post = input('post.');
        $user = UserModel::where('id', $this->user_id)->find();
        $user->nickname = $post['nickname'];
        return $user->save() ? success() : error();
    }

    /**
     * 设置头像
     * @return array|\think\response\Json
     * @throws
     */
    public function setAvatar () {
        $post = input('post.');
        $user = UserModel::where('id', $this->user_id)->find();
        $user->avatar = $post['avatar'];
        return $user->save() ? success() : error();
    }

}
