<?php
namespace app\common\model\sky9th;

use app\common\model\Client;

class Project extends Client {

    protected $table = 'client_project';
    public $search = [
        'title' => 'input'
    ];
    public $thead = ['title','description'];
    public $form = [
        'title' => 'input',
        'link' => 'input',
        'cover' => ['type'=>'image', 'width'=>'200', 'height'=>'200'],
        'pictures' => 'images',
        'description' => 'textarea',
        'content' => 'editor'
    ];

    public function setPicturesAttr($value){
        return implode(',', $value);
    }

}