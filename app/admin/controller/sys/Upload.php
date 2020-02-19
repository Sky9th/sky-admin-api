<?php
namespace app\admin\controller\sys;

use app\common\logic\File;

class Upload {

    /**
     * 上传公开文件
     * @return array
     */
    public function general () {
        $file = new File();
        $file_id = $file->uploadImage();
        return is_numeric($file_id) ? success('', $file->getFile($file_id)) : error($file_id);
    }

}