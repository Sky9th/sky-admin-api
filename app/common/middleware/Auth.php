<?php
namespace app\common\middleware;

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
            }
        }
        return $next($request);
    }


}