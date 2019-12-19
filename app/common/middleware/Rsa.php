<?php

namespace app\common\middleware;

class Rsa
{

    const RSA_PUBLIC = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCAUlVSmPZnhNFJgBzWIzfTt4bJ
y1EZC7JLumn/1raTNTHwbC3vUzT6JRUbXJ8rTtfFI3ul/848HJPQlCbp37EcawrE
lbr0G3IibEf7R21s8Yz65B6Z1ERrd/ZZzQIvVoo95YJMuk8oKJrVylcYin7RiXRM
UOxcgVUarN4Pn1DByQIDAQAB
-----END PUBLIC KEY-----';

    const RSA_PRIVATE = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCAUlVSmPZnhNFJgBzWIzfTt4bJy1EZC7JLumn/1raTNTHwbC3v
UzT6JRUbXJ8rTtfFI3ul/848HJPQlCbp37EcawrElbr0G3IibEf7R21s8Yz65B6Z
1ERrd/ZZzQIvVoo95YJMuk8oKJrVylcYin7RiXRMUOxcgVUarN4Pn1DByQIDAQAB
AoGAB0gkzV4exXsVAU3C1SgEeAA3JuZhwMEYKhH3B+ygSbRAFDufU7BuFi/ahcX4
xlVgCzDbSG0+v8yDWte9aZInE7e4xu1ktNncP4gnoRohmoOIRqpf7RSDaqb37S6p
iCr0pdt7xN6ihIml/rspbMbZ6GKV6B20ZtgFFROSXYoucLECQQDgYmkLC8Mnm0oS
WdQG8zxNHZky09FLE+Sgm5leFIuTaE5dEgTxQjdybTbHyJr94FcnkEoc9D3xLNng
MBwSQyeVAkEAkmbrQktc1KZbl98PhSBz4q1R9AWkF3BTdpm52ngmGn9tbKeZmmwP
McU9dYBiIjiEH4NQojVYbMfgYm+CfDCUZQJBAIGieXunPQWZ4w59FVE6n+ERs5u4
4pcUlCbyHoZLKmRsg7GjonVcQzp5vIdp75mzTccOxrK5rTu0JOAwC1fFso0CQESm
1nND+8gMKq9Q/mojCbSmKeQQMR58oebft3NnHBcY4istfK4ZNhbUszlCdsItVmeg
oFMwSMLaB7KhL8Mk1LECQD+WqIMXfjGyocJCuQp2fYkSjk0fCA/TANbrFq2Zn3K5
W7yJRrvQ7xSMZNFKJkebx+9Vg0TOef8nWIjU3E1tGSY=
-----END RSA PRIVATE KEY-----';

    public function handle($request, \Closure $next)
    {
        $server = $request->server();

        if (strtoupper($server['REQUEST_METHOD']) == 'OPTIONS') {
            return $next($request);
        }

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
            $private_key = openssl_pkey_get_private(self::RSA_PRIVATE);
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
        $key = openssl_pkey_get_public(self::RSA_PUBLIC);
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
