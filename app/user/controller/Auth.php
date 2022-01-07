<?php

namespace app\user\controller;

use app\common\logic\UserAuth;
use app\common\logic\Verify;
use app\common\model\common\User as UserModel;
use app\user\validate\User as UserValidate;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\Response;

class Auth
{

    /**
     * 注册
     * @return array|Response
     * @throws \Exception
     */
    public function register () {
        $post = input('post.');
        return $this->handleInfo(array('app\common\logic\UserAuth', 'register'), 'register', $post);
    }

    /**
     * @return array
     */
    public function setPassword () {
        $post = input('post.');
        return $this->handleInfo(array('app\common\logic\UserAuth', 'resetPassword'), 'reset', $post);
    }

    /**
     * @param $callback
     * @param $post
     * @param $scene
     * @return array
     */
    private function handleInfo ($callback, $scene, $post) {
        $mail = $post['mail'];
        if ($post['code'] && $post['code'] == cache('VERIFY_EMAIL_CODE_'.$mail)) {
            $validate = new UserValidate();
            if (!$validate->scene($scene)->check($post)) {
                return error($validate->getError());
            } else {
                $user_id = call_user_func($callback, $post);
                if (!$user_id) { return error(); }
                $sessionKey = UserAuth::session($user_id, ['user_id' => $user_id], 'eft', 3600 * 24 * 15);
                cache('VERIFY_EMAIL_CODE_'.$mail, null);
                return $sessionKey ? success('', ['sessionKey' => $sessionKey, 'mail'=>$mail]) : error();
            }
        } else {
            return error('验证码错误');
        }
    }

    /**
     * 登陆接口
     * @throws
     */
    public function login(){
        $mail = input('post.mail');
        $password = input('post.password');
        $fingerprint = input('fingerprint');
        $code = input('post.code');
        if ($fingerprint && captcha_check($fingerprint, $code)) {
            $model = new UserModel();
            $user = $model->where('mail', $mail)->where('type', 2)->find();
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
                return success('登陆成功', ['sessionKey' => $sessionKey, 'mail' => $user['mail']]);
            }else{
                return error();
            }
        }else{
            return error('验证码错误');
        }
    }

    /**
     * 图形验证码
     * @return array
     */
    public function captcha () {
        $fingerprint = input('post.fingerprint');
        $base64 = Verify::image($fingerprint);
        return success('', $base64);
    }

    public function aliVerifyCode () {
        $post = input('post.');

        $email = $post['mail'];
        $rand = rand(100000,999999);
        $break = cache('VERIFY_EMAIL_BREAK_'.$email);
        if ($break) {
            return error('请等待60秒后再发送');
        }
        cache('VERIFY_EMAIL_CODE_'.$email, $rand, 60 * 15);
        cache('VERIFY_EMAIL_BREAK_'.$email, true, 60);
        try {
            $accessKeyId = env('alibaba.access_key_id', '');
            $accessKeySecret = env('alibaba.access_key_secret', '');
            AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
                ->regionId('cn-hangzhou')
                ->asDefaultClient();

            $result = AlibabaCloud::rpc()
                ->product('Dm')
                // ->scheme('https') // https | http
                ->version('2015-11-23')
                ->action('SingleSendMail')
                ->method('POST')
                ->host('dm.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'AccountName' => 'service@sky9th.cn',
                        'AddressType' => "1",
                        'ReplyToAddress' => "false",
                        'ToAddress' => $email,
                        'Subject' => '邮箱验证码 —— by SkyAdmin',
                        'TextBody' => '您的验证码:'. $rand. ',有效时间15分钟'
                    ],
                ])
                ->request();
            return success();
        } catch (ClientException $e) {
            return error($e->getErrorMessage());
        } catch (ServerException $e) {
            return error($e->getErrorMessage());
        }
    }

}
