<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:13
 */

namespace app\common\model\common;

use app\common\model\Common;
use think\Model;

class User extends Common
{

    protected $table = 'common_user';
    protected $resultSetType = 'collection';
    protected $readonly = ['username'];
    protected $visible = ['id','type','nickname','avatar','realname','phone','mail','username','last_login_time','status'];
    protected $type = [
        'last_login' => 'timestamp',
    ];

    public function roles() {
        return $this->belongsToMany('app\common\model\sys\Role', 'sys_user_relation_role', 'role_id','user_id');
    }

    public function mpr() {
        return $this->belongsTo('app\common\model\common\MprUser','mpr_user_id');
    }

    public function wechat() {
        return $this->belongsTo('app\common\model\common\WechatUser','wechat_user_id');
    }

    public function getMailAttr($value) {
        // TODO mask mail
        return $value;
    }

    public function setPasswordAttr ($value, $data) {
        $seed =  rand(1000, 9999);
        $this->set('seed', $seed);
        $encrypt_key = config('static.password_encrypt_key');
        return md5($value.$seed.$encrypt_key);
    }

}
