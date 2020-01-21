<?php

namespace app\admin\controller;

class Menu extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Menu();
        $this->validate = new \app\common\validate\sys\Menu();
    }

}
