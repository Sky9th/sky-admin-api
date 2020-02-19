<?php
namespace app\admin\controller\common;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Verify {

    public function image ($name) {
        $phraseBuilder = new PhraseBuilder(4);
        $captcha = new CaptchaBuilder(null, $phraseBuilder);
        $build = $captcha->build(100);
        $data = $build->inline();
        $code = $captcha->getPhrase();
        cache('skyadmin_captcha_'.$name , $code, ['expire'=>900], 'captcha');
        return success('', $data);
    }

}