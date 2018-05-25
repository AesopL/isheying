<?php
namespace app\admin\model;

use think\Model;

class Article extends Model
{
    protected $autoWriteTimeStamp = 'datatime';

    //返回原有数据  不自动进行时间转换
    public function getCreateTimeAttr($time)
    {
        return $time;
    }
    public function getUpdateTimeAttr($time)
    {
        return $time;
    }
    public function getPublishTimeAttr($time)
    {
        return $time;
    }

    //设置审核状态为中文
    public function getStatusAttr($value){
        $status = [0=>'待审核','1'=>'审核通过'];
        return $status[$value];
    }
     //设置是否置顶
    public function getIsTopAttr($value){
        $is_top = [0=>'否','1'=>'是'];
        return $is_top[$value];
    }
     //设置审核状态为中文
    public function getIsRecommendAttr($value){
        $is_recommend = [0=>'否','1'=>'是'];
        return $is_recommend[$value];
    }

    
}
