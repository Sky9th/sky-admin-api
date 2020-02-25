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
        'permission|权限标识' => 'require|length:0,255',
        'component|组件标识' => 'require',
        'sort|排序' => 'integer',
        'is_lock|锁定' => 'bool',
        'cache|缓存' => 'bool',
    ];

    protected $scene = [
        'delete' => ['']
    ];

}
