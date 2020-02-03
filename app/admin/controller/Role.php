<?php

namespace app\admin\controller;

use think\Exception;

class Role extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Role();
        $this->validate = new \app\common\validate\sys\Role();
    }

    /**
     * 为管理组赋予权限
     * @return array
     * @throws
     */
    public function savePermission () {
        $role_id = input('post.role_id');
        $permission = input('post.permissions/a');
        if($role_id == 1){
            return error('超级管理组不允许修改以及删除');
        }
        try {
            $this->model->startTrans();
            $role = $this->model->find($role_id);
            if ($role && $role->menus()->detach() !== false && $role->menus()->attach($permission) !== false ){
                $this->model->commit();
                return success('');
            }
            $this->model->rollback();
            return error();
        }catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 移除或添加用户到管理组
     * @return array
     * @throws
     */
    public function modifyUser () {
        $role_id = input('post.role_id');
        $user_id = input('post.user_id');
        $action =  input('post.action');
        if ($role_id == 1 || $user_id == 1){
            return error('超级管理组或超级管理员不允许修改以及删除');
        }
        $role = $this->model->find($role_id);
        if ($action == 1) {
            $res = $role->menus()->attach($user_id);
        } else {
            $res = $role->menus()->detach($user_id);
        }
        return $res ? success() : error();
    }
}
