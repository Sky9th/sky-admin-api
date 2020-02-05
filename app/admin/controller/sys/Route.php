<?php
namespace app\admin\controller\sys;

use app\admin\controller\Resource;

class Route extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Route();
        $this->validate = new \app\common\validate\sys\Route();
    }

    public function index()
    {
        $this->getPerPage();
        $this->makeOrder();
        $this->makeWhere();
        $list = $this->model->where($this->where)->order($this->order)->select()->toArray();
        return success('', list_to_tree($list));
    }


}
