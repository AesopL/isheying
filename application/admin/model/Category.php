<?php
namespace app\admin\model;

use think\Model;

class Category extends Model
{

    //返回原有数据  不自动进行时间转换
    public function getCreateTimeAttr($time)
    {
        return $time;
    }
    
}
