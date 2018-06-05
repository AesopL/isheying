<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\SlideCategory as SlideCategoryModel;

class SlideCategory extends AdminBase
{
    protected $slide_category_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->slide_category_model = new SlideCategoryModel;

    }
    public function index($keyword = '')
    {
        $map = [];
        if (!empty($keyword)) {
            $map['name'] = ['like', "%{$keyword}%"];
        }
        $slide_categories = $this->slide_category_model->where($map)->select();
        return $this->fetch('index', ['slide_categories' => $slide_categories, 'keyword' => $keyword]);
    }

    public function edit($id)
    {
        $data = $this->slide_category_model->get($id);
        return return_msg(200, '获取成功', $data);
    }

    public function save()
    {
        if (!$this->request->isAjax()) {
            return reuurn_msg(400, '请求错误');
        }

        $data = $this->request->param();
        $validate_res = $this->validate($data, 'SlideCategory');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }

        $res = $this->slide_category_model->allowField(true)->save($data);
        if ($res !== false) {
            return return_msg(200, '添加成功');
        } else {
            return return_msg(400, '添加失败');
        }
    }

    public function update()
    {
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        $data = $this->request->param();
        //dump($data);die;
        $validate_res = $this->validate($data, 'SlideCategory');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }

        $res = $this->slide_category_model->allowField(true)->save($data, $data['id']);
        if ($res !== false) {
            return return_msg(200, '修改成功');
        } else {
            return return_msg(400, '修改失败');
        }

    }

    public function delete()
    {
        /*******************  验证请求类型  *******************/
        if ($this->request->isAjax()) {
            /*******************  接收参数  *******************/
            $data = $this->request->param();
            //dump($data['id']);die;
            /*******************  删除数据  *******************/
            $res = $this->slide_category_model->where('id', 'in', $data['id'])->delete();
            if ($res !== false) {
                return $this->return_msg(200, '删除成功');
            } else {
                return $this->return_msg(400, '删除失败');
            }
        }
    }
}
