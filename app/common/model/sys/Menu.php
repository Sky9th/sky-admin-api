<?php
namespace app\common\model\sys;

use app\common\model\Sys;

class Menu extends Sys {

    protected $table = 'sys_menu';
    protected $visible = ['id','type','pid','title','permission','icon','path','visible','sort'];
    public $order = 'sort desc, id desc';

    public function apis () {
        return $this->belongsToMany('Api','sys_menu_relation_api','api_id','menu_id');
    }

    public function setPermissionAttr ($value) {
        return 'p_'.$value;
    }

}