<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\AuthRule as AuthRuleModel;
use think\Db;

/**
 * 菜单管理
 */
class Menu extends AdminBase
{
    protected $auth_rule_model;
    protected function _initialize()
    {
        parent::_initialize();
        $admin_menu_list = Db('auth_rule')->order(['sort' => 'DESC', 'id' => 'ASC'])->select();
        $admin_menu_level_list = array2level($admin_menu_list);
        $this->auth_rule_model = new AuthRuleModel();
        //$page = $admin_menu_list->render();
        //$this->assign('page', $page);

        $this->assign('admin_menu_level_list', $admin_menu_level_list);
        //dump($admin_menu_level_list);die;
    }

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 菜单添加页面
     *
     * @param string $pid
     * @return mixed
     */
    public function add($pid = '')
    {
        return $this->fetch('add', ['pid' => $pid]);
    }

    /**
     * 数据添加
     *
     * @return 返回json信息
     */
    public function save()
    {
        if ($this->request->isAjax()) {
            $data = $this->request->param();
            //dump($data);die;
            /*******************  验证数据  *******************/
            $validate_res = $this->validate($data, 'Menu');
            if ($validate_res !== true) {
                return $this->return_msg(400, $validate_res);
            }

            $res = $this->auth_rule_model->allowField(true)->save($data);
            if (!$res) {
                return $this->return_msg(400, '添加失败');
            }
            return $this->return_msg(200, '添加成功', $data);
        }

    }

    /**
     * 获取单行数据
     *
     * @param [int] $id
     * @return json信息
     */
    public function edit($id)
    {
        $data = $this->auth_rule_model->where('id', $id)->find();
        if (!$data) {
            return $this->return_msg(400, '无效数据');
        }
        return $this->return_msg(200, '获取成功', $data);

    }

    /**
     * 数据更新
     *
     * @return 返回提示信息
     */
    public function update()
    {
        /*******************  验证请求类型  *******************/
        if ($this->request->isAjax()) {
            /*******************  获取数据  *******************/
            $data = $this->request->param();
            /*******************  验证数据  *******************/
            $validate_res = $this->validate($data, 'Menu.edit');
            if ($validate_res !== true) {
                return $this->return_msg(400, $validate_res, $data);
            }
            //dump($data);die;
            /*******************  修改数据库  *******************/
            $res = $this->auth_rule_model->allowField(true)->save($data, $data['id']);
            if ($res !== false) {
                return $this->return_msg(200, '修改成功', $data);
            }
            return $this->return_msg(400, '修改失败', $res);
        }
    }

    public function delete()
    {
        /*******************  验证请求类型  *******************/
        if ($this->request->isAjax()) {
            /*******************  接收参数  *******************/
            $id = $this->request->param('id');
            // dump($id);
            /*******************  判断是否有子栏目  *******************/
            $son_menu = $this->auth_rule_model->where('pid', $id)->find();
            if (!empty($son_menu)) {
                return $this->return_msg(201, '该栏目下有子目录，不能删除');
            }
            /*******************  删除数据  *******************/
            $res = $this->auth_rule_model->where('id', 'in', $id)->delete();
            if ($res !== false) {
                return $this->return_msg(200, '删除成功');
            } else {
                return $this->return_msg(400, '文章删除失败');
            }
        }
    }
}
