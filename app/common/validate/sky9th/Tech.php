<?php
namespace app\common\validate\sky9th;

use app\common\validate\Common;

class Tech extends Common {

    protected $rule = [
        'title|标题' => 'require|length:2,20',
        'version|简介' => 'require|length:0,255',
        'link|链接' => 'require|length:0,255',
    ];

    protected $scene = [
        'delete' => ['']
    ];

}