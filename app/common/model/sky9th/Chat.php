<?php
namespace app\common\model\sky9th;

use app\common\model\Client;
use app\common\model\common\File;

class Chat extends Client {

    protected $table = 'client_chat';
    public $search = [
        'title' => 'input'
    ];
    public $thead = ['title','description'];
    public $form = [
        'title' => 'input',
        'pictures' => 'images',
        'content' => 'editor'
    ];

    public function user() {
        return $this->belongsTo('app\common\model\common\User');
    }

    public function reply() {
        return $this->hasMany('app\common\model\sky9th\Chat', 'pid')->with('user');
    }

    public function getPicturesAttr($value){
        $pictures = new File();
        return $pictures->where('id','in', $value)->select();
    }

    public function setPicturesAttr($value){
        return implode(',', $value);
    }

}