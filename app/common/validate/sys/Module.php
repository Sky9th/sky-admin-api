<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use think\Validate;

class Module extends Validate
{

    protected $rule = [
        'title|名称' => 'require',
        'name|标识' => 'require',
        //'type|模块类型' => 'require',
        //'src|真实路径' => 'require',
        'visible|是否可见' => 'require',
        //'module|模块' => 'require',
    ];

    protected $scene = [
        'normal' => ['name', 'title', 'intro', 'type', 'icon', 'color', 'visible', 'module'],
        'resource' => ['name', 'title', 'intro', 'type', 'src', 'icon', 'color', 'visible', 'resource', 'log', 'module'],
    ];

}
