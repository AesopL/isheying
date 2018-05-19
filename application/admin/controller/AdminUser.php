<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\AdminUser as AdminUserModel;
use app\admin\model\AuthGroupAccess as AuthGroupAccessModel;
use app\admin\model\AuthGroup as AuthGroupModel;

class AdminUser extends AdminBase
{
    protected $admin_user_model;
    protected $auth_group_model;
    protected $auth_group_access_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->admin_user_model = new AdminUserModel();
        $this->auth_group_model = new AuthGroupModel();
        $this->auth_group_access_model = new AuthGroupAccessModel();
        /*******************  获取分组  *******************/
        $auth_groups = $this->auth_group_model->field('id,title')->select();
        $this->assign('auth_groups', $auth_groups);
    }

    /**
     * 管理员列表
     *
     * @return void
     */
    public function index()
    {
        $admin_users = $this->admin_user_model->paginate(20);
        $this->assign('admin_users', $admin_users);
        return $this->fetch();
    }
    /**
     * 管理员添加设置分组
     *
     * @return void
     */
    public function save()
    {
        /*******************  判断请求  *******************/
        if ($this->request->isAjax()) {
            /*******************  接收参数  *******************/
            $data = $this->request->param();
            /*******************  验证信息  *******************/
            $validate_res = $this->validate($data, 'AdminUser');
            if ($validate_res !== true) {
                return $this->return_msg(400, $validate_res);
            }
            /*******************  加密密码（md5+salt）  *******************/
            $data['password'] = md5($data['password'] . config('salt'));
            // dump($data['password']);die;
            /*******************  写入数据  *******************/
            $res = $this->admin_user_model->allowField(true)->save($data);
            if ($res !== false) {
                /*******************  获取新增id  *******************/
                $auth_group_data['uid'] = $this->admin_user_model->id;
                $auth_group_data['group_id'] = $data['group_id'];
                /*******************  设置用户所在组  *******************/
                $this->auth_group_access_model->save($auth_group_data);
                return $this->return_msg(200, '保存成功');
            } else {
                return $this->return_msg(400, '保存失败', $data['create_at']);
            }
        }
    }
    public function edit($id)
    {
        $data = $this->admin_user_model->get($id);
        $group_data = $this->auth_group_access_model->where('uid', $id)->find();
        $data['group_id'] = $group_data['group_id'];
        if (!empty($data)) {
            return $this->return_msg(200, '获取成功', $data);
        }
    }

    /**
     * 更新数据
     *
     * @return 返回json提示信息
     */
    public function update()
    {
        if ($this->request->isAjax()) {
            /*******************  接收数据  *******************/
            $data = $this->request->param();
            /*******************  验证数据  *******************/
            $validate = validate('AdminUser');

            $validate_res = $this->validate($data, 'AdminUser.edit');
            if ($validate_res !== true) {
                return $this->return_msg(400, $validate_res);
            }
            $user_data = ['id' => $data['id'], 'username' => $data['username'], 'status' => $data['status']];
            /*******************  是否修改密码  *******************/
            if (!empty($data['password'])) {
                $data['password'] = md5($data['password'] . config('salt'));
            }
            /*******************  写入数据库  *******************/
            //dump($data);
            $res = $this->admin_user_model->allowField(true)->save($user_data, $user_data['id']);
            if ($res !== false) {
                /*******************  数据拼接  *******************/
                $auth_group_access_data['uid'] = $data['id'];
                $auth_group_access_data['group_id'] = $data['auth_group'];
                /*******************  更新数据  *******************/
                $this->auth_group_access_model->save($auth_group_access_data, $auth_group_access_data['uid']);
                return $this->return_msg(200, '修改成功');
            } else {
                $this->return_msg(400, '修改失败');
            }

        }
    }

    /**
     * 管理员删除/批量删除
     *
     * @return void
     */
    public function delete()
    {
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  将id转为字符串  *******************/
        $id = is_array($data['id']) ? implode(',', $data['id']) : $data['id'];
        /*******************  是否是超级管理员  *******************/
        if ($id == 1 || in_array(1, explode(',', $id))) {
            return $this->return_msg(201, '超级管理员不能删除');
        }
        /*******************  删除数据管理员  *******************/
        $res = $this->admin_user_model->destroy($id);
        if ($res !== false) {
            /*******************  删除  *******************/
            return $this->return_msg(200, '删除成功');
        } else {
            return $this->return_msg(400, '删除失败');
        }
    }

}
