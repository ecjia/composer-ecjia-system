<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-03-15
 * Time: 14:10
 */

namespace Ecjia\System\Subscribers;

use Ecjia\System\BaseController\EcjiaDefaultController;
use ecjia_license;
use ecjia;
use Royalcms\Component\Hook\Dispatcher;

class InstalledScreenSubscriber
{

    public function onRoyalcmsDefaultControllerAction($arg)
    {
        return new EcjiaDefaultController();
    }

    /**
     * 自定义商店关闭后输出
     */
    public function onCustomShopClosedAction()
    {
        header('Content-type: text/html; charset='.RC_CHARSET);
        die('<div style="margin: 150px; text-align: center; font-size: 14px"><p>' . __('本店盘点中，请您稍后再来...') . '</p><p>' . ecjia::config('close_comment') . '</p></div>');
    }


    public function onEcjiaGeneralInfoFilter($data)
    {
        if (! ecjia_license::instance()->license_check()) {
            $data['powered'] = ecjia::powerByLink();
        } else {
            $data['powered'] = '';
        }
        return $data;
    }


    public function onPageTitleSuffixFilter($title)
    {
        if (defined('ROUTE_M') && ROUTE_M != 'installer') {
            if (ecjia_license::instance()->license_check()) {
                return '';
            }
        }
        $suffix = ' - ' . ecjia::powerByText();
        return $suffix;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {

        //hookers
        $events->addAction(
            'init',
            'Ecjia\System\Hookers\LoadThemeFunctionAction'
        );

        //action
        $events->addAction(
            'royalcms_default_controller',
            'Ecjia\System\Subscribers\InstalledScreenSubscriber@onRoyalcmsDefaultControllerAction'
        );
        $events->addAction(
            'ecjia_shop_closed',
            'Ecjia\System\Subscribers\InstalledScreenSubscriber@onCustomShopClosedAction'
        );

        //filter
        $events->addFilter(
            'ecjia_general_info_filter',
            'Ecjia\System\Subscribers\InstalledScreenSubscriber@onEcjiaGeneralInfoFilter'
        );
        $events->addFilter(
            'page_title_suffix',
            'Ecjia\System\Subscribers\InstalledScreenSubscriber@onPageTitleSuffixFilter'
        );


    }

}