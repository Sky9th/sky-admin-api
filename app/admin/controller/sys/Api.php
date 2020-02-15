<?php

namespace app\admin\controller\sys;

use app\admin\controller\Resource;
use app\common\model\sys\Role;
use think\facade\Db;

class Api extends Resource
{

    public function __construct()
    {
        $this->model = new \app\common\model\sys\Api();
        $this->validate = new \app\common\validate\sys\Api();
    }

    public function index()
    {
        $this->getPerPage();
        $this->makeOrder();
        $this->makeWhere();
        $list = $this->model->where($this->where)->where('pid', 0)->append(['children'])->order($this->order)->paginate($this->list_row);
        return success('', $list);
    }

    /**
     * 根据用户Id查询关联接口
     * @param int $menu_id 用户Id
     * @return array
     * @throws
     */
    public function indexByMenuId ($menu_id) {
        $filter = json_decode(input('filter', '{}'), true);
        $relation = input('relation', 'LEFT', '/LEFT|INNER|RIGHT/');
        $alias = [];
        foreach ($filter as $key => $value) {
            $alias['a.'.$key] = $value;
        }
        $this->makeWhere($alias);
        $this->makeOrder();
        $this->getPerPage();
        $visible = $this->model->getVisible();
        $visible[] = 'menu_id';
        $list = Db::table($this->model->getTable())->where('pid', 0)->visible($visible)->alias('a')->join('sys_menu_relation_api b', 'a.id = b.api_id and b.menu_id = '.$menu_id, $relation)->where($this->where)->order($this->order)->paginate($this->list_row);
        return success('', $list);
    }

    /**
     *
     * @return array
     * @throws
     */
    public function save()
    {
        $post = input('post.');
        $type = input('post.type');
        unset($post['type']);
        unset($post['update_time']);
        unset($post['create_time']);
        $check = $this->validate->scene('save')->check($post);
        if (!$check) {
            return error($this->validate->getError());
        }
        try {
            $this->model->startTrans();
            if ($type == '1'){
                $this->model->save($post);
                $pid = $this->model->id;
                $resource = [
                    ['列表','index','','GET'],
                    ['新增','save','','POST'],
                    ['查询','read','/:id','GET'],
                    ['更新','update','/:id','PUT'],
                    ['删除','delete','/[:id]','DELETE'],
                ];
                $data = [];
                foreach ($resource as $item) {
                    $data[] = [
                        'pid' => $pid,
                        'title' => $post['title'].$item[0],
                        'path' => $post['path'].$item[2],
                        'permission' => $post['permission'].'_'.$item[1],
                        'method' => $item[3],
                    ];
                }
                $this->model = new \app\common\model\sys\Api();
                $res = $this->model->saveAll($data);
            } else {
                $res = $this->model->save($post);
            }
            if ($res) {
                $id = $this->model->id;
                $this->model->commit();
                return success(lang($this->msg['save']), ['id' => $id]);
            } else {
                $this->model->rollback();
                return error(lang($this->msg['non_save']));
            }
        } catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * 获取管理组的相关菜单权限
     * @param $role_id
     * @return array
     * @throws
     */
    public function indexByRoleId ($role_id) {
        $role = Role::find($role_id);
        return success('',$role->apis);
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
        $api = $this->model->where(true)->append(['children'])->find($api_id)->toArray();
        $api_ids = [$api_id];
        if (count($api['children']) > 0) {
            $api_ids = array_merge($api_ids,array_column($api['children']->toArray(),'id'));
        }
        $menu = \app\common\model\sys\Menu::find($menu_id);
        if ($action == 1) {
            $res = $menu->apis()->saveAll($api_ids);
        } else {
            $res = $menu->apis()->detach($api_ids);
        }
        return $res ? success() : error();
    }

}
