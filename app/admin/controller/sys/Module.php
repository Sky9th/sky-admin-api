<?php

namespace app\admin\controller\sys;

use app\admin\controller\Resource;

class Module extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Module();
        $this->validate = new \app\common\validate\sys\Module();
    }

}
