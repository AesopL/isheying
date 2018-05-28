<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Category as CategroyModel;

class Article extends AdminBase
{
    protected $article_model;
    protected $category_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->article_model = new ArticleModel();
        $this->category_model = new CategroyModel();
        $categories = $this->category_model->order(['sort' => 'DESC'])->select();
        $categories = array2level($categories);
        $this->assign('categories', $categories);
    }

    public function index($cid = 0, $keyword = '', $page = 1)
    {
        $map = [];
        /*******************  需要查询的字段  *******************/
        $field = 'id,title,cid,author,reading,status,is_top,is_recommend,publish_time,sort';

        /*******************  是否分类查询  *******************/
        if ($cid > 0) {
            $category_children_ids = $this->category_model->where(['path' => ['like', "%,{$cid},%"]])->column('id');
            $category_children_ids = (!empty($category_children_ids) && is_array($category_children_ids)) ? implode(',', $category_children_ids) . ',' . $cid : $cid;
            $map['cid'] = ['IN', $category_children_ids];
        }
        //dump($keyword);

        /*******************  是否有关键字查询  *******************/
        if (!empty($keyword)) {
            $map['title'] = ['like', "%{$keyword}%"];
        }

        $articles_list = $this->article_model->field($field)->where($map)->order(['publish_time' => 'DESC'])->paginate(15, false, ['page' => $page]);
        $categories_list = $this->category_model->column('name', 'id');

        //dump($articles);
        return $this->fetch('index', ['articles_list' => $articles_list, 'categories_list' => $categories_list, 'cid' => $cid, 'keyword' => $keyword]);
    }

    public function add()
    {
        return $this->fetch();
    }

    /**
     * 文章保存
     *
     * @return json信息
     */
    public function save()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return \return_msg(400, '请求错误');
        }
        /*******************  接收参数  *******************/
        $data = $this->request->post();
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'Article');
        if ($validate_res !== true) {
            return return_msg('400', $validate_res);
        }
        /*******************  写入数据库  *******************/
        //$res = $this->article_model->allowField(true)->save($data);
        /*******************  返回信息  *******************/
        if ($res !== false) {
            return return_msg(200, '添加成功');
        } else {
            return return_msg(400, '添加失败');
        }
    }

    /**
     * 编辑文章
     *
     * @param [string|int] $id
     * @return void
     */
    public function edit($id)
    {
        $article = $this->article_model->get($id);
        //dump($article);die;
        return $this->fetch('edit', ['article' => $article]);
    }

    public function update()
    {
        /*******************  判断请求  *******************/
        if (!$this->request->isAjax()) {
            return return_msg(400, '请求错误');
        }
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  验证数据  *******************/
        $validate_res = $this->validate($data, 'Article');
        if ($validate_res !== true) {
            return return_msg(400, $validate_res);
        }
        /*******************  写入数据库  *******************/
        $res = $this->article_model->allowField(true)->save($data, $data['id']);
        if ($res !== false) {
            return return_msg(200, '修改成功');
        } else {
            return return_msg(400, '修改失败');
        }
    }

    /**
     * 删除文章[包括批量删除]
     *
     * @return void
     */
    public function delete()
    {
        /*******************  接收数据  *******************/
        $data = $this->request->param();
        /*******************  将id转为字符串  *******************/
        $id = is_array($data['id']) ? implode(',', $data['id']) : $data['id'];

        /*******************  删除数据  *******************/
        $res = $this->article_model->destroy($id);
        if ($res !== false) {
            return $this->return_msg(200, '删除成功');
        } else {
            return $this->return_msg(400, '删除失败');
        }

    }
    /**
     * 上传文件
     *
     * @return void
     */
    public function uploaderThumb()
    {
        $data = $this->request->file('file');

        //dump($data);die;
        $thumb_path = upload_file($data, 'article_thumb');
        if ($thumb_path !== false) {
            $data = [
                'result' => 'ok',
                'url' => $thumb_path,
            ];
        }
        return json($data);

    }
}
