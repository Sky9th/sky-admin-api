<?php
/**
 * Author: Sky9th
 * Date: 2017/4/2
 * Time: 17:11
 */

namespace app\common\model\sys;

use app\common\model\Sys;

class Role extends Sys
{
    protected $table = 'sys_role';
    protected $resultSetType = 'collection';

    public function menus () {
        return $this->belongsToMany('menu', 'sys_role_relation_menu','menu_id','role_id');
    }
}
