<?php
namespace app\admin\validate;

use think\Validate;

class Slide extends Validate
{
    protected $rule    = [
        'name'         => 'require',
        'cid'          => 'number',
        'sort'         => 'require|number',
    ];

    protected $message = [
        'name.require' => "名称不能为空",
        'cid.number'   => '只能为数字',
        'sort.require' => "排序不能为空",
        'sort.number'  => '排序只能是数字',
    ];
}
