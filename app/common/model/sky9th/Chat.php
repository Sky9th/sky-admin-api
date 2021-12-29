<?php
namespace app\common\model\sky9th;

use app\common\model\Client;

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

    public function setPicturesAttr($value){
        return implode(',', $value);
    }

}