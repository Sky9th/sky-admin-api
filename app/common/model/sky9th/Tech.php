<?php
namespace app\common\model\sky9th;

use app\common\model\Client;

class Tech extends Client {

    protected $table = 'client_tech';
    public $search = [
        'title' => 'input'
    ];
    public $thead = ['title','version','link'];
    public $form = [
        'title' => 'input',
        'version' => 'input',
        'link' => 'url',
    ];

}