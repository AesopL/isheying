<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;

class Login extends \think\Controller
{
    public function index()
    {
        /*******************  判断是否已经登录  *******************/
        if (Session::has('admin_name')) {
            $this->redirect('admin/index/index');
        }
        //echo session('?admin_name');die;
        return $this->fetch();
    }

    /**
     * 登录
     *
     * @return 返回json提示信息
     */
    public function Login()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '非法请求');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'Login');
        if ($validate_res !== true) {
            return return_msg(201, $validate_res);
        }

        /*******************  验证用户  *******************/
        $user = db('admin_user')->where('username', $data['username'])->field('id,username,password,status')->find();
        if (empty($user)) {
            return return_msg(201, '账号不存在');
        }

        /*******************  验证密码  *******************/
        if (md5($data['password'] . config('salt')) !== $user['password']) {
            return return_msg(201, "密码不正确");
        }

        /*******************  验证状态  *******************/
        if ($user['status'] !== 1) {
            return return_msg(201, '账号被禁用');
        }

        /*******************  写入session  *******************/
        session('uid', $user['id']);
        session('admin_name', $user['username']);
        return return_msg(200, '登陆成功', ['url' => 'admin/index/index']);
    }

    /**
     * 退出登陆
     *
     * @return void
     */
    public function logout()
    {
        session('uid', null);
        session('admin_name', null);
        $this->redirect('admin/login/index');
    }
}
