<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\common\validate\sys;

use think\Validate;

class User extends Validate
{

    protected $rule = [
        'id' => 'lock',
        'username|用户名' => 'require|length:4,25',
        'password|密码' => 'requireWith:username|conflict',
        'repassword|确认密码'=>'requireWith:username|confirm:password',
        'mail|邮箱' => 'email',
    ];

    protected $scene = [
        'save' => ['username','password','repassword'],
        'update' => ['password','repassword'],
        'delete' => ['id']
    ];

    /**
     * 密码验证
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool
     */
    protected function conflict ($value, $rule, $data = []) {
        if($value){
            $r2='/[a-z]/';  //lowercase
            $r3='/[0-9]/';  //numbers
            $r4='/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char

            if(preg_match_all($r2,$value, $o)<1) {
                return "密码必须包含至少一个小写字母，请返回修改！";
            }
            if(preg_match_all($r3,$value, $o)<1) {
                return "密码必须包含至少一个数字，请返回修改！";
            }
            /*if(preg_match_all($r4,$value, $o)<1) {
                return "密码必须包含至少一个特殊符号：[~!@#$%^&*()\-_=+{};:<,.>?]，请返回修改！";
            }*/
            if(strlen($value)<6) {
                return "密码必须包含至少含有6个字符，请返回修改！";
            }
            return true;
        }else{
            return true;
        }
    }

    /**
     * 超级管理组锁定修改
     * @param $value
     * @param $rule
     * @param array $data
     * @return string
     */
    protected function lock ($value, $rule, $data=[]){
        if( in_array(1, $value) ){
            return '超级管理组不允许修改以及删除';
        }
        return true;
    }

}
