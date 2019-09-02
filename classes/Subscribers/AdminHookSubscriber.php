<?php


namespace Ecjia\System\Subscribers;

use admin_notice;
use ecjia;
use Ecjia\System\Admins\Plugin\ConfigMenu;
use Ecjia\System\Admins\Privilege\PrivilegeMenu;
use ecjia_admin;
use ecjia_admin_message;
use ecjia_app;
use ecjia_cloud;
use ecjia_license;
use ecjia_screen;
use ecjia_utility;
use RC_Api;
use RC_Cache;
use RC_DB;
use RC_Uri;
use Royalcms\Component\Hook\Dispatcher;

class AdminHookSubscriber
{

    /**
     * Handle admin message display dashboard events.
     */
    public function onAdminDashboardLeftMessageAction()
    {
        $title = __('管理员留言');

        $chat_list = RC_Cache::app_cache_get('admin_dashboard_admin_message', 'system');
        if (! $chat_list) {
            $chat_list = ecjia_admin_message::get_admin_chat(array('page_size' => 5));
            RC_Cache::app_cache_set('admin_dashboard_admin_message', $chat_list, 'system', 15);
        }
        ecjia_admin::$controller->assign('title'			, $title);
        ecjia_admin::$controller->assign('msg_lists'		, $chat_list['item']);
        echo ecjia_admin::$controller->fetch('library/widget_admin_dashboard_messagelist.lbi');
    }


    /**
     * Handle admin log display dashboard events.
     */
    public function onAdminDashboardRightLogAction()
    {
        if (!ecjia_admin::$controller->admin_priv('logs_manage', ecjia::MSGTYPE_HTML, false)) {
            return false;
        }

        $title = __('操作日志');
        $data = RC_Cache::app_cache_get('admin_dashboard_admin_log', 'system');
        if (!$data) {
            $data = RC_DB::table('admin_log')->select('admin_log.*', 'admin_user.user_name')->leftJoin('admin_user', 'admin_log.user_id', '=', 'admin_user.user_id')->orderBy('log_id', 'desc')->take(5)->get();
            RC_Cache::app_cache_set('admin_dashboard_admin_log', $data, 'system', 30);
        }

        ecjia_admin::$controller->assign('title'	  , $title);
        ecjia_admin::$controller->assign('log_lists'  , $data);
        echo ecjia_admin::$controller->fetch('library/widget_admin_dashboard_loglist.lbi');
    }

    /**
     * Handle ecjia product news display dashboard events.
     */
    public function onAdminDashboardRightProductNewsAction()
    {
        $title = __('产品新闻');

        $product_news = ecjia_utility::site_admin_news();
        if (! empty($product_news)) {
            ecjia_admin::$controller->assign('title'	  , $title);
            ecjia_admin::$controller->assign('product_news'  , $product_news);
            echo ecjia_admin::$controller->fetch('library/widget_admin_dashboard_product_news.lbi');
        }
    }

    /**
     * Handle admin dashboard sidebar info events.
     *
     * 添加后台左侧边栏信息
     */
    public function onAdminSidebarInfoAction()
    {
        $cache_key = 'admin_remind_sidebar';
        $remind = RC_Cache::userdata_cache_get($cache_key, $_SESSION['admin_id'], true);

        if (empty($remind)) {
            $remind = array();

            /*今日新增用户*/
            $validate_app_user = ecjia_app::validate_application('user');
            if (!is_ecjia_error($validate_app_user)) {
                $remind_user = RC_Api::api('user', 'remind_user');

                $new_user_count = (!empty($remind_user['new_user_count']) && is_numeric($remind_user['new_user_count'])) ? $remind_user['new_user_count'] : 0;

                $remind[] = array(
                    'label' => __('今日新增用户'),
                    'total' => $new_user_count,
                    'style' => 'danger',
                );
            }

            /*留言*/
            $validate_app_feedback = ecjia_app::validate_application('feedback');
            if (!is_ecjia_error($validate_app_feedback)) {
                $remind_message = RC_Api::api('feedback', 'remind_feedback');

                $message_count = (!empty($remind_message['message_count']) && is_numeric($remind_message['message_count'])) ? $remind_message['message_count'] : 0;

                $remind[] = array(
                    'label' => __('新手机咨询'),
                    'total' => $message_count,
                    'style' => 'warning',
                );
            }

            /*今日新增订单*/
            $validate_app_order = ecjia_app::validate_application('orders');
            if (!is_ecjia_error($validate_app_order)) {
                $remind_order = RC_Api::api('orders', 'remind_order');

                $new_orders = (!empty($remind_order['new_orders']) && is_numeric($remind_order['new_orders'])) ? $remind_order['new_orders'] : 0;

                $remind[] = array(
                    'label' => __('今日新增订单'),
                    'total' =>  $new_orders,
                    'style' => 'success',
                );
            }

            RC_Cache::userdata_cache_set($cache_key, $remind, $_SESSION['admin_id'], true, 5);
        }

        if (! empty($remind)) {
            ecjia_admin::$controller->assign('remind'  , $remind);
            echo ecjia_admin::$controller->fetch('library/widget_admin_dashboard_remind_sidebar.lbi');
        }
    }

    /**
     * 显示插件菜单
     */
    public function onDisplayAdminPluginMenusAction()
    {
        $menus = ConfigMenu::singleton()->authMenus();
        $screen = ecjia_screen::get_current_screen();

        echo '<div class="setting-group">'.PHP_EOL;
        echo '<span class="setting-group-title"><i class="fontello-icon-cog"></i>插件配置</span>'.PHP_EOL;
        echo '<ul class="nav nav-list m_t10">'.PHP_EOL; //

        foreach ($menus as $key => $menu)
        {
            if ($menu->action == 'divider')
            {
                echo '<li class="divider"></li>';
            }
            elseif ($menu->action == 'nav-header')
            {
                echo '<li class="nav-header">' . $menu->name . '</li>';
            }
            else
            {
                echo '<li><a class="setting-group-item'; //data-pjax

                if ($menu->base && $screen->parent_base && $menu->base == $screen->parent_base)
                {
                    echo ' llv-active';
                }

                echo '" href="' . $menu->link . '">' . $menu->name . '</a></li>'.PHP_EOL;
            }
        }

        echo '</ul>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
    }

    /**
     * 管理员权限菜单
     */
    public function onDisplayAdminPrivilegeMenusAction()
    {
        $screen = ecjia_screen::get_current_screen();
        $code = $screen->get_option('current_code');

        $menus = with(new PrivilegeMenu())->authMenus();

        echo '<ul class="nav nav-tabs">' . PHP_EOL;

        foreach ($menus as $key => $group) {
            if ($group->action == 'divider') {
            } elseif ($group->action == 'nav-header') {
            } else {
                echo '<li';

                if ($code == $group->action) {
                    echo ' class="active"';
                }
                echo '>';

                echo '<a class="data-pjax" href="' . $group->link . '">' . $group->name . '</a></li>'.PHP_EOL;//data-pjax
            }
        }

        echo '</ul>'.PHP_EOL;
    }

    /**
     * Upgrade checked.
     */
    public function onDisplayAdminUpgradeCheckedAction()
    {
        $new_version = (new \Ecjia\System\Admins\UpgradeCheck\CloudCheck)->checkUpgrade();
        if ($new_version) {
            $upgrade_url = RC_Uri::url('@upgrade/init');
            $warning = sprintf(__('ECJia到家 v%s 现已经可用！ 请现在下载更新，前往<a href="%s">更新检测</a>。'), $new_version['version'], $upgrade_url);
            ecjia_screen::get_current_screen()->add_admin_notice(new admin_notice($warning));
        }
    }

    /**
     * License checked.
     */
    public function onDisplayEcjiaLicenseCheckedAction()
    {
        if (! ecjia_license::instance()->license_check()) {
            $license_url = RC_Uri::url('@license/license');
            $empower_info = sprintf(__('授权提示：您的站点还未经过授权许可！请上传您的证书，前往<a href="%s">授权证书管理</a> 。'), $license_url);
            ecjia_screen::get_current_screen()->add_admin_notice(new admin_notice($empower_info));
        }
    }

    /**
     * ecjia cloud checked.
     */
    public function onDisplayEcjiaCloudCheckedAction()
    {
        ecjia_cloud::instance()->api('product/analysis/record')->data(ecjia_utility::get_site_info())->cacheTime(21600)->run();
    }

    /**
     * admin session logins.
     */
    public function onRecordAdminSessionLoginsAction($row)
    {
        RC_Api::api('system', 'admin_session_logins', [
            'user_id' => $row['user_id'],
            'from_type' => 'weblogin',
        ]);
    }

    /**
     * admin session logout.
     */
    public function onAdminSessionLogoutRemoveAction()
    {
        $session_id = session()->getId();

        (new \Ecjia\System\Admins\SessionLogins\AdminSessionLogins($session_id, session('session_user_id')))->removeBySessionId();
    }

    /**
     * 添加IE支持的header信息
     */
    public function onIsSupportHeader()
    {
        if (is_ie()) {
            echo "\n";
            echo '<!--[if lte IE 8]>'. "\n";
            echo '<link rel="stylesheet" href="' . RC_Uri::admin_url() . '/statics/lib/ie/ie.css" />'. "\n";
            echo '<![endif]-->'. "\n";
            echo "\n";
            echo '<!--[if lt IE 9]>'. "\n";
            echo '<script src="' . RC_Uri::admin_url() . '/statics/lib/ie/html5.js"></script>'. "\n";
            echo '<script src="' . RC_Uri::admin_url() . '/statics/lib/ie/respond.min.js"></script>'. "\n";
            echo '<script src="' . RC_Uri::admin_url() . '/statics/lib/flot/excanvas.min.js"></script>'. "\n";
            echo '<![endif]-->'. "\n";
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->addAction(
            'admin_dashboard_left',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onAdminDashboardLeftMessageAction'
        );

        $events->addAction(
            'admin_dashboard_right',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onAdminDashboardRightLogAction'
        );

        $events->addAction(
            'admin_dashboard_right',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onAdminDashboardRightProductNewsAction'
        );

        $events->addAction(
            'admin_sidebar_info',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onAdminSidebarInfoAction'
        );

        $events->addAction(
            'display_admin_plugin_menus',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onDisplayAdminPluginMenusAction'
        );

        $events->addAction(
            'display_admin_privilege_menus',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onDisplayAdminPrivilegeMenusAction'
        );

        $events->addAction(
            'ecjia_admin_dashboard_index',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onDisplayAdminUpgradeCheckedAction'
        );

        $events->addAction(
            'ecjia_admin_dashboard_index',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onDisplayEcjiaLicenseCheckedAction'
        );

        $events->addAction(
            'ecjia_admin_dashboard_index',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onDisplayEcjiaCloudCheckedAction'
        );

        $events->addAction(
            'ecjia_admin_login_after',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onRecordAdminSessionLoginsAction'
        );

        $events->addAction(
            'ecjia_admin_logout_before',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onAdminSessionLogoutRemoveAction'
        );

        $events->addAction(
            'admin_head',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onIsSupportHeader'
        );

        //hookers
        $events->addAction(
            'admin_print_main_bottom',
            'Ecjia\System\Hookers\DisplayAdminCopyrightAction'
        );
        $events->addAction(
            'admin_dashboard_top',
            'Ecjia\System\Hookers\DisplayAdminWelcomeAction'
        );
        $events->addAction(
            'admin_print_header_nav',
            'Ecjia\System\Hookers\DisplayAdminHeaderNavAction'
        );
        $events->addAction(
            'admin_sidebar_collapse_search',
            'Ecjia\System\Hookers\DisplayAdminSidebarNavSearchAction'
        );
        $events->addAction(
            'admin_sidebar_collapse',
            'Ecjia\System\Hookers\DisplayAdminSidebarNavAction'
        );

    }

}