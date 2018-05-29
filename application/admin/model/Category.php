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

    /**
     * 层级列表缩进
     *
     * @return void
     */
    public function getLevelList()
    {
        $list = $this->order(['sort'=>'DESC'])->select();
        return array2level($list);
    }
    
}
