<?php
//  
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//   
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

use Ecjia\System\Admins\Plugin\ConfigMenu;
use Ecjia\System\Admins\Privilege\PrivilegeMenu;

class admin_system_hooks {


	public static function cloud_checked()
    {
        ecjia_cloud::instance()->api('product/analysis/record')->data(ecjia_utility::get_site_info())->cacheTime(21600)->run();
    }
	
	
	public static function admin_dashboard_left_1() {
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
	
	
	public static function admin_dashboard_right_1() {
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
	
	public static function admin_dashboard_right_2() {
	    $title = __('产品新闻');
	    
	    $product_news = ecjia_utility::site_admin_news();
        if (! empty($product_news)) {
            ecjia_admin::$controller->assign('title'	  , $title);
            ecjia_admin::$controller->assign('product_news'  , $product_news);
            echo ecjia_admin::$controller->fetch('library/widget_admin_dashboard_product_news.lbi');
        }	    
	}
	
	/**
	 * 添加后台左侧边栏信息
	 */
	public static function admin_sidebar_info() {
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
	
	
	public static function display_admin_plugin_menus() {
	    
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
	
	
	public static function display_admin_privilege_menus() {
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

	public static function display_admin_upgrade_checked()
    {
        $new_version = (new \Ecjia\System\Admins\UpgradeCheck\CloudCheck)->checkUpgrade();
        if ($new_version) {
            $upgrade_url = RC_Uri::url('@upgrade/init');
            $warning = sprintf(__('ECJia到家 v%s 现已经可用！ 请现在下载更新，前往<a href="%s">更新检测</a>。'), $new_version['version'], $upgrade_url);
            ecjia_screen::get_current_screen()->add_admin_notice(new admin_notice($warning));
        }
    }


    public static function display_ecjia_license_checked()
    {
        if (! ecjia_license::instance()->license_check()) {
            $license_url = RC_Uri::url('@license/license');
            $empower_info = sprintf(__('授权提示：您的站点还未经过授权许可！请上传您的证书，前往<a href="%s">授权证书管理</a> 。'), $license_url);
            ecjia_screen::get_current_screen()->add_admin_notice(new admin_notice($empower_info));
        }
    }


    public static function record_admin_session_logins($row)
    {
        RC_Api::api('system', 'admin_session_logins', [
            'user_id' => $row['user_id'],
            'from_type' => 'weblogin',
        ]);
    }


    public static function admin_session_logout_remove()
    {
        $session_id = session()->getId();

        (new \Ecjia\System\Admins\SessionLogins\AdminSessionLogins($session_id, session('session_user_id')))->removeBySessionId();
    }
	
}

RC_Hook::add_action( 'display_admin_plugin_menus', array('admin_system_hooks', 'display_admin_plugin_menus') );
RC_Hook::add_action( 'admin_sidebar_info', array('admin_system_hooks', 'admin_sidebar_info'));
RC_Hook::add_action( 'admin_dashboard_left', array('admin_system_hooks', 'admin_dashboard_left_1') );
RC_Hook::add_action( 'admin_dashboard_right', array('admin_system_hooks', 'admin_dashboard_right_1') );
RC_Hook::add_action( 'admin_dashboard_right', array('admin_system_hooks', 'admin_dashboard_right_2') );
RC_Hook::add_action( 'ecjia_admin_dashboard_index', array('admin_system_hooks', 'display_admin_upgrade_checked') );
RC_Hook::add_action( 'ecjia_admin_dashboard_index', array('admin_system_hooks', 'display_ecjia_license_checked') );
RC_Hook::add_action( 'ecjia_admin_dashboard_index', array('admin_system_hooks', 'cloud_checked') );

RC_Hook::add_action( 'display_admin_privilege_menus', array('admin_system_hooks', 'display_admin_privilege_menus') );
RC_Hook::add_action( 'ecjia_admin_login_after', array('admin_system_hooks', 'record_admin_session_logins') );
RC_Hook::add_action( 'ecjia_admin_logout_before', array('admin_system_hooks', 'admin_session_logout_remove') );


// end