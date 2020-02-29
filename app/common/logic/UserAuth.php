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
use think\Exception;
use think\facade\Request;

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
        cache($sessionKey, $data, $expire);
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
     * @return mixed
     * @throws
     */
    static public function register($wechat_user_id, $mpr_user_id)
    {
        $exist = false;
        if ($wechat_user_id) {
            $exist = UserModel::where('wechat_user_id', $wechat_user_id)->where('status', 1)->where('type', 2)->value('id');
        }
        if (!$exist && $mpr_user_id) {
            $exist = UserModel::where('mpr_user_id', $mpr_user_id)->where('status', 1)->where('type', 2)->value('id');
        }

        if ($exist) {
            return $exist;
        } else {
            Db::startTrans();
            try {

                $model = new UserModel();
                $model->save(['type' => 2, 'wechat_user_id' => $wechat_user_id, 'mpr_user_id' => $mpr_user_id]);
                $user_id = $model->id;

                Db::commit();
                return $user_id;
            } catch (Exception $e) {
                Db::rollback();
                throw $e;
            }
        }
    }

    /**
     * 绑定用户手机
     * @param $phone
     * @param $user_id
     * @param $wechat_user_id
     * @param $mpr_user_id
     * @return array|mixed
     * @throws Exception
     * @throws
     */
    static public function phone($phone, $user_id, $wechat_user_id, $mpr_user_id)
    {
        $exist = UserModel::where('phone', $phone)->where('type', 2)->field('id,type,mpr_user_id,wechat_user_id,nickname,realname,phone,mail')->find();
        Db::startTrans();
        try {
            $user = new UserModel();
            $data = [];
            $wechat_user_id ? $data['wechat_user_id'] = $wechat_user_id : [];
            $mpr_user_id ? $data['mpr_user_id'] = $mpr_user_id : [];
            if ($exist) {
                $res = $user->save($data, ['id' => $exist['id']]);
                $user_id = $exist['id'];
            } else {
                $data['phone'] = $phone;
                $res = $user->save($data, ['id' => $user_id]);
            }
            $sessionKey = Request::instance()->header()['sessionkey'];
            $session = cache($sessionKey);
            $session['user_id'] = $user_id;
            cache($sessionKey, $session);
            if (!$res) {
                Db::rollback();
                return error();
            }
            Db::commit();
            return $user_id;
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 获取用户信息
     * @param $user_id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws
     */
    static public function info($user_id)
    {
        $info = UserModel::where('id', $user_id)->field('id,nickname,realname,phone,create_time,update_time,last_login_time,mpr_user_id,wechat_user_id')->with(['mpr' => function ($query) {
            return $query->field('id,nickname');
        }, 'wechat' => function ($query) {
            return $query->field('id,nickname,headimgurl');
        }])->find();
        return $info;
    }


}
