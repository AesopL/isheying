<?php
namespace app\admin\controller;

use app\admin\AdminBase;

class System extends AdminBase
{
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

        $site_config = db('system')->field('value')->where('name', 'site_config')->find();
        $site_config = unserialize($site_config['value']);
        $this->assign('site_config', $site_config);
        $this->assign('info', $info);

        return $this->fetch();
    }

    /**
     * 编辑站点配置页面
     *
     * @param integer $id
     * @return void
     */
    public function editSiteConfig($id = 1)
    {
        $site_config = db('system')->field('value')->where('id', $id)->find();
        $site_config = unserialize($site_config['value']);
        $site_config['id'] = $id;
        //dump($site_config);die;
        return $this->fetch('edit_site_config', ['site_config' => $site_config]);
    }

    public function updateSiteConfig()
    {
        $data = $this->request->post('site_config/a');
        $data['site_tongji'] = htmlspecialchars_decode($data['site_tongji']);
        $id = $data['id'];
        $data['value'] = serialize($data);
        //dump($data);die;
        if (db('system')->where('id', $id)->setField('value',$data['value']) !== false) {
            $this->success('提交成功');
        } else {
            $this->error('提交失败');
        }

    }

    public function siteConfig($id = 1)
    {
        $data = [
            "site_title" => "后台管理系统",
            "seo_title" => "",
            "seo_keyword" => "",
            "seo_description" => "",
            "site_copyright" => "",
            "site_icp" => "",
            "site_tongji" => "",
        ];
        //dump(serialize($data));
        $site_configs = db('system')->select();
        //$site_configs = unserialize($site_config['value']);
        foreach ($site_configs as $key => $value) {
            $site_configs[$key]['value'] = unserialize($value['value']);
        }
        //dump($site_configs);die;
        //dump($site_config);die;
        return $this->fetch('site_config', ['site_configs' => $site_configs]);
    }
}
