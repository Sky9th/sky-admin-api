<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use app\common\validate\Common;

class Config extends Common
{

    protected $rule = [
        'title|名称' => 'require',
        'code|编码' => 'require',
        'param|参数' => 'pass'
    ];

    protected $scene = [
        'delete' => ['']
    ];
}
