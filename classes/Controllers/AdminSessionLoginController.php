<?php


namespace Ecjia\System\Controllers;


use admin_nav_here;
use ecjia_admin;
use ecjia_screen;

class AdminSessionLoginController extends ecjia_admin
{

    public function __construct()
    {
        parent::__construct();


    }

    public function init()
    {
        $this->admin_priv('session_login_manage');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('登录日志')));

        ecjia_screen::get_current_screen()->add_help_tab(array(
            'id' => 'overview',
            'title' => __('概述'),
            'content' =>
                '<p>' . __('欢迎访问ECJia智能后台会员管理页面，可以在此查看用户登录操作的一些会话记录信息。') . '</p>'
        ));




    }

}