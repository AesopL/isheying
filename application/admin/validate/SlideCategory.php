<?php
namespace app\admin\validate;

use think\Validate;

class SlideCategory extends Validate 
{
    protected $rule = [
        'name'=> 'require',
    ];

    protected $message = [
        'name.require'=>'名称不能为空',
    ];
}
