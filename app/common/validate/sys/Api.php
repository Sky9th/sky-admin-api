<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use think\Validate;

class Api extends Validate
{

    protected $rule = [
        'title|名称' => 'require',
        'path|路径' => 'require',
        'method|方法' => 'require'
    ];

    protected $scene = [
        'delete' => ['']
    ];

}
