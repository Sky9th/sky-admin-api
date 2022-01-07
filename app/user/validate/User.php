<?php
/**
 * Created by PhpStorm.
 * user: Sky9th
 * Date: 2017/4/19
 * Time: 13:47
 */

namespace app\user\validate;

class User extends \app\common\validate\sys\User
{

    protected $rule = [
        'mail|邮箱' => 'require|unique:common_user|email',
        'password|密码' => 'requireWith:mail|conflict',
        'repassword|确认密码'=>'requireWith:mail|confirm:password',
    ];

    protected $scene = [
        'register' => ['mail','password','repassword'],
        'reset' => ['password','repassword']
    ];

}
