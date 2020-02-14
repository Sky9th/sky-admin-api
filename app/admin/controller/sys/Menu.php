<?php
namespace app\admin\controller\sys;

use app\admin\controller\Resource;
use app\common\model\sys\Role;

class Menu extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Menu();
        $this->validate = new \app\common\validate\sys\Menu();
    }

    /**
     * 查询菜单以及其下属接口
     * @return mixed
     * @throws
     */
    public function indexWithApi () {
        $menus = $this->model->visible(['id','pid','type','title','apis'])->with(['apis' => function ($query) {
            $query->visible(['id','type','title']);
        }])->select()->toArray();
        foreach ($menus as $menu) {
            if (count($menu['apis']) > 0) {
                foreach ($menu['apis'] as $api) {
                    $api['pid'] = $menu['id'];
                    $api['type'] = 2;
                    $api['id'] = 'api_'. $api['id'];
                    $menus[] = $api;
                }
            }
        }
        return list_to_tree($menus);
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
