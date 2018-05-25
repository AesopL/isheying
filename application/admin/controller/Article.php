<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Category as CategroyModel;

class Article extends \app\admin\AdminBase
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

    public function save()
    {
        $data = $this->request->param();
    }
    /**
     * 上传文件
     *
     * @return void
     */
    public function uploaderThumb()
    {
        $data = $this->request->param();
        // dump($data);die;
        // $thumb_path = upload_file($data['name'], 'article_thumb');
        // if ($thumb_path !==false) {
            $data = [
                'result' => 'ok',
                'id' => 10001,
                'url' => 'http://example.com/file-10001.jpg',
            ];
        // }
        return json($data);

    }
}
