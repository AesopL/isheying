<?php
namespace app\admin\model;

use think\Model;

class AdminUser extends Model
{
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function groups()
    {
        return $this->belongsToMany('AuthGroup', 'auth_group_access', 'group_id', 'uid');
    }

}
