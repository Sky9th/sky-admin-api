<?php

namespace app\admin\controller;

class Index
{

    /**
     * 获取文件路径
     * @param int $id
     * @return array
     * @throws
     */
    public function getFile($id = false)
    {
        if (!$id) $id = input('post.ids');
        return success('', get_file($id));
    }

}
