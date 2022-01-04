<?php

namespace app\chat\socket\listener\base;

use app\chat\socket\Base;

class Connect extends Base {

    public function handle($data) {
        var_dump('----------Connect event------');
        var_dump($data);
    }

}
