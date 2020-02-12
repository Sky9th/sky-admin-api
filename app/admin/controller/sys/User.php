<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:11
 */
namespace app\admin\controller\sys;

use app\admin\controller\Resource;
use app\admin\logic\AdminAuth;
use think\facade\Db;

class User extends Resource {

    public function __construct()
    {
        $this->model = new \app\common\model\common\User();
        $this->validate = new \app\common\validate\sys\User();
    }

    /**
     * 根据管理组Id查询关联用户
     * @param int $role_id 管理组Id
     * @return array
     * @throws
     */
    public function indexByRoleId ($role_id) {
        $filter = json_decode(input('filter'), true);
        $alias = [];
        foreach ($filter as $key => $value) {
            $alias['a.'.$key] = $value;
        }
        $this->makeWhere($alias);
        $this->makeOrder();
        $this->getPerPage();
        $visible = $this->model->getVisible();
        $visible[] = 'role_id';
        $list = Db::table($this->model->getTable())->visible($visible)->alias('a')->join('sys_user_relation_role b', 'a.id = b.user_id and b.role_id = '.$role_id, 'LEFT')->where($this->where)->order($this->order)->paginate($this->list_row);
        return success('',$list);
    }

    /**
     * 获取权限信息
     * @return array
     */
    public function getPermissionInfo () {
        $user_id = request()->user_id;
        $admin_auth = new AdminAuth($user_id);
        $permission = $admin_auth->getPermissionInfo();
        return success('', $permission);
    }

}
