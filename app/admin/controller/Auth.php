<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:11
 */
namespace app\admin\controller;

use app\common\logic\UserAuth;
use app\common\model\common\User;

class Auth {

    /**
     * 登陆接口
     */
    public function login(){
        $phone = input('post.username');
        $password = input('post.password');;
        $code = input('post.code');
        if( $code == cache('api_login_code_'.$phone)){
            $model = new User();
            $user = $model->where('username', $phone)->where('type', 1)->find();
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
                return success('登陆成功','',['sessionKey'=>$sessionKey]);
            }else{
                return error('');
            }
        }else{
            return error('验证码错误');
        }
    }

    /**
     * 注册
     * @throws
     */
    public function register(){
        return error('注册通道已关闭');
        $phone = input('post.phone');
        $password = input('post.password');
        $repassword = input('post.repassword');
        $code = input('post.code');
        if($password != $repassword){
            return error('两次密码不一致');
        }
        if( $code === cache('api_login_code_'.$phone) ) {
            cache('api_login_code_'.$phone, null);
            $model = new User();
            $exist = $model->where('phone', $phone)->count();
            if( $exist > 0 ){
                $this->error('该手机号码已注册');
            }else{
                $seed = rand(1000,9999);
                $encrypt_key = config('static.encrypt_key');
                $encode = $password.$seed.$encrypt_key;
                $res = $model->save(['phone'=>$phone, 'type'=>1, 'seed'=>$seed, 'password' => $encode]);
                $id = $model->id;
                if( !$res ){
                    $this->error('请稍后再试');
                }

                $sessionKey = UserLogic::session($id, [
                    'user_id' => $id
                ]);
                $this->success('','', ['sessionKey'=>$sessionKey,'id'=>$id]);
            }
        }else{
            $this->error('验证码错误');
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
     * 登录短信接口
     * @param int phone 手机号码
     * @return array
     */
    public function sms(){
        $phone = input('post.phone');
        $res = Notice::login($phone);
        return $res;
    }

    /**
     * 验证用户登录凭据是否有效
     * @param string sessionKey
     * @return array
     */
    public function check(){
        $session = UserLogic::checkSession();
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
        UserLogic::logout();
        return success();
    }


}
