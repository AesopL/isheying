<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\Slide as SlideModel;
use app\admin\model\SlideCategory as SlideCategoryModel;

class Slide extends AdminBase
{
    protected $slide_category_model;
    protected $slide_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->slide_category_model = new SlideCategoryModel();
        $this->slide_model = new SlideModel();
        $slidecategory = $this->slide_category_model->column('name', 'id');

        $this->assign('slidecategory', $slidecategory);
    }

    /**
     * 轮播首页
     *
     * @param string $keyword
     * @return void
     */
    public function index($keyword = '')
    {
        $map = [];
        if (!empty($map)) {
            $map['name'] = ['like', "%{$keyword}%"];
        }
        $slides = $this->slide_model->paginate(15);
        return $this->fetch('index', ['slides' => $slides, 'keyword' => $keyword]);
    }

    public function edit($id)
    {
        $slide = $this->slide_model->get($id);
        return return_msg(200, '获取成功', $slide);
    }

    /**
     * 保存图片
     *
     * @return void
     */
    public function save()
    {
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        $data = $this->request->param();
        $validate_res = $this->validate($data, 'Slide');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }
        $res = $this->slide_model->allowField(true)->save($data);
        if ($res !== false) {
            return return_msg(200, '添加成功');
        } else {
            return return_msg(400, '添加失败');
        }
    }

    /**
     * 上传图片
     *
     * @return void
     */
    public function uploadSlider()
    {
        $data = [];
        $data = $this->request->file('file');
        $thumb_path = upload_file($data);
        if ($thumb_path !== false) {
            $data = [
                'result' => 'ok',
                'url' => $thumb_path,
            ];
        }
        return json($data);

    }

    /**
     * 更新数据
     *
     * @return json
     */
    public function update()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        //    \dump($data);die;
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'Slide');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }
        /*******************  写入数据库  *******************/
        $res = $this->slide_model->allowField(true)->save($data, $data['id']);
        /*******************  返回信息  *******************/
        if ($res !== false) {
            return return_msg(200, '修改成功');
        } else {
            return return_msg(400, '修改失败');
        }
    }

    /**
     * 删除
     *
     * @return void
     */
    public function delete()
    {
        /*******************  验证请求类型  *******************/
        if ($this->request->isAjax()) {
            /*******************  接收参数  *******************/
            $data = $this->request->param();
            //dump($data['id']);die;
            /*******************  删除数据  *******************/
            $res = $this->slide_model->where('id', 'in', $data['id'])->delete();
            if ($res !== false) {
                return $this->return_msg(200, '删除成功');
            } else {
                return $this->return_msg(400, '删除失败');
            }
        }

    }
}
