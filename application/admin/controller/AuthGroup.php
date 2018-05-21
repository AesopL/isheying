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

    public function index()
    {
        $auth_groups = $this->auth_group_model->paginate(10);
        $this->assign('auth_groups', $auth_groups);
        return $this->fetch();
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

    public function getAuth($id)
    {
        /*******************  管理员的权限  *******************/
        $auth_group_list = $this->auth_group_model->find($id);
        /*******************  转为数组  *******************/
        $auths = explode(',', $auth_group_list);
        $auth_rule_list = $this->auth_rule_model->field('id,title,pid')->select();
        /*******************  设置选中  *******************/
        foreach ($auth_rule_list as $key => $value) {
            in_array($value['id'], $auths) && $auth_rule_list[$key]['checked'] = true;
        }
        $items = array(
            1 => array('id' => 1, 'pid' => 0, 'name' => '江西省'),
            2 => array('id' => 2, 'pid' => 0, 'name' => '黑龙江省'),
            3 => array('id' => 3, 'pid' => 1, 'name' => '南昌市'),
            4 => array('id' => 4, 'pid' => 2, 'name' => '哈尔滨市'),
            5 => array('id' => 5, 'pid' => 2, 'name' => '鸡西市'),
            6 => array('id' => 6, 'pid' => 4, 'name' => '香坊区'),
            7 => array('id' => 7, 'pid' => 4, 'name' => '南岗区'),
            8 => array('id' => 8, 'pid' => 6, 'name' => '和兴路'),
            9 => array('id' => 9, 'pid' => 7, 'name' => '西大直街'),
            10 => array('id' => 10, 'pid' => 8, 'name' => '东北林业大学'),
            11 => array('id' => 11, 'pid' => 9, 'name' => '哈尔滨工业大学'),
            12 => array('id' => 12, 'pid' => 8, 'name' => '哈尔滨师范大学'),
            13 => array('id' => 13, 'pid' => 1, 'name' => '赣州市'),
            14 => array('id' => 14, 'pid' => 13, 'name' => '赣县'),
            15 => array('id' => 15, 'pid' => 13, 'name' => '于都县'),
            16 => array('id' => 16, 'pid' => 14, 'name' => '茅店镇'),
            17 => array('id' => 17, 'pid' => 14, 'name' => '大田乡'),
            18 => array('id' => 18, 'pid' => 16, 'name' => '义源村'),
            19 => array('id' => 19, 'pid' => 16, 'name' => '上坝村'),
        );

        $auth_rule_list = genTree9($items);
        return return_msg(200,'成功',$auth_rule_list);

    }
}
