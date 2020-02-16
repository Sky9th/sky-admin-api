<?php
namespace app\admin\logic;

use app\common\model\common\User;
use app\common\model\sys\Api;
use app\common\model\sys\Menu;
use app\common\model\sys\Role;
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
        list($roles, $menus, $apis, $permission) = $this->getPermissionAndMenuAndApi();

        $route = Route::where('permission', 'in', $permission)->append(['meta'])->select();
        $route = list_to_tree($route->toArray());

        return [
            'userName' => $name,
            'userRoles' => $roles,
            'userPermissions' => $permission,
            'accessMenus' => $menus,
            'accessRoutes' => $route,
            'accessApi' => $apis,
            'avatarUrl' => ''
        ];
    }

    /**
     * 获取权限节点以及菜单
     * @return array
     * @throws
     */
    private function getPermissionAndMenuAndApi () {
        $role_ids = $this->user->roles->column('id');
        if (in_array(SUPER_ROLE_ID, $role_ids)) {
            $roles = Role::column('permission');
            $menu_ids = Menu::column('id');
            $api_ids = Api::column('id');
        }else{
            $roles = $this->user->roles->column('permission');
            $menu_ids = Db::table('sys_role_relation_menu')->where('role_id','in',$role_ids)->column('menu_id');
            $api_ids = Db::table('sys_role_relation_api')->where('role_id','in',$role_ids)->column('api_id');
        }
        $menus = Menu::where('id','in', $menu_ids)->select();
        $apis = Api::where('id','in', $api_ids)->select();
        $permission = [];
        foreach ($menus as $key => $value) {
            $permission[] = $value['permission'];
        }
        foreach ($apis as $key => $value) {
            $permission[] = $value['permission'];
        }
        $menus = list_to_tree($menus->toArray());
        return [$roles, $menus, $apis, $permission];
    }

    /**
     * 检查是否具有接口权限
     * @param bool $permission
     * @return bool
     */
    public function hasPermission ($permission = false) {
        if ($permission) {
            list($roles, $menus, $apis, $permissions) = $this->getPermissionAndMenuAndApi();
            return in_array($permission, $permissions);
        } else {
            $route = request()->rule()->getRule();
            $while_list = [
                'login',
                'user/getPermissionInfo'
            ];
            if (in_array($route, $while_list)) {
                return true;
            } else{
                list($roles, $menus, $apis, $permissions) = $this->getPermissionAndMenuAndApi();
                $api_path = array_column($apis->toArray(), 'path');
                $expected = [];
                //自适应两种路由规则
                foreach ($api_path as $item) {
                    $_tmp = preg_replace('/\/:(.*)\/?/', '/<$1>', $item);
                    $_tmp = preg_replace('/\/\[:(.*)]\/?/', '/<$1?>', $_tmp);
                    $expected[] = $_tmp;
                }
                $api_path = array_merge($expected, $api_path);
                return in_array($route, $api_path);
            }
        }
    }

}