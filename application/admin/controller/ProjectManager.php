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

    public function add(){
        return $this->fetch();
    }

    public function job_log(){
        return $this->fetch();
    }

    public function log_detail(){
        return $this->fetch();
    }

    public function add_job_log(){
        return $this->fetch();  
    }
}