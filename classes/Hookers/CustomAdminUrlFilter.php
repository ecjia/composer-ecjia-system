<?php


namespace Ecjia\System\Hookers;


use RC_Config;

class CustomAdminUrlFilter
{

    /**
     * 自定义后台管理访问URL
     * @param string $url
     * @param string $path
     * @return string
     */
    public function handle($url, $path)
    {
        if (RC_Config::has('site.custom_admin_url')) {
            $home_url = RC_Config::get('site.custom_admin_url');
        } else {
            $home_url = rtrim(ROOT_URL, '/');
        }

        $admin_url = $home_url . '/content/system';
        if ($path) {
            $admin_url .= '/' . $path;
        }

        return  $admin_url;
    }

}