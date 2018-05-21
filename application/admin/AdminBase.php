<?php
namespace app\admin;

use org\Auth;
use think\Controller;
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
        $this->checkAuth();
        /*******************  获取菜单  *******************/
        $this->getMenu();
        // 输出当前请求控制器（配合后台侧边菜单选中状态）
        $this->assign('controller', Loader::parseName($this->request->controller()));

    }

    public function checkAuth()
    {
        /*******************  当前请求的模块控制器方法  *******************/
        $mod_ctr_ac = [
            'module' => $this->request->module(),
            'controller' => $this->request->controller(),
            'action' => $this->request->action(),
        ];
        /*******************  实例化Auth类  *******************/
        $auth = new Auth();
        /*******************  当前管理员ID  *******************/
        $id = session('uid');
        /*******************  排除模块  *******************/
        $not_check = ['admin/Index/index'];
        /*******************  检查权限  *******************/
        if (!in_array($mod_ctr_ac['module'] . '/' . $mod_ctr_ac['controller'] . '/' . $mod_ctr_ac['action'], $not_check)) {
            $auth_res = $auth->check($mod_ctr_ac['module'] . '/' . $mod_ctr_ac['controller'] . '/' . $mod_ctr_ac['action'], $id);
            if ($auth_res !== true) {
              $this->error('没有权限');
            }
        }

    }

    /**
     * 获取侧边栏菜单
     */
    protected function getMenu()
    {
        $menu = [];
        $admin_id = session('uid');
        $auth     = new Auth();
        /*******************  获取菜单  *******************/
        $auth_rule_list = db('auth_rule')->order(['sort' => 'DESC', 'id' => 'ASC'])->where('status', 1)->select();
        /*******************  根据权限筛选列表  *******************/
        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
                $menu[] = $value;
            }
        }
        $menu = !empty($auth_rule_list) ? array2tree($menu) : [];
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
