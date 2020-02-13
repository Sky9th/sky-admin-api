<?php

namespace app\admin\controller\sys;

use app\admin\controller\Resource;
use think\facade\Db;

class Api extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Api();
        $this->validate = new \app\common\validate\sys\Api();
    }

    /**
     * 根据用户Id查询关联管理组
     * @param int $menu_id 用户Id
     * @return array
     * @throws
     */
    public function indexByMenuId ($menu_id) {
        $filter = json_decode(input('filter'), true);
        $alias = [];
        foreach ($filter as $key => $value) {
            $alias['a.'.$key] = $value;
        }
        $this->makeWhere($alias);
        $this->makeOrder();
        $this->getPerPage();
        $visible = $this->model->getVisible();
        $visible[] = 'menu_id';
        $list = Db::table($this->model->getTable())->visible($visible)->alias('a')->join('sys_menu_relation_api b', 'a.id = b.api_id and b.menu_id = '.$menu_id, 'LEFT')->where($this->where)->order($this->order)->paginate($this->list_row);
        return success('', $list);
    }

    /**
     * 移除或添加接口到菜单
     * @return array
     * @throws
     */
    public function modifyMenu () {
        $menu_id = input('post.menu_id');
        $api_id = input('post.api_id');
        $action =  input('post.action');
        $api = $this->model->find($api_id);
        if ($action == 1) {
            $res = $api->menus()->attach($menu_id);
        } else {
            $res = $api->menus()->detach($menu_id);
        }
        return $res ? success() : error();
    }

}
