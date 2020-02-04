<?php
namespace app\common\model\sys;

use app\common\model\Sys;

class Menu extends Sys {

    protected $table = 'sys_menu';
    protected $visible = ['id','type','pid','title','permission','icon','path','visible','sort'];
    public $order = 'sort desc, id desc';

}