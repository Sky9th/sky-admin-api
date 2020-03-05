<?php
/**
 * Created by PhpStorm.
 * User: Sky9th
 * Date: 2019/1/22
 * Time: 11:28
 */

namespace app\common\model\common;

use app\common\model\Common;

class Config extends Common
{

    protected $table = 'common_config';
    public $search = [
        'title' => 'input',
        'code' => 'input'
    ];
    public $thead = ['title','code', 'param'];
    public $form = [
        'title' => 'input',
        'code' => 'input',
        'param' => 'textarea'
    ];

    public function getParamAttr ($value) {
        return json_decode($value, true);
    }

}
