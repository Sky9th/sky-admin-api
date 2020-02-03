<?php

namespace app\admin\controller;

use think\Exception;
use think\facade\Db;

class Role extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Role();
        $this->validate = new \app\common\validate\sys\Role();
    }

    /**
     * 根据用户Id查询关联管理组
     * @param int $user_id 用户Id
     * @return array
     * @throws
     */
    public function indexByUserId ($user_id) {
        $filter = json_decode(input('filter'), true);
        $alias = [];
        foreach ($filter as $key => $value) {
            $alias['a.'.$key] = $value;
        }
        $this->makeWhere($alias);
        $this->makeOrder();
        $this->getPerPage();
        $list = Db::table($this->model->getTable())->visible($this->model->getVisible())->alias('a')->join('sys_user_relation_role b', 'a.id = b.role_id and b.user_id = '.$user_id, 'LEFT')->where($this->where)->order($this->order)->paginate($this->list_row);
        return success('',$list);
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
            $res = $role->users()->attach($user_id);
        } else {
            $res = $role->users()->detach($user_id);
        }
        return $res ? success() : error();
    }
}
