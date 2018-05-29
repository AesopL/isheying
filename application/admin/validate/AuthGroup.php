<?php
namespace app\admin\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule     =  [
        'title'         => 'require|length:3,10|unique:auth_group',
    ];
    protected $message  =  [
        'title.require' => '名称不能为空',
        'title.length'  => '名称为3-10个字符',
        'title.unique'  => '权限组已存在',
    ];
}
