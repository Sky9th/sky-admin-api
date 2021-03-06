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
    protected $visible = ['id','type','nickname','realname','phone','mail','username','last_login_time','status'];
    protected $type = [
        'last_login' => 'timestamp',
    ];

    public function roles() {
        return $this->belongsToMany('app\common\model\sys\Role', 'sys_user_relation_role', 'role_id','user_id');
    }

    public function setPasswordAttr ($value, $data) {
        $seed =  rand(1000, 9999);
        $this->set('seed', $seed);
        $encrypt_key = config('static.password_encrypt_key');
        return md5($value.$seed.$encrypt_key);
    }

}
