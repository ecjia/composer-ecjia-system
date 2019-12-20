<?php

namespace Ecjia\System\Providers;

use Royalcms\Component\Support\ServiceProvider;
use Royalcms\Component\Hook\Dispatcher;

class HookerServiceProvider extends ServiceProvider
{
    /**
     * The hook action listener mappings for the application.
     *
     * @var array
     */
    protected $actions = [
        'ecjia_loading' => [
            'Ecjia\System\Hookers\EcjiaLoadingScreenAction'
        ],

        'init' => [
            ['Ecjia\System\Hookers\EcjiaInitLoadAction', 2],
            ['Ecjia\System\Hookers\EcjiaLoadLangAction', 9]
        ],

        'ecjia_loaded' => [
            'Ecjia\System\Hookers\EcjiaInstallApplicationLoadAction'
        ],

        'ecjia_compatible_process' => [
            'Ecjia\System\Hookers\EcjiaFrontCompatibleProcessAction'
        ],

        'ecjia_front_access_session' => [
            'Ecjia\System\Hookers\EcjiaFrontAccessSessionAction'
        ],

        'shop_config_updated_after' => [
            'Ecjia\System\Hookers\ShopConfigUpdatedAfterAction'
        ],

        'app_scan_bundles_filter' => [
            'Ecjia\System\Hookers\EcjiaLoadSystemApplicationFilter',
        ],

        'app_activation_bundles' => [
            'Ecjia\System\Hookers\EcjiaInstallApplicationBundleFilter',
        ],

    ];

    /**
     * The hook filters listener mappings for the application.
     *
     * @var array
     */
    protected $filters = [
        'set_ecjia_config_filter' => [
            'Ecjia\System\Hookers\SetEcjiaConfigFilter'
        ],

        'system_static_url' => [
            ['Ecjia\System\Hookers\CustomSystemStaticUrlFilter', 10, 2],
        ],

        'admin_url' => [
            ['Ecjia\System\Hookers\CustomAdminUrlFilter', 10, 2],
        ],

        'upload_default_random_filename' => [
            'Ecjia\System\Hookers\UploadDefaultRandomFilenameFilter',
        ],

        'set_server_timezone' => [
            'Ecjia\System\Hookers\SetCurrentTimezoneFilter',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Ecjia\System\Subscribers\EcjiaAutoloadSubscriber',
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Royalcms\Component\Hook\Dispatcher  $dispatcher
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {

        foreach ($this->actions as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->addAction($event, $listener);
            }
        }

        foreach ($this->filters as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->addFilter($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $dispatcher->subscribe($subscriber);
        }

    }


    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function filters()
    {
        return $this->filters;
    }

}
