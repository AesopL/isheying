<?php
namespace app\admin;

use think\Controller;
use think\Db;
use think\Loader;
use think\Session;

class AdminBase extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
        if (!Session::has('admin_name')) {
            $this->redirect('admin/login/index');
        }

        $this->getMenu();
        // 输出当前请求控制器（配合后台侧边菜单选中状态）
        $this->assign('controller', Loader::parseName($this->request->controller()));

    }

    /**
     * 获取侧边栏菜单
     */
    protected function getMenu()
    {
        $menu = [];
        // $admin_id = Session::get('admin_id');
        // $auth     = new Auth();

        $auth_rule_list = Db('auth_rule')->order(['sort' => 'DESC', 'id' => 'ASC'])->where('status', 1)->select();

        // foreach ($auth_rule_list as $value) {
        //     if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
        //         $menu[] = $value;
        //     }
        // }
        $menu = !empty($auth_rule_list) ? array2tree($auth_rule_list) : [];
        //dump($menu);
        $this->assign('menu', $menu);
    }

    /**
     * 返回数据信息
     *
     * @param [int] $code 结果码[200:成功，400:数据错误，500:服务器错误]
     * @param string $msg 提示信息
     * @param array $data 数据
     * @return json 组合提示信息
     */
    protected function return_msg($code, $msg = '', $data = [])
    {
        $return_data['code'] = $code;
        $return_data['msg'] = $msg;
        $return_data['data'] = $data;
        return $return_data;

    }
}
