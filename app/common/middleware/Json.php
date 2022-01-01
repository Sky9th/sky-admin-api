<?php

namespace app\common\middleware;

use think\facade\Request;

class Json
{

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        if(Request::isOptions()){
            return $response;
        }
        $code = $response->getCode();
        if ($code == 200) {
            return json($response->getData());
        } else {
            return json($response->getData(), 400);
        }
    }

}
