<?php


namespace Ecjia\System\Hookers;


use ecjia_config;
use RC_App;

/**
 * ECJia 安装应用注册进AppManager
 * Class EcjiaInitLoadAction
 * @package Ecjia\System\Hookers
 */
class EcjiaInstallApplicationLoadAction
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        // 只获取已经安装的扩展应用
        $currents = ecjia_config::getAddonConfig('active_applications', true);

        $app = royalcms('app');

        $applications = $app->getApplicationLoader()->loadAppsWithIdentifier();

        collect($currents)->each(function ($app_id) use ($app, $applications) {

            if (isset($applications[$app_id])) {

                $bundle = $applications->get($app_id);

                if (!empty($bundle)) {

                    $app->extend($bundle->getAlias(), function () use ($bundle) {
                        return $bundle;
                    });

                    if ($bundle->getAlias() != $bundle->getDirectory()) {
                        $app->extend($bundle->getDirectory(), function () use ($bundle) {
                            return $bundle;
                        });
                    }

                }

            }


        });
    }

}