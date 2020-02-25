<?php
// 应用公共文件


/**
 * 返回正确状态的数组
 * @param string $msg
 * @param mixed $data
 * @param string $url
 * @return array
 */
function success($msg = '', $data = '')
{
    $result = [
        'code' => 0,
        'msg' => $msg,
        'data' => $data
    ];
    return $result;
}

/**
 * 返回错误状态的数组
 * @param bool $msg
 * @param mixed $data
 * @param string $url
 * @param int $code
 * @return array
 */
function error($msg = false, $data = [], $code = -1)
{
    if (!$msg) {
        $msg = '系统繁忙，请稍后再试';
    }
    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ];
    return $result;
}

/**
 * 获取图片路径
 * @param $id string|array
 * @param $url bool
 * @return array|string
 * @throws
 */
function get_image($id, $url = false)
{
    $image_ids = [];
    if (is_numeric($id)) {
        $image_ids[] = $id;
    } else if (is_string($id)) {
        $image_ids = explode(',', $id);
    }
    $images = \app\common\model\Files::where('id', 'in', $image_ids)->select();
    if (count($images) == 0) {
        return false;
    }
    $src = [];
    foreach ($images as $image) {
        $src[] = 'uploads/images/' . str_replace('\\', '/', $image['src']);
    }
    if (count($src) == 1) {
        return $src[0];
    }
    return $src;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pk 主键
 * @param string $child 子键
 * @param string $pid parent标记字段
 * @param string $root 父级ID
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'children', $root = '0') {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}


/**
 * 过滤特殊字符、语句
 * @param $strParam
 * @return string|string[]|null
 */
function filter_special_char($strParam){
    $regex = "/|\$|\%|\^|\*|\?|\;|\'|\,|\(|\)|AND|SELECT|UNION|JOIN|WHERE|from|for|join|like|case|where|limit|offset|hex|group|update|insert|delete|by|char|drop|if|decode|ascii|instr|floor|exp|exec|concat|rand|extractvalue|database|table|exists|len|substr|user|create/i";  //and、or、select、union
    return preg_replace($regex,"",$strParam);
}

/**
 * 过滤xss
 * @param $string
 * @return string|string[]|null
 */
function filter_xss($string) {
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);
    $param1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $param2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $param = array_merge($param1, $param2);
    for ($i = 0; $i < sizeof($param); $i++) {
        $pattern = '/';
        for ($j = 0; $j < strlen($param[$i]); $j++) {
            if ($j > 0) {
                $pattern .= '(';
                $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                $pattern .= '|(&#0([9][10][13]);?)?';
                $pattern .= ')?';
            }
            $pattern .= $param[$i][$j];
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, ' ', $string);
        $string = preg_replace('/\"/', '"', $string);
    }
    return $string;
}

/**
 * 验证码确认
 * @param $name
 * @param $value
 * @return bool
 */
function captcha_check($name, $value) {
    return strtolower($value) == strtolower(cache('skyadmin_captcha_' . $name));
}

/**
 * 获取文件信息
 * @param $id
 * @return array|\think\Model|null
 */
function get_file($id) {
    $file = new \app\common\logic\File();
    return $file->getFile($id);
}

/**
 * CurlPOST
 * @param string $url
 * @param string $param
 * @return bool|string
 */
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    $curl = curl_init();//初始化curl
    curl_setopt($curl, CURLOPT_URL, $postUrl);//抓取指定网页
    curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($curl);//运行curl
    curl_close($curl);

    return $data;
}
