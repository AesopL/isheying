<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function genTree9($items)
{
    $tree = array(); //格式化好的树
    foreach ($items as $item) {
        if (isset($items[$item['pid']])) {
            $items[$item['pid']]['son'][] = &$items[$item['id']];
        } else {
            $tree[] = &$items[$item['id']];
        }
    }

    return $tree;
}

function genTree5($items)
{
    foreach ($items as $item) {
        $items[$item['pid']]['son'][$item['id']] = &$items[$item['id']];
    }

    return isset($items[0]['son']) ? $items[0]['son'] : array();
}

/**
 * 数组层级缩进转换
 * @param array $array 源数组
 * @param int   $pid
 * @param int   $level
 * @return array
 */
function array2level($array, $pid = 0, $level = 1)
{
    static $list = [];
    foreach ($array as $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[] = $v;
            array2level($array, $v['id'], $level + 1);
        }
    }

    return $list;
}

/**
 * 构建层级（树状）数组
 * @param array  $array          要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name       父级ID的字段名
 * @param string $child_key_name 子元素键名
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
{
    $counter = array_children_count($array, $pid_name);
    if (!isset($counter[0]) || $counter[0] == 0) {
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0] > 0) {
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
            array_push($array, $temp);
        } else {
            if ($temp[$pid_name] == 0) {
                $tree[] = $temp;
            } else {
                $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter = array_children_count($array, $pid_name);
    }

    return $tree;
}

/**
 * 子元素计数器
 * @param array $array
 * @param int   $pid
 * @return array
 */
function array_children_count($array, $pid)
{
    $counter = [];
    foreach ($array as $item) {
        $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
        $count++;
        $counter[$item[$pid]] = $count;
    }

    return $counter;
}

/**
 * 把元素插入到对应的父元素$child_key_name字段
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name)
{
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if (!isset($item[$child_key_name])) {
                $item[$child_key_name] = [];
            }

            $item[$child_key_name][] = $child;
        }
    }

    return $parent;
}
/**
 * 返回json提示新 function
 *
 * @param [type] $code
 * @param string $msg
 * @param array $data
 * @return json信息
 */
function return_msg($code, $msg = '', $data = [])
{
    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    return $result;
}

/**
 * 上传图片
 *
 * @param [type] $file
 * @param string $type
 * @return void
 */
function upload_file($file, $type = '')
{
    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    if ($info) {
        $path = '/uploads/' . $info->getSaveName();
        if (!empty($type)) {
            image_edit($path, $type);
        }
        return str_replace('\\', '/', $path);
    } else {
        return false;
    }
}
/**
 * 生成缩略图
 *
 * @param [string] $path 图片路径
 * @param [string] $type 图片场景
 * @return void
 */
function image_edit($path, $type)
{
    //dump(ROOT_PATH . 'public' . $path);die;
    $image = \think\Image::open(ROOT_PATH . 'public' . $path);
    switch ($type) {
        case 'head_img':
            $image->thumb(200, 200, \think\Image::THUMB_CENTER)->save(ROOT_PATH . 'public' . $path);
            break;
        case 'article_thumb':
            $image->thumb(400, 400, \think\Image::THUMB_CENTER)->save(ROOT_PATH . 'public' . $path);
            break;
    }
}
