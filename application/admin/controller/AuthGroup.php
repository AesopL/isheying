<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule as AuthRuleModel;

class AuthGroup extends AdminBase
{
    protected $auth_group_model;
    protected $auth_rule_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->auth_group_model = new AuthGroupModel();
        $this->auth_rule_model = new AuthRuleModel();
    }

    public function index($keyword = '', $status = '')
    {
        
        $map = [];
        if (!empty($keyword)) {
            $map['title'] = ['like', "%{$keyword}%"];
        }
        if ($status !== '') {
            $map['status'] = $status;
        }
        //dump($status);
        //dump($map);die;
        $auth_groups = $this->auth_group_model->where($map)->paginate(10);
        $this->assign('auth_groups', $auth_groups);
        return $this->fetch('index', ['keyword' => $keyword,'status'=>$status]);
    }

    public function edit()
    {
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        $id = $this->request->param('id');
        $data = $this->auth_group_model->field('id,title,status')->find($id);
        return return_msg(200, '获取成功', $data);
    }
    /**
     * 保存信息
     *
     * @return json信息
     */
    public function save()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收参数  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'AuthGroup');
        if ($validate_res !== true) {
            return return_msg('201', $validate_res);
        }
        /*******************  写入数据  *******************/
        $res = $this->auth_group_model->allowField(true)->save($data);
        if ($res !== false) {
            return return_msg(200, '添加成功');
        } else {
            return return_msg('添加失败');
        }

    }

    /**
     * 更新
     *
     * @return void
     */
    public function update()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  验证数据 *******************/
        $validate_res = $this->validate($data, 'AuthGroup');
        if ($validate_res !== true) {
            return return_msg(201, $validate_res);
        }
        /*******************  写入数据  *******************/
        $res = $this->auth_group_model->allowField(true)->save($data, $data['id']);
        if ($res !== false) {
            return return_msg(200, '修改成功');
        } else {
            return return_msg(400, '修改失败');
        }
    }

    /**
     * 删除
     *
     * @return json信息
     */
    public function delete()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg('请求错误');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        if (!isset($data['id']) || empty($data['id'])) {
            return return_msg(201, '非法访问');
        }
        /*******************  超级管理员不能删除  *******************/
        if ($data['id'] == 1) {
            return return_msg(400, '超级管理组不可删除');
        }
        /*******************  删除数据  *******************/
        $res = $this->auth_group_model->destroy($data['id']);
        if ($res !== false) {
            return return_msg(200, '删除成功');
        } else {
            return return_msg(400, '删除失败');
        }
    }

    /**
     * 获取已授权的权限
     *
     * @param [string] $id
     * @return void
     */
    public function getAuth($id)
    {
        /*******************  管理员的权限  *******************/
        $auth_group_list = $this->auth_group_model->find($id)->toArray();
        /*******************  转为数组  *******************/
        $auths = explode(',', $auth_group_list['rules']);
        $auth_rule_list = $this->auth_rule_model->field('id,title,pid')->select();
        /*******************  设置选中  *******************/
        foreach ($auth_rule_list as $key => $value) {
            in_array($value['id'], $auths) && $auth_rule_list[$key]['checked'] = true;
        }
        return return_msg(200, '成功', $auth_rule_list);

    }

    /**
     * 更新权限
     *
     * @return void
     */
    public function updateAuth()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        if ($data['id']) {
            $data['rules'] = isset($data['auth_rule_ids']) && is_array($data['auth_rule_ids']) ? implode(',', $data['auth_rule_ids']) : '';
        }
        //dump($data);die;
        /*******************  写入数据库  *******************/
        $res = $this->auth_group_model->allowField(true)->save($data, $data['id']);
        if ($res !== false) {
            return return_msg(200, '授权成功');
        } else {
            return return_msg(400, '授权失败');
        }
    }
}
