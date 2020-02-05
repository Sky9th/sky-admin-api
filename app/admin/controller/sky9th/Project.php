<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:11
 */
namespace app\admin\controller\sky9th;

use app\admin\controller\Resource;

class Project extends Resource {

    public function __construct()
    {
        $this->model = new \app\common\model\sky9th\Project();
        $this->validate = new \app\common\validate\sky9th\Project();
    }

}
