<?php
namespace app\common\model\sys;

use app\common\model\Sys;

class Menu extends Sys {

    protected $table = 'sys_menu';
    protected $visible = ['id','pid','title','icon','path','visible'];

}