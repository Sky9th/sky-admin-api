<?php

namespace app\common\middleware;

class Filter
{

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        unset($_POST['update_time']);
        unset($_POST['create_time']);
        return $response;
    }

}
