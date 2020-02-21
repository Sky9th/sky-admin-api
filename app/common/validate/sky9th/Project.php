<?php
namespace app\common\validate\sky9th;

use app\common\validate\Common;

class Project extends Common {

    protected $rule = [
        'title|标题' => 'require|length:2,20',
        'link|链接' => 'length:0,255',
        'cover|封面' => 'integer',
        'pictures|图集' => 'length:0,255',
        'description|简介' => 'length:0,255',
        'content|内容' => 'min:10',
    ];

    protected $scene = [
        'delete' => ['']
    ];

}