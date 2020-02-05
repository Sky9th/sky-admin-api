<?php
namespace app\common\validate\sky9th;

use app\common\validate\Common;

class Project extends Common {

    protected $rule = [
        'title|标题' => 'require|length:2,20',
        'link|链接' => '',
        'description|简介' => 'length:0,255',
        'content|内容' => '',
    ];

    protected $scene = [
        'delete' => ['']
    ];

}