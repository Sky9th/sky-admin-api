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
    public function getFile($id)
    {
        return success('',get_file_src($id));
    }

}
