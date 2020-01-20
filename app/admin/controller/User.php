<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:11
 */
namespace app\admin\controller;

use app\admin\logic\AdminAuth;

class User {

    public function getPermissionInfo() {
        $user_id = request()->user_id;
        $admin_auth = new AdminAuth($user_id);
        $permission = $admin_auth->getPermissionInfo();
        return success('', $permission);
    }

}
