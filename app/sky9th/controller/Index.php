<?php

namespace app\sky9th\controller;

use app\common\model\sky9th\Chat;

class Index
{

    /**
     * 查询条目(废弃)
     * @return array
     * @throws
     */
    public function index () {
        $good = new Chat();
        return success('', $good->select());
    }

}
