<?php

namespace app\app\middleware;

class Json
{

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $code = $response->getCode();
        if ($code === 200) {
            return json($response->getData());
        } else {
            return $response;
        }
    }

}
