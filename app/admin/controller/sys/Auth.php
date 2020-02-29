<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:11
 */
namespace app\admin\controller\sys;

use app\common\logic\UserAuth;
use app\common\model\common\User;

class Auth {

    /**
     * 登陆接口
     */
    public function login(){
        $username = input('post.username');
        $password = input('post.password');;
        $code = input('post.code');
        if (captcha_check('', $code)) {
            $model = new User();
            $user = $model->where('username', $username)->where('type', 1)->find();
            if( !$user ){
                return error('账号未注册');
            }
            if( $user['status'] != '1'){
                return error('账号已停用');
            }
            $seed = $user['seed'];
            $encrypt_key = config('static.password_encrypt_key');
            $encode = md5($password.$seed.$encrypt_key);
            if( $user['password'] != $encode ){
                return error('密码不正确');
            }
            $id = $user['id'];
            if( $id ){
                $sessionKey = UserAuth::session($id, [
                    'user_id' => $id
                ], 'web');
                return success('登陆成功', ['sessionKey' => $sessionKey, 'username' => $user['username'], 'nickname' => $user['nickname']]);
            }else{
                return error('');
            }
        }else{
            return error('验证码错误');
        }
    }

    /**
     * 重置密码
     * @return array
     * @throws
     */
    public function reset(){
        $phone = input('post.phone');
        $password = input('post.password');
        $repassword = input('post.repassword');
        $code = input('post.code');
        if($password != $repassword){
            return error('两次密码不一致');
        }
        if( Notice::checkLogin($code, $phone) ) {
            $model = new User();
            $user = $model->where('phone', $phone)->where('type', 1)->find();
            if( !$user ){
                return error('账号未注册');
            }
            if( $user['status'] != '1'){
                return error('账号已停用');
            }
            $seed = rand(1000,9999);
            $encrypt_key = config('static.encrypt_key');
            $encode = $password.$seed.$encrypt_key;
            $res = $model->save(['seed'=>$seed, 'password' => $encode],['id'=>$user['id']]);
            if( !$res ){
                $this->error('请稍后再试');
            }
            return success('','');
        }else{
            return error('验证码错误');
        }
    }

    /**
     * 验证用户登录凭据是否有效
     * @param string sessionKey
     * @return array
     * @throws
     */
    public function check(){
        $session = UserAuth::checkSession();
        if( $session ){
            $user_id = $session['user_id'];
            $user = User::where('id', $user_id)->find();
            if($user && $user['status'] == '1'){
                $this->success(lang('session valid'));
            }
        }
        return error(lang('session invalid'));
    }

    /**
     * 注销登录状态、销毁用户登录凭据
     * @return array
     */
    public function logout(){
        UserAuth::logout();
        return success();
    }


}
