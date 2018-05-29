<?php
namespace app\admin\validate;

use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        'pid'   => 'require',
        'title' => 'require|min:2|unique:auth_rule',
        'name'  => 'require|min:3|unique:auth_rule',
        'sort'  => 'number',
    ];
    protected $message = [
        'pid.require'   => '父级栏目不能为空',
        'name.require'  => '控制器方法不能为空',
        'name.min'      => '控制器方法不能少于3个字符',
        'name.unique'   => '控制器方法已经存在',
        'title.require' => '菜单名称不能为空',
        'title.min'     => '菜单名称不能小于2个字符',
        'title.unique'  => '菜单名称已经存在',
        'sort.number'   => '排序只能是数字',
    ];
    protected $scene = [
        'edit' => ['name' => 'require|min:3', 'title' => 'require|min:2'],
    ];
}
