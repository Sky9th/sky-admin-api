<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/4/12
 * Time: 10:30
 */

namespace app\common\logic;

use app\common\model\common\User as UserModel;
use think\Db;
use think\facade\Request;
use think\Model;

class UserAuth
{

    /**
     * 注册登录状态
     * @param $user_id
     * @param $data array 状态数据
     * @param $prefix
     * @param $expire int 过期时间
     * @return string
     */
    static public function session($user_id, $data, $prefix = '', $expire = 86400)
    {
        $session = time() . uniqid() . $user_id;
        $data['expire_time'] = time() + $expire;
        $data['expire'] = $expire;
        $sessionKey = $prefix . $session;
        cache($sessionKey, $data);
        UserModel::update(['last_login_time' => time(), 'last_login_session' => $sessionKey], ['id' => $user_id]);
        return $sessionKey;
    }

    /**
     * 检查登录状态是否有效
     * @param $sessionKey
     * @return bool|array
     */
    static public function checkSession($sessionKey = false)
    {
        if (!$sessionKey) {
            $sessionKey = Request::instance()->header()['sessionkey'];
        }
        $session = cache($sessionKey);
        //TODO 检测是否已被登录
        if (!isset($session['expire_time'])) {
            return false;
        }
        $expire_time = $session['expire_time'];
        $now = time();
        if ($expire_time - $now <0) {
            return false;
        } else {
            $session['expire_time'] = $now +  $session['expire'];
            cache($sessionKey, $session, $session['expire']);
            return $session;
        }
    }

    /**
     * 退出登录
     * @param $sessionKey
     * @return bool
     */
    static public function logout($sessionKey = false)
    {
        if (!$sessionKey) {
            $sessionKey = Request::instance()->header()['sessionkey'];
        }
        cache($sessionKey, null);
        return true;
    }

    /**
     * 注册用户
     * @param $wechat_user_id
     * @param $mpr_user_id
     * @param $data
     * @return mixed
     * @throws
     */
    static public function register($data = [], $wechat_user_id = 0, $mpr_user_id = 0)
    {
        $exist = false;
        $user = new UserModel();
        if ($wechat_user_id) {
            $exist = $user->where('wechat_user_id', $wechat_user_id)->where('status', 1)->where('type', 2)->value('id');
        }
        if (!$exist && $mpr_user_id) {
            $exist = $user->where('mpr_user_id', $mpr_user_id)->where('status', 1)->where('type', 2)->value('id');
        }

        if ($exist) {
            return $exist;
        } else {
            $user->startTrans();
            try {
                $model = new UserModel();
                $model->save(array_merge($data, ['type' => 2, 'wechat_user_id' => $wechat_user_id, 'mpr_user_id' => $mpr_user_id]));
                $user_id = $model->id;
                $user->commit();
                return $user_id;
            } catch (\Exception $e) {
                $user->rollback();
                throw $e;
            }
        }
    }

    /**
     * 重置密码
     * @param $data
     * @return mixed
     * @throws
     */
    static public function resetPassword($data)
    {
        $user = new UserModel();
        $exist = $user->where('mail', $data['mail'])->where('status', 1)->where('type', 2)->find();
        $result = $exist->save(['password' => $data['password']]);
        return $result ? $exist->id : false;
    }

    /**
     * 绑定用户手机
     * @param $phone
     * @param $user_id
     * @param $wechat_user_id
     * @param $mpr_user_id
     * @return array|mixed
     * @throws \Exception
     */
    static public function phone($phone, $user_id, $wechat_user_id, $mpr_user_id)
    {
        $db = new Db();
        $user = new UserModel();
        $exist = $user->where('phone', $phone)->where('type', 2)->field('id,type,mpr_user_id,wechat_user_id,nickname,realname,phone,mail')->find();
        $db->startTrans();
        try {
            $user = new UserModel();
            $data = [];
            $wechat_user_id ? $data['wechat_user_id'] = $wechat_user_id : [];
            $mpr_user_id ? $data['mpr_user_id'] = $mpr_user_id : [];
            if ($exist) {
                $res = $user->where(['id' => $exist['id']])->save($data);
                $user_id = $exist['id'];
            } else {
                $data['phone'] = $phone;
                $res = $user->where(['id' => $user_id])->save($data);
            }
            $sessionKey = Request::instance()->header()['sessionkey'];
            $session = cache($sessionKey);
            $session['user_id'] = $user_id;
            cache($sessionKey, $session);
            if (!$res) {
                $db->rollback();
                return error();
            }
            $db->commit();
            return $user_id;
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    /**
     * 获取用户信息
     * @param $user_id
     * @return array|string|Model|null
     * @throws
     */
    static public function info($user_id)
    {
        $user = new UserModel();
        $info = $user->where('id', $user_id)->field('id,mail,nickname,avatar,realname,phone,create_time,update_time,last_login_time,mpr_user_id,wechat_user_id')->with(['mpr' => function ($query) {
            return $query->field('id,nickname');
        }, 'wechat' => function ($query) {
            return $query->field('id,nickname,headimgurl');
        }])->find();
        return $info;
    }


}
