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
        'path|路径' => 'require|combineUnique',
        'method|方法' => 'require'
    ];

    protected $scene = [
        'delete' => ['']
    ];

    // 路径与方法组合唯一
    protected function combineUnique($value, $rule, $data=[])
    {
        $res = \app\common\model\sys\Api::where('path', $data['path'])->where('path', $data['method'])->count();
        if($res > 0){
            return '路径与方法的组合必须唯一';
        }
        return true;
    }
}
