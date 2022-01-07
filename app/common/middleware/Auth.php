<?php
namespace app\common\middleware;

use app\admin\logic\AdminAuth;
use app\common\logic\UserAuth;

class Auth {

    public function handle($request, \Closure $next)
    {
        $header = $request->header();
        $session_key = $header['session-key'];
        if ($session_key) {
            $session = UserAuth::checkSession($session_key);
            if($session){
                $request->user_id = $session['user_id'];
            }else{
                return json(error('登录凭证已失效', [], 0), 401);
            }

            $app = app('http')->getName();
            if ($app == 'admin') {
                $user_id = $session['user_id'];
                $adminAuth = new AdminAuth($user_id);
                if (!$adminAuth->hasPermission()) {
                    return json(error('您无相关权限', [], 0), 403);
                }
            }
        }
        return $next($request);
    }

}