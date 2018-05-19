<?php
namespace app\admin\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule        =  [
        'username'         => 'require|length: 3,25',
        'password'         => 'require|length: 6,16',
    ];

    protected $message     =  [
        'username.require' => '账号不能为空',
        'username.length'  => '账号不符合',
        'password.require' => '密码不能为空',
        'password.length'  => '密码长度不符合',
    ];
}
