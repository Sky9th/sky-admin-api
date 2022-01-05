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
        $data = $response->getData();
        if ($data['code'] == 0) {
            return json($data);
        } else {
            return json($data, 400);
        }
    }

}
