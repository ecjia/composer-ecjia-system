<?php


namespace Ecjia\System\Hookers;


use RC_Uri;

class EcjiaLoadingScreenAction
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * 是否已经安装过ECJia
         */
        if ($this->is_installed_ecjia()) {
            $events = royalcms('Royalcms\Component\Hook\Dispatcher');
            $events->subscribe('Ecjia\System\Subscribers\InstalledScreenSubscriber');
        }
    }

    /**
     * 检测是否安装ecjia
     */
    protected function is_installed_ecjia()
    {
        $install_lock = storage_path() . '/data/install.lock';

        if (!file_exists($install_lock) && !defined('NO_CHECK_INSTALL')) {

            if (royalcms('request')->query('m') != 'installer')
            {
                $url = RC_Uri::url('installer/index/init');
                rc_redirect($url);
                exit();
            }

            return false;
        }

        return true;
    }


}