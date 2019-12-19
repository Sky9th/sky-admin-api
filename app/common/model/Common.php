<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/7
 * Time: 10:16
 */

namespace app\common\model;

use think\Model;

class Common extends Model
{

    static $allowField = true;
    static $visibleField = [];
    static $visibleCondition = [['status', '=', 1]];
    static $order = 'id desc';

    public function getStatusTextAttr($value, $data)
    {
        $status = config('static.status_name');
        return $status[$data['status']];
    }

    public function getStatusBadgeAttr($value, $data)
    {
        $status = config('static.status_badge');
        return $status[$data['status']];
    }

}
