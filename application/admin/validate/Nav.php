<?php
namespace app\admin\validate;

use think\Validate;

class Nav extends Validate
{
    protected $rule    = [
        'name'         => 'require',
        'pid'          => 'require',
        'sort'         => 'require',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'pid.require'  => '父级不能为空',
        'sort.require' => '排序不能为空',
    ];
}
