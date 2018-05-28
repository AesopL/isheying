<?php
namespace app\admin\controller;

use app\admin\AdminBase;
use app\admin\model\Category as CategoryModel;

class Category extends AdminBase
{
    protected $category_model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->category_model = new CategoryModel();
    }

    public function index()
    {
       $categories = $this->category_model->order(['sort' => 'DESC'])->paginate(10);
       $this->assign('categories',$categories);
        return $this->fetch();
    }
}
