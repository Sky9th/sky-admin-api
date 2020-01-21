<?php

namespace app\admin\controller;

class Route extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Route();
        $this->validate = new \app\common\validate\sys\Route();
    }

}
