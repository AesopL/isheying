<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'name' => 'require',
        'sort' => 'require',
        'type' => 'require',
        'pid' => 'require',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'sort.require'=>'排序不能为空',
        'type.require'=>'类型不能为空',
        'pid.require'=>'父级不能为空',
    ];
}
