<?php
// 应用公共文件


/**
 * 返回正确状态的数组
 * @param string $msg
 * @param mixed $data
 * @param string $url
 * @return array
 */
function success($msg = '', $data = '', $url = '')
{
    $result = [
        'code' => 0,
        'msg' => $msg,
        'data' => $data,
        'url' => $url
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
function error($msg = false, $data = [], $url = '', $code = -1)
{
    if (!$msg) {
        $msg = '系统繁忙，请稍后再试';
    }
    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
        'url' => $url
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
