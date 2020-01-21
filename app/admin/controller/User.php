<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:11
 */
namespace app\admin\controller;

use app\admin\logic\AdminAuth;

class User extends Resource {

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Role();
        $this->validate = new \app\common\validate\sys\Role();
    }

    public function getPermissionInfo() {
        $user_id = request()->user_id;
        $admin_auth = new AdminAuth($user_id);
        $permission = $admin_auth->getPermissionInfo();
        return success('', $permission);
    }

}
