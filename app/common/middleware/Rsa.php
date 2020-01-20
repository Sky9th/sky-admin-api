<?php

namespace app\common\middleware;

class Rsa
{

    public function handle($request, \Closure $next)
    {
        $header = $request->header();

        if (!(isset($header['timestamp']) && isset($header['signature']))) {
            return json(error());
        }
        $timestamp = $header['timestamp'];
        $signature = $header['signature'];

        $decode = json_decode($this->RAS_openssl($signature, 'decode'));
        $key = config('static.api_key');

        if ($key != $decode->key) {
            return json(error('signature error'));
        }

        if (abs($timestamp - time()) > 10 * 60) {
            return json(error('timestamp out'));
        }
        return $next($request);
    }


    /**
     * RSA数据加密解密
     * @param $data
     * @param $type string  encode加密  decode解密
     * @return string
     */
    public function RAS_openssl($data, $type = 'encode')
    {

        if (empty($data)) {
            return 'data参数不能为空';
        }

        //私钥解密
        if ($type == 'decode') {
            $private_key = openssl_pkey_get_private(config('rsa.private'));
            if (!$private_key) {
                return ('私钥不可用');
            }
            $return_de = openssl_private_decrypt(base64_decode($data), $decrypted, $private_key);
            if (!$return_de) {
                return ('解密失败,请检查RSA秘钥');
            }
            return $decrypted;
        }

        //公钥加密
        $key = openssl_pkey_get_public(config('rsa.public'));
        if (!$key) {
            return ('公钥不可用');
        }
        $return_en = openssl_public_encrypt($data, $crypted, $key);
        if (!$return_en) {
            return ('加密失败,请检查RSA秘钥');
        }
        return base64_encode($crypted);
    }

}
