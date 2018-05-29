<?php
namespace app\admin\controller;

use app\admin\AdminBase;

class Index extends AdminBase
{

    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 获取服务器信息
     *
     * @return array
     */
    public function index()
    {
        $info = [
            ['title' => '系统', 'name' => PHP_OS],
            ['title' => '运行环境', 'name' => $_SERVER["SERVER_SOFTWARE"]],
            ['title' => 'PHP运行方式', 'name' => php_sapi_name()],
            ['title' => 'ThinkPHP版本', 'name' => THINK_VERSION . ' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]'],
            ['title' => '最大上传', 'name' => ini_get('upload_max_filesize')],
            ['title' => '执行时间', 'name' => ini_get('max_execution_time') . '秒'],
            ['title' => '服务器时间', 'name' => date("Y年n月j日 H:i:s")],
            ['title' => '北京时间', 'name' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600)],
            ['title' => '服务器域名/IP', 'name' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]'],
            ['title' => '剩余空间', 'name' => round((disk_free_space(".") / (1024 * 1024)), 2) . 'M'],
            // ['title' => '', 'name' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF"],
            // ['title' => '', 'name' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO'],
            // ['title' => '', 'name' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO'],
        ];
        //dump($info);
        $this->assign('info', $info);
        return $this->fetch();

    }
}
