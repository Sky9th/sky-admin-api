<?php

namespace app\admin\controller;

class Role extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Role();
        $this->validate = new \app\common\validate\sys\Role();
    }

}
