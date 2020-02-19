<?php
namespace app\common\logic;

use think\exception\ValidateException;
use think\facade\Filesystem;
use app\common\model\common\File as FileModel;
use think\file\UploadedFile;

class File
{
    protected $access;
    protected $pid;
    protected $user_id;

    public function __construct($access = 'public')
    {
        $this->user_id = request()->user_id;
        $this->access = $access;
        $this->pid = input('pid', 0);
    }

    /**
     * 获取文件路径
     * @param $id
     * @return array
     * @throws
     */
    public function getFile($id) {
        $file = FileModel::where('id', $id)->where('local', 0)->find();
        return [
            'id' => $id,
            'title' => $file['title'],
            'src' =>request()->domain(). '/storage/'.$file['src']
        ];
    }

    /**
     * 上传图片
     * @return string|int
     */
    public function uploadImage () {
        $file = request()->file('file');
        try {
            validate(['file'=>config('filesystem.validate.image')])->check(['file'=>$file]);
            $file_id = $this->unique($file);
            if ($file_id) return $file_id;
            $savename = Filesystem::disk($this->access)->putFile('images', $file);
            $file_id = $this->record($file, $savename, 1);
            return $file_id;
        } catch (ValidateException $e) {
            return $e->getMessage();
        }
    }

    /**
     * 上传文件去重
     * @description 同一个用户上传同一个文件时直接返回曾经上传过的文件
     * @param $file UploadedFile
     * @return bool|mixed
     * @throws
     */
    protected function unique ($file) {
        $hash = $file->hash('sha1');
        $size = $file->getSize();
        $res = FileModel::where('hash', $hash)->where('size', $size)->where('user_id', $this->user_id)->find();
        return $res ? $res['id'] : false;
    }

    /**
     * 上传文件记录入库
     * @param $info UploadedFile
     * @param $savename
     * @param $type
     * @return mixed
     */
    protected function record ($info, $savename, $type) {
        $fileModel = new FileModel();
        $file = $fileModel->save([
            'user_id' => $this->user_id,
            'type' => $type,
            'pid' => $this->pid,
            'title' => $info->getOriginalName(),
            'src' => $savename,
            'hash' => $info->hash('sha1'),
            'size' => $info->getSize(),
            'ext' => $info->extension(),
        ]);
        return $file ? $fileModel->id : false;
    }

}