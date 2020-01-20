<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:13
 */

namespace app\common\model\common;

use app\common\model\Common;

class User extends Common
{

    protected $table = 'common_user';
    protected $resultSetType = 'collection';
    protected $type = [
        'last_login' => 'timestamp',
    ];

    public function roles() {
        return $this->belongsToMany('app\common\model\sys\Role', 'sys_user_relation_role', 'role_id','user_id');
    }

}
