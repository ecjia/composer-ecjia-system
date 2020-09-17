<?php


namespace Ecjia\System\AdminPanel\Controllers;


use admin_nav_here;
use ecjia;
use Ecjia\Component\SessionLogins\SessionLoginsModel;
use ecjia_admin;
use ecjia_screen;
use RC_Script;

class AdminSessionLoginController extends ecjia_admin
{

    public function __construct()
    {
        parent::__construct();

        RC_Script::enqueue_script('smoke');
    }

    public function init()
    {
        $this->admin_priv('session_login_manage');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('登录日志')));

        ecjia_screen::get_current_screen()->add_help_tab(array(
            'id'      => 'overview',
            'title'   => __('概述'),
            'content' =>
                '<p>' . __('欢迎访问ECJia智能后台会员管理页面，可以在此查看用户登录操作的一些会话记录信息。') . '</p>'
        ));

        $logs = SessionLoginsModel::get()->toArray();

        $this->assign('ur_here', __('登录日志'));

        $this->assign('logs', $logs);

        return $this->display('admin_session_login.dwt');
    }

    /**
     * 删除
     */
    public function remove()
    {
        $this->admin_priv('session_manage');

        $key = trim($this->request->input('key'));

        SessionLoginsModel::where('id', $key)->delete();

        return $this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
    }

}