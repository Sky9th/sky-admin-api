<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use think\Validate;

class Route extends Validate
{
    protected $rule = [
        'title|名称' => 'require',
        'name|编码' => 'require',
        'path|路径' => 'require',
        'permission|权限标识' => 'require',
        'component|组件标识' => 'require',
        'sort|编码' => 'inter',
        'is_lock|编码' => 'bool',
        'cache|编码' => 'bool',
    ];

    protected $scene = [
        'delete' => ['']
    ];

}
