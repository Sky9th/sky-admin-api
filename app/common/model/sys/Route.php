<?php
namespace app\common\model\sys;

use app\common\model\Sys;

class Route extends Sys {

    protected $table = 'sys_route';
    protected $visible = ['id','pid','name','title','permission','path','component','component_path'];
    public $order = 'sort desc, id desc';

    public function getMetaAttr ($value, $data) {
        return ['title'=>$data['title'], 'cache'=>boolval($data['cache'])];
    }
    
    public function setPermissionAttr ($value) {
        return 'p_'.$value;
    }
}