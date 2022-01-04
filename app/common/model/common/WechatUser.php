<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/6/19
 * Time: 15:46
 */

namespace app\common\model\common;

use think\Model;

class WechatUser extends Model
{

    protected $table = 'common_wechat_user';

    public function getNicknameAttr($value)
    {
        return json_decode($value);
    }

    public function setNicknameAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 注册微信用户
     * @param $user
     * @return false|int
     */
    public function register($user)
    {
        $exist = $this->where('openid', $user['openid'])->value('id');
        if ($exist) {
            $res = $this->where(['openid' => $user['openid']])->save($user);
            if ($res) {
                $id = $exist;
                return $id;
            }
        } else {
            $res = $this->save($user);
            if ($res) {
                $id = $this->id;
                return $id;
            }
        }
        return false;
    }

    /**
     * 用户取消关注
     * @param $openid
     * @return false|int
     */
    public function unsubscribe($openid)
    {
        return $this->where(['openid' => $openid])->save(['subscribe' => 0]);
    }

}
