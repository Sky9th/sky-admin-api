<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use think\Validate;

class Role extends Validate
{

    protected $rule = [
        'id|编号' => 'lock',
        'title|名称' => 'require',
        'permission|标识' => 'require',
    ];

    protected $scene = [
        'delete' => ['id']
    ];

    /**
     * 超级管理组锁定修改
     * @param $value
     * @param $rule
     * @param array $data
     * @return string
     */
    public function lock ($value, $rule, $data=[]){
        if(!is_array($value)){
            $value = explode(',', $value);
        }
        if (in_array(1, $value)) {
            return '超级管理组不允许修改以及删除';
        }
        return true;
    }

}
