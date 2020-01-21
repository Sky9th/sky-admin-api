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
