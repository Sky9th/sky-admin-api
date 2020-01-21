<?php
namespace app\admin\logic;

use app\common\model\common\User;
use app\common\model\sys\Api;
use app\common\model\sys\Menu;
use app\common\model\sys\Route;
use think\facade\Db;

/**
 * 管理员身份权限相关逻辑层
 * Class AdminAuth
 * @package app\admin\logic
 */
class AdminAuth {

    private $user_id;
    private $user;

    /**
     * AdminAuth constructor.
     * @param $user_id int 用户ID
     * @throws
     */
    public function __construct($user_id){
        $this->user_id = $user_id;
        $this->user = User::where('id', $user_id)->find();
        if(!$this->user){
            return error('');
        }
    }

    /**
     * 获取相关权限信息
     * @return array
     * @throws
     */
    public function getPermissionInfo () {

        $name = $this->user['nickname'] ? : $this->user['username'];
        list($roles, $menus, $permission) = $this->getPermissionAndMenu();

        $route = Route::where('permission', 'in', $permission)->append(['meta'])->select();
        $route = list_to_tree($route->toArray());

        $menu_ids = [];
        foreach ($menus as $menu) {
            $menu_ids[] = $menu['id'];
        }
        $api = Api::where('id','in',$menu_ids)->select();
        return [
            'userName' => $name,
            'userRoles' => $roles,
            'userPermissions' => $permission,
            'accessMenus' => $menus,
            'accessRoutes' => $route,
            'accessApi' => $api,
            'avatarUrl' => ''
        ];
    }

    /**
     * 获取权限节点以及菜单
     * @return array
     * @throws
     */
    private function getPermissionAndMenu () {
        $roles = $this->user->roles->column('permission');
        $role_ids = $this->user->roles->column('id');
        $menu_ids = Db::table('sys_role_relation_menu')->where('role_id','in',$role_ids)->column('menu_id');
        $menus = Menu::where('id','in', $menu_ids)->select();
        $permission = [];
        foreach ($menus as $key => $value) {
            $permission[] = $value['permission'];
        }
        $menus = list_to_tree($menus->toArray());
        return [$roles, $menus, $permission];
    }

}