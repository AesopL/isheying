<?php
namespace app\admin;

use org\Auth;
use think\Controller;
use think\Loader;
use think\Session;

/**
 * 基类控制器
 * $mod_ctr_ac  当前模块控制器方法数组
 * $mod_ctr_ac_id   当前当前模块控制器方法数组的id
 */
class AdminBase extends Controller
{
    protected $mod_ctr_ac; //当前模块控制器方法数组
    protected $mod_ctr_ac_id; //当前当前模块控制器方法数组的id

    protected function _initialize()
    {
        parent::_initialize();
        if (!Session::has('admin_name')) {
            $this->redirect('admin/login/index');
        }
        /*******************  当前请求的模块控制器方法  *******************/
        $this->mod_ctr_ac = [
            'm' => $this->request->module(),
            'c' => $this->request->controller(),
            'a' => $this->request->action(),
        ];
        /*******************  当前访问的auth_rule的id  *******************/
        $this->mod_ctr_ac_id = db('auth_rule')
            ->where('name', $this->mod_ctr_ac['m'] . '/' . $this->mod_ctr_ac['c'] . '/' . $this->mod_ctr_ac['a'])
            ->field('id')
            ->find();
        $this->breadcrumbs = $this->getBreadcrumb($this->mod_ctr_ac_id);

        /*******************  检查权限  *******************/
        $this->checkAuth();
        /*******************  获取菜单  *******************/
        $this->getMenu();
        /*******************  返回面包屑导航数据  *******************/
        $this->assign('breadcrumbs', $this->getBreadcrumb($this->mod_ctr_ac_id));

        $this->assign('action', Loader::parseName($this->request->action()));

        // 输出当前请求控制器（配合后台侧边菜单选中状态）
        $this->assign('controller', Loader::parseName($this->request->controller()));

    }

    /**
     * 检查权限
     *
     * @return 提示信息
     */
    public function checkAuth()
    {

        /*******************  实例化Auth类  *******************/
        $auth = new Auth();
        /*******************  当前管理员ID  *******************/
        $id = session('uid');
        /*******************  排除模块  *******************/
        $not_check = ['admin/Index/index'];
        /*******************  检查权限  *******************/
        if (!in_array($this->mod_ctr_ac['m'] . '/' . $this->mod_ctr_ac['c'] . '/' . $this->mod_ctr_ac['a'], $not_check)) {
            $auth_res = $auth->check($this->mod_ctr_ac['m'] . '/' . $this->mod_ctr_ac['c'] . '/' . $this->mod_ctr_ac['a'], $id);
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
        $auth = new Auth();
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

    /**
     * 面包屑导航数据
     *
     * @param [type] $id
     * @return 数组信息
     */
    public function getBreadcrumb($id)
    {
        $breadcrumb_data = [];
        /*******************  根据id获得pid  *******************/
        $info = db('auth_rule')->field('id,title,pid')->find($id);
        //dump($info);
        $breadcrumb_data[] = $info['title'];
        /*******************  如果存在上级分类，则逐步向上获取上级分类的信息  *******************/
        while ($info['pid'] != 0 && $info['pid'] != "") {
            /*******************  上级分类信息  *******************/
            $info = $this->getAuthRuleById($info['pid']);
            $breadcrumb_data[] = $info['title']; //上级分类名称
        }
        //dump($breadcrumb_data);
        return array_reverse($breadcrumb_data);

    }

    /**
     * id查询auth_rule的数据
     *
     * @param [int] $id
     * @return 单个数组
     */
    public function getAuthRuleById($id)
    {
        return db('auth_rule')->field('id,title,pid')->find($id);
    }

}
