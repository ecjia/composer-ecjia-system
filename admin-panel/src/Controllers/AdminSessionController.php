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

namespace Ecjia\System\AdminPanel\Controllers;

use admin_nav_here;
use admin_notice;
use ecjia;
use Ecjia\System\Admins\Redis\RedisManager;
use Ecjia\System\Admins\Sessions\SessionManager;
use ecjia_admin;
use ecjia_screen;
use RC_Script;
use RC_Style;
use RC_Uri;

class AdminSessionController extends ecjia_admin
{

    public function __construct()
    {
        parent::__construct();


        RC_Style::enqueue_style('chosen');
        RC_Script::enqueue_script('smoke');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('jquery-chosen');
        RC_Script::enqueue_script('ecjia-admin_logs');

        //js语言包调用
        RC_Script::localize_script('ecjia-admin_logs', 'admin_logs_lang', config('system::jslang.logs_page'));
    }

    public function init()
    {
        $this->admin_priv('session_manage');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('会话管理')));

        ecjia_screen::get_current_screen()->add_help_tab(array(
            'id'      => 'overview',
            'title'   => __('概述'),
            'content' =>
                '<p>' . __('欢迎访问ECJia智能后台会员管理页面，可以在此查看用户登录操作的一些会话记录信息。') . '</p>'
        ));

        $redis = new RedisManager();

        if (!$redis->testConnection()) {
            $warning = sprintf(__("当前功能需要依赖 Redis 支持，未检测到正确连接的Redis，请检查配置或安装Redis后再继续。<br />
                Redis的配置可在 content/configs/database.php 中 connections.redis.session 进行配置，<br />
                也可在根目录 .env 中进行配置，涉及到的配置项含有【REDIS_HOST】【REDIS_PORT】，请再次确认。
            "));
            ecjia_screen::get_current_screen()->add_admin_notice(new admin_notice($warning));

            $logs = [];
        } else {

            $keyword = $this->request->input('keyword');

            $session = new SessionManager($redis->getConnection());

            //搜索时
            if (!empty($keyword)) {
                $keys = $session->getSearchKeys($keyword);
                $logs = $session->valueUnSerializeForKeys($keys)->all();

                $this->assign('keyword', $keyword);
            } else {
                $logs = $session->getKeysWithValueUnSerialize();
            }

            $current_type = $this->request->input('type', 'all');

            $navs = [
                [
                    'type'  => 'all',
                    'count' => $session->count(),
                    'url'   => RC_Uri::url('@admin_session/init'),
                    'label' => __('全部')
                ]
            ];

            $this->assign('current_type', $current_type);
            $this->assign('navs', $navs);
        }

        $this->assign('ur_here', __('会话管理'));

        $this->assign('logs', $logs);

        return $this->display('admin_session.dwt');
    }

    /**
     * 查看详情
     */
    public function detail()
    {
        $this->admin_priv('session_manage');

        try {
            $key = trim($this->request->input('key'));

            $session = (new SessionManager())->getSessionKey($key);

            $this->assign('session_info', $session);

            $data = $this->fetch('admin_session_detail.dwt');

            return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('data' => $data));
        }
        catch (\Exception $exception) {
            return $this->showmessage($exception->getMessage(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
    }

    /**
     * 删除
     */
    public function remove()
    {
        $this->admin_priv('session_manage');

        try {
            $key = trim($this->request->input('key'));

            (new SessionManager())->deleteSessionKey($key);

            return $this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
        }
        catch (\Exception $exception) {
            return $this->showmessage($exception->getMessage(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
    }

}