<?php
namespace app\common\model\sky9th;

use app\common\model\Client;

class ChatUser extends Client {

    protected $table = 'client_chat_user';
    public $search = [];
    public $thead = [];
    public $form = [];

    public function user () {
        return $this->belongsTo('app\common\model\common\User');
    }

}