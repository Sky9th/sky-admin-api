<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use think\Validate;

class Menu extends Validate
{

    protected $rule = [
        'title|标题' => 'require|length:4,10',
        'path|路径' => 'require',
        'icon|图标' => 'require',
        'permission|权限标识' => 'require',
        'sort|排序' => 'integer',
        'type|类型' => 'integer',
    ];

    protected $scene = [
        'delete' => ['']
    ];

}
