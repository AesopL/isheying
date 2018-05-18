<?php
namespace app\admin\validate;

use think\Validate;

class AdminUser extends Validate
{
    protected $rule = [
        'username'         => 'require|length:3,25|chsDash|unique:admin_user',
        'password'         => 'require|length:6,16',
        'confirm_password' => 'require|confirm:password',
        'status'           => 'number',
        'group_id'         => 'number',
    ];

    protected $message = [
        'username.require'         => '账号不能为空',
        'username.length'          => '账号长度为3-25个字符',
        'username.chsDash'         => '账号不符合要求',
        'username.unique'          => '账号已存在',
        'password.require'         => '密码不能为空',
        'password.length'          => '密码长度为6-16个字符',
        'confirm_password.require' => '确认密码不能为空',
        'confirm_password.confirm' => '两次密码不一致',
        'status.number'            => '用户id错误',
        'group_id'                 => '用户组错误',
    ];
}
