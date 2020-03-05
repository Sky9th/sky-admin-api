<?php
namespace app\common\validate;

use think\Validate;

class Common extends Validate {

    public function getRule () {
        return $this->rule;
    }

    public function pass () {
        return true;
    }

}