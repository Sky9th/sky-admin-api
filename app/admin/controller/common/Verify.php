<?php
namespace app\admin\controller\common;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Verify {

    public function image () {
        $fingerprint = input('post.fingerprint');
        $base64 = \app\common\logic\Verify::image($fingerprint);
        return success('', $base64);
    }

}