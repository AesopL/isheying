<?php
namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule     =  [
        'title'         => 'require|min:3|token',
        'category'           => 'require',
    ];

    protected $message  =[ 
        'title.require' => '标题不能为空',
        'title.min'     => '标题不能少于三个字符',
        'category.require'   => '请选择分类',
    ];
}
