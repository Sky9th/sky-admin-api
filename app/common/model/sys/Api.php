<?php
namespace app\common\model\sys;

use app\common\model\Sys;

class Api extends Sys {

    protected $table = 'sys_api';
    protected $visible = ['id','title','path','method','description'];

}