<?php
namespace app\admin\controller;

use app\admin\AdminBase;

class Index extends AdminBase
{

    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        return $this->fetch();
    }
}
