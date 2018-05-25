<?php
namespace app\admin\controller;

use app\admin\AdminBase;

/**
 * 站点配置
 */
class SiteConfig extends AdminBase
{
    public function index()
    {
        //dump(serialize($data));
        $site_configs = db('system')->select();
        //$site_configs = unserialize($site_config['value']);
        foreach ($site_configs as $key => $value) {
            $site_configs[$key]['value'] = unserialize($value['value']);
        }
        //dump($site_configs);die;
        return $this->fetch('index', ['site_configs' => $site_configs]);

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
        if (db('system')->where('id', $id)->setField('value', $data['value']) !== false) {
            $this->success('提交成功');
        } else {
            $this->error('提交失败');
        }

    }

    public function siteConfig($id = 1)
    {
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
