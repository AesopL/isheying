<?php
namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule     =  [
        'category'           => 'require',
        'title'         => 'require|min:3|unique:article',
    ];

    protected $message  =[ 
        'category.require'   => '请选择分类',
        'title.require' => '标题不能为空',
        'title.min'     => '标题不能少于三个字符',
        'title.unique'     => '文章标题已存在',
    ];
}
