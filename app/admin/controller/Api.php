<?php

namespace app\admin\controller;

class Api extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Api();
        $this->validate = new \app\common\validate\sys\Api();
    }

}
