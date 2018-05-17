<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\AdminUser as AdminUserModel;

class AdminUser extends AdminBase
{
    protected $admin_user_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->admin_user_model = new AdminUserModel();
    }

    public function index()
    {
        $admin_users = $this->admin_user_model->paginate(1);
        $this->assign('admin_users', $admin_users);
        return $this->fetch();
    }
}
