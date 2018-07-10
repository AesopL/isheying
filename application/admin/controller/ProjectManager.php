<?php
namespace app\admin\controller;
use app\admin\AdminBase;
class ProjectManager extends AdminBase{
    protected function _initialize(){
        parent::_initialize();
    }
    //首页
    public function index(){
        return $this->fetch();
    }
}