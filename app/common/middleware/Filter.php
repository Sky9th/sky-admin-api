<?php

namespace app\common\middleware;

class Filter
{

    public function handle($request, \Closure $next)
    {
        //$param = input('.');
        unset($_POST['update_time']);
        unset($_POST['create_time']);
        //print_r($param);
        //unset($param['create_time']);
        //unset($param['update_time']);
        //dump($request);
        //print_r($request);

        return $next($request);
    }

}
