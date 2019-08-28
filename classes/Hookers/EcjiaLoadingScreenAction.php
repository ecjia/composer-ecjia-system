<?php


namespace Ecjia\System\Hookers;


use RC_Package;
use RC_Uri;
use Ecjia\System\Frameworks\Screens\NotInstallScreen;
use Ecjia\System\Frameworks\Screens\InstallScreen;

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
         * 加载ECJia项目主文件
         */
        RC_Package::package('system')->loadClass('ecjia', false);
        if ($this->is_installed_ecjia()) {
            (new NotInstallScreen())->loading();
        } else {
            (new InstallScreen())->loading();
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