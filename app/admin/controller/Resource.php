<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/19
 * Time: 9:34
 */

namespace app\admin\controller;

use app\common\model\Common;
use think\Validate;

/**
 * Class Resource
 * @package app\api\controller
 * @property Common $model
 * @property Validate $validate
 */
class Resource
{

    protected $model;
    protected $validate;

    protected $where = false;
    protected $order = false;
    protected $list_row;

    protected $msg = [
        'save' => 'created success',
        'update' => 'update success',
        'delete' => 'delete success',
        'status' => 'status success',
        'non_save' => 'created error',
        'non_update' => 'update error',
        'non_delete' => 'delete error',
        'non_status' => 'status error',
    ];

    /**
     * 查询列表
     * @return array
     * @throws
     */
    public function index()
    {
        $this->getPerPage();
        $this->makeOrder();
        $this->makeWhere();
        $list = $this->model->where($this->where)->order($this->order)->paginate($this->list_row);
        return success('', $list);
    }

    /**
     * 查看数据
     * @param $id
     * @return array
     * @throws
     */
    public function read($id)
    {
        $info = $this->model->where(true)->find($id);
        return success('', $info);
    }

    /**
     * 新增数据
     * @return array
     */
    public function save()
    {
        $post = input('post.');
        unset($post['update_time']);
        unset($post['create_time']);
        $check = $this->validate->scene('save')->check($post);
        if (!$check) {
            return error($this->validate->getError());
        }
        $res = $this->model->save($post);
        if ($res) {
            $id = $this->model->id;
            return success(lang($this->msg['save']), ['id' => $id]);
        } else {
            return error(lang($this->msg['non_save']));
        }
    }

    /**
     * 更新数据
     * @param $id
     * @return array
     * @throws
     */
    public function update($id)
    {
        $post = input('put.');
        unset($post['update_time']);
        unset($post['create_time']);
        $check = $this->validate->scene('update')->check($post);
        if (!$check) {
            return error($this->validate->getError());
        }
        $data = $this->model->where('id', $id)->find();
        $res = $data->save($post);
        if ($res === false) {
            return error(lang($this->msg['non_update']));
        } else {
            return success(lang($this->msg['update']), ['id' => $id]);
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return array
     * @throws
     */
    public function delete($id = false)
    {
        if($id){
            $ids = [$id];
        }else{
            $ids = input('delete.ids/a');
        }
        $check = $this->validate->scene('delete')->check(['id'=>$ids]);
        if (!$check) {
            return error($this->validate->getError());
        }
        $list = $this->model->where('id', 'in', $ids)->select();
        $res = $list->delete();
        if ($res) {
            return success(lang($this->msg['delete']), ['id' => $ids]);
        } else {
            return error(lang($this->msg['non_delete']));
        }
    }

    /**
     * 审核数据
     * @param $status
     * @return array
     */
    public function status($status)
    {
        $id = input('delete.ids/a');
        $res = $this->model->where('id', 'in', $id)->save(['status' => $status]);
        if ($res === false) {
            return error(lang($this->msg['non_status']));
        } else {
            return success(lang($this->msg['status']), ['id' => $id]);
        }
    }

    /**
     * 获取每页查询数量
     * @param int $per_page
     */
    protected function getPerPage ($per_page = 0) {
        if(!$per_page){
            $per_page = input('get.per_page', config('static.list_row'));
        }
        if($per_page && in_array($per_page, config('static.list_row_range'))){
            $this->list_row = $per_page;
        }else{
            $this->list_row =  config('static.list_row');
        }
    }

    /**
     * 组成排序
     * @param $order
     * @param $descending
     */
    protected function makeOrder ($order = false, $descending = false) {
        if(!$order){
            $order = input('order');
            $descending = input('descending');
        }
        //组成排序
        if(!$this->order){
            if ($order) {
                $sort = $order . ' ' .($descending == 'true' ? 'desc' : 'asc');
            } else {
                $sort = $this->model->order;
            }
            $this->order = $sort;
        }
    }

    /**
     * 组成查询条件
     * @param $filter
     */
    protected function makeWhere ($filter = false) {
        if(!$filter){
            $filter = json_decode(input('filter'), true);
        }
        //对查询字符串进行安全过滤，过滤特殊符号，特定词语，以及XSS并且组成查询条件
        if(!$this->where && is_array($filter)){
            $where = [];
            foreach ($filter as $key => $value) {
                $value = filter_xss(filter_special_char($value));
                if(!$value) { continue; }
                $where[] = [$key, 'like', '%'.$value.'%'];
            }
            if(count($where) > 0){
                $this->where = $where;
            }
        }
    }

}
