<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\Nav as NavModel;

class Nav extends AdminBase
{
    protected $nav_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->nav_model = new NavModel();
        $navs = $this->nav_model->order(['sort' => 'ASC'])->select();
        $navs_level = \array2level($navs);
        $this->assign('navs_level', $navs_level);
    }

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 返回单个数据
     *
     * @param string $id
     * @return json
     */
    public function edit($id)
    {
        $data = $this->nav_model->get($id);
        return return_msg(200,'获取成功',$data);
    }

    /**
     * 保存数据
     *
     * @return void
     */
    public function save()
    {
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'Nav');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }

        /*******************  写入数据  *******************/
        $res = $this->nav_model->allowField(true)->save($data);
        if ($res !== false) {
            return return_msg(200, '添加成功');
        } else {
            return return_msg(400, '添加失败');
        }
    }

    public function update()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->param()) {
            return return_msg(400,'请求错误');
        }
        /*******************  接收参数  *******************/
        $data = $this->request->param();
        //dump($data);
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data,'Nav');
        if ($validate_res !== true) {
            return return_msg(400,$validate_res);
        }
        /*******************  写入数据  *******************/
        $res = $this->nav_model->allowField(true)->save($data,$data['id']);
        /*******************  返回信息  *******************/
        if ($res !==false) {
            return return_msg(200,'修改成功');
        }
    }
}
