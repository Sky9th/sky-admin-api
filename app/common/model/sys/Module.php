<?php
/**
 * Author: Sky9th
 * Date: 2017/4/2
 * Time: 17:11
 */

namespace app\common\model\sys;

use app\common\model\Sys;

class Module extends Sys
{

    protected $table = 'sys_module';
    protected $resultSetType = 'collection';

    public function ActionLog()
    {
        return $this->hasMany('ActionLog');
    }

    public function _before_delete($ids)
    {
        $count = $this->where(['pid' => ['in', $ids]])->count();
        if ($count) {
            return error('所选模块中存在子级模块，无法删除');
        }
        return true;
    }

}
