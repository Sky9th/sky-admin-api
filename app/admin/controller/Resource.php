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
        $list = $this->model->where(true)->order($this->model->order)->paginate(15);
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
    public function delete($id)
    {
        $res = $this->model->where('id', $id)->delete();
        if ($res) {
            return success(lang($this->msg['delete']), ['id' => $id]);
        } else {
            return error(lang($this->msg['non_delete']));
        }
    }


    /**
     * 批量删除数据
     * @param $id
     * @return array
     * @throws
     */
    public function destroy()
    {
        $ids = input('delete.ids/a');
        $res = $this->model->where('id', 'in', $ids)->delete();
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

}
