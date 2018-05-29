<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\Category as CategoryModel;
use app\admin\model\Article as ArticleModel;

class Category extends AdminBase
{
    protected $category_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->category_model = new CategoryModel();
        $this->article_model = new ArticleModel();
        $category_level_list = $this->category_model->getLevelList();
        //dump($category_level_list);
        $this->assign('category_level_list', $category_level_list);

    }

    public function index()
    {
        $categories = $this->category_model->order(['sort' => 'DESC'])->paginate(10);
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    /**
     * 添加页面
     *
     * @return void
     */
    public function add($pid = '')
    {
        return $this->fetch('add', ['pid' => $pid]);
    }

    /**
     * 保存数据
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
        /*******************  验证参数  *******************/
        $validate_res = $this->validate($data, 'Category');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }
        /*******************  写入数据  *******************/
        $res = $this->category_model->allowField(true)->save($data);
        /*******************  返回信息  *******************/
        if ($res !== false) {
            return return_msg(200, '添加成功');
        } else {
            return return_msg(400, '添加失败');
        }
    }

    public function edit($id)
    {
        $category = $this->category_model->get($id);
        //dump($category);
        return $this->fetch('edit', ['category' => $category]);
    }

    public function update()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收参数  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'Category');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }
        //dump($data);
        /*******************  写入数据  *******************/
        $res = $this->category_model->allowField(true)->save($data, $data['id']);
        /*******************  返回信息  *******************/
        if ($res !== false) {
            return return_msg(200, '修改成功');
        } else {
            return return_msg(400, '修改失败');
        }
    }

    public function delete()
    {
        /*******************  接收id  *******************/
        $data = $this->request->param();
        //dump($data);die;
        /*******************  获取父级  *******************/
        $category = $this->category_model->where(['pid' => $data['id']])->find();
        /*******************  获取分类下的文章  *******************/
        $article = $this->article_model->where(['cid' => $data['id']])->find();

        if (!empty($category)) {
            return return_msg(400,'此分类下存在子分类，不可删除');
        }
        if (!empty($article)) {
            return return_msg(400,'此分类下存在文章，不可删除');
        }
        if ($this->category_model->destroy($data['id'])) {
           return return_msg(200,'删除成功');
        } else {
            return return_msg(400,'删除失败');
        }

    }
}
