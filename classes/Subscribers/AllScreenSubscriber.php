<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-03-15
 * Time: 14:16
 */

namespace Ecjia\System\Subscribers;

use RC_Config;
use RC_Hook;
use RC_Upload;
use Royalcms\Component\Hook\Dispatcher;

class AllScreenSubscriber
{

    /**
     * 移除$_ENV中的敏感信息
     * @param $tables
     * @return mixed
     */
    public function onRemoveEnvPrettyPageTableDataFilter($tables)
    {
        $env = collect($tables['Environment Variables']);
        $server = collect($tables['Server/Request Data']);

        $col = collect([
            'AUTH_KEY',
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
            'DB_PREFIX'
        ]);
        $col->map(function ($item) use ($env, $server) {
            $env->pull($item);
            $server->pull($item);
        });

        $tables['Environment Variables'] = $env->all();
        $tables['Server/Request Data'] = $server->all();
        return $tables;
    }

    /**
     * 更新上传路径动态更新
     */
    public function onUpdateCustomStoragePathAction()
    {
        if (RC_Config::has('site.custom_upload_path')) {
            RC_Config::set('storage.disks.direct.root', RC_Upload::custom_upload_path());
            RC_Config::set('storage.disks.local.root', RC_Upload::custom_upload_path());
        }

        if (RC_Config::has('site.custom_upload_url')) {
            RC_Config::set('storage.disks.direct.url', RC_Upload::custom_upload_url());
            RC_Config::set('storage.disks.local.url', RC_Upload::custom_upload_url());
        }
    }

    /**
     * 自定义上传目录路径
     * @param string $url
     * @param string $path
     * @return string
     */
    public function onCustomUploadPathFilter($url, $path)
    {
        if (RC_Config::has('site.custom_upload_path')) {
            $upload_path = RC_Config::get('site.custom_upload_path');
        } else {
            $upload_path = SITE_UPLOAD_PATH;
        }

        $upload_path = $upload_path . ltrim($path, '/');

        return $upload_path;
    }

    /**
     * 自定义上传目录访问URL
     * @param string $url
     * @param string $path
     * @return string
     */
    public function onCustomUploadUrlFilter($url, $path)
    {
        if (RC_Config::has('site.custom_upload_url')) {
            $home_url = RC_Config::get('site.custom_upload_url');
            $url = $home_url . '/' . $path;
        }

        $upload_url = rtrim($url, '/');

        return $upload_url;
    }

    /**
     * 自定义项目访问URL
     * @param string $url
     * @param string $path
     * @return string
     */
    public function onCustomHomeUrlFilter($url, $path, $scheme)
    {
        if (RC_Config::has('site.custom_home_url')) {
            $home_url = RC_Config::get('site.custom_home_url');
            $url = $home_url . '/' . $path;
        }
        return rtrim($url, '/');
    }

    /**
     * 自定义项目访问URL
     * @param string $url
     * @param string $path
     * @return string
     */
    public function onCustomSiteUrlFilter($url, $path, $scheme)
    {
        if (RC_Config::has('site.custom_site_url')) {
            $home_url = RC_Config::get('site.custom_site_url');
            $url = $home_url . '/' . $path;
        }
        return rtrim($url, '/');
    }

    public function onSetEcjiaFilterRequestGetAction()
    {
        ecjia_filter_request_input($_GET);
        ecjia_filter_request_input($_REQUEST);
    }


    public function onEcjiaSetHeaderAction()
    {
        header('content-type: text/html; charset=' . RC_CHARSET);
        header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        //filter
        $events->addFilter(
            'pretty_page_table_data',
            'Ecjia\System\Subscribers\AllScreenSubscriber@onRemoveEnvPrettyPageTableDataFilter'
        );
        $events->addFilter(
            'upload_path',
            'Ecjia\System\Subscribers\AllScreenSubscriber@onCustomUploadPathFilter',
            10,
            2
        );
        $events->addFilter(
            'upload_url',
            'Ecjia\System\Subscribers\AllScreenSubscriber@onCustomUploadUrlFilter',
            10,
            2
        );
        $events->addFilter(
            'home_url',
            'Ecjia\System\Subscribers\AllScreenSubscriber@onCustomHomeUrlFilter',
            10,
            3
        );
        $events->addFilter(
            'site_url',
            'Ecjia\System\Subscribers\AllScreenSubscriber@onCustomSiteUrlFilter',
            10,
            3
        );

        //action
        $events->addAction(
            'init',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onUpdateCustomStoragePathAction'
        );
        $events->addAction(
            'ecjia_admin_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onEcjiaSetHeaderAction'
        );
        $events->addAction(
            'ecjia_front_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onEcjiaSetHeaderAction'
        );

        $events->addAction(
            'ecjia_admin_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onSetEcjiaFilterRequestGetAction'
        );
        $events->addAction(
            'ecjia_front_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onSetEcjiaFilterRequestGetAction'
        );
        $events->addAction(
            'ecjia_api_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onSetEcjiaFilterRequestGetAction'
        );
        $events->addAction(
            'ecjia_merchant_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onSetEcjiaFilterRequestGetAction'
        );
        $events->addAction(
            'ecjia_platform_finish_launching',
            'Ecjia\System\Subscribers\AdminHookSubscriber@onSetEcjiaFilterRequestGetAction'
        );

    }

}