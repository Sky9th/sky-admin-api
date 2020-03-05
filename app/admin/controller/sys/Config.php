<?php

namespace app\admin\controller\sys;

use app\admin\controller\Resource;

class Config extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\common\Config();
        $this->validate = new \app\common\validate\sys\Config();
    }

    public function read($id)
    {
        $info = $this->model->where(true)->whereOr('id', $id)->whereOr('code', $id)->find();
        return success('', $info);
    }

    /**
     * 配置设置
     * @param $code
     * @return array
     * @throws
     */
    public function set($code) {
        $param = input('post.param');
        $config = $this->model->where('code', $code)->find();
        $res = false;
        if ($config) {
            $config->param = json_encode($param);
            $res = $config->save();
        }
        return $res ? success() : error();
    }

}
