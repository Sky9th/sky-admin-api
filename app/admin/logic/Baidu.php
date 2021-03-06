<?php
namespace app\admin\logic;

use think\Exception;

class Baidu {

    /**
     * 百度开放平台AccessToken获取
     * @return mixed
     * @throws Exception
     */
    public static function accessToken () {
        $token = cache('BAIDU_AI_ACCESS_TOKEN');
        if(!$token) {
            $url = 'https://aip.baidubce.com/oauth/2.0/token';
            $post_data['grant_type'] = 'client_credentials';
            $post_data['client_id'] = config('baidu.client_id');
            $post_data['client_secret'] = config('baidu.client_secret');
            $o = "";
            foreach ($post_data as $k => $v) {
                $o .= "$k=" . urlencode($v) . "&";
            }
            $post_data = substr($o, 0, -1);
            $res = request_post($url, $post_data);

            /**
             * {
             * "refresh_token": "25.b55fe1d287227ca97aab219bb249b8ab.315360000.1798284651.282335-8574074",
             * "expires_in": 2592000,
             * "scope": "public wise_adapt",
             * "session_key": "9mzdDZXu3dENdFZQurfg0Vz8slgSgvvOAUebNFzyzcpQ5EnbxbF+hfG9DQkpUVQdh4p6HbQcAiz5RmuBAja1JJGgIdJI",
             * "access_token": "24.6c5e1ff107f0e8bcef8c46d3424a0e78.2592000.1485516651.282335-8574074",
             * "session_secret": "dfac94a3489fe9fca7c3221cbf7525ff"
             * }
             */

            $res = json_decode($res, true);
            if (isset($res['error'])) {
                throw new Exception($res['error_msg']);
            }

            cache('BAIDU_AI_ACCESS_TOKEN',  $res,3600 * 24);
            $token = cache('BAIDU_AI_ACCESS_TOKEN');
        }
        return $token['access_token'];
    }

}