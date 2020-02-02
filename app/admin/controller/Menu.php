<?php

namespace app\admin\controller;
use app\common\model\sys\Role;

class Menu extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Menu();
        $this->validate = new \app\common\validate\sys\Menu();
    }

    /**
     * 查询所有菜单
     * @return array
     * @throws
     */
    public function index()
    {
        $list = $this->model->select()->toArray();
        return success('', list_to_tree($list));
    }

    /**
     * 获取管理组的相关权限
     * @param $role_id
     * @return array
     * @throws
     */
    public function indexByRoleId ($role_id) {
        $role = Role::find($role_id);
        return success('',$role->menus);
    }

}
