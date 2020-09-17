<?php
namespace Ecjia\System\AdminPanel;

use RC_Hook;
use RC_Loader;
use RC_Locale;
use RC_Service;
use Royalcms\Component\App\AppParentServiceProvider;
use Royalcms\Component\Hook\Dispatcher;

class EcjiaAdminPanelServiceProvider extends AppParentServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \Ecjia\System\AdminPanel\Subscribers\AdminPanelHookSubscriber::class
    ];

    public function boot(Dispatcher $dispatcher)
    {
        /**
         * step:1
         * 自动加载类管理
         */
        self::autoload();

        /**
         * step:2
         * 加载路由
         */
        self::routeDispacth();

        /**
         * step:3
         * 注册别名
         */
        self::registerFacades();

        /**
         * step:4
         * 默认加载
         */
        self::defaultLoading();


        $this->registerSession();

        $this->registerDefaultController();

        $this->registerSubscribers($dispatcher);

        $this->registerAppService();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('ecjia/admin', 'admin', __DIR__ . '/../');

    }

    /**
     * Register the session service.
     */
    public function registerSession()
    {

    }

    public function registerDefaultController()
    {
        RC_Hook::remove_all_actions('royalcms_default_controller');
    }

    /**
     * Register any other events for your application.
     *
     * @param  \Royalcms\Component\Hook\Dispatcher  $dispatcher
     * @return void
     */
    public function registerSubscribers(Dispatcher $dispatcher)
    {
        foreach ($this->subscribe as $subscriber) {
            $dispatcher->subscribe($subscriber);
        }
    }

    protected function registerAppService()
    {


    }

    /**
     * 加载手动加载的类
     */
    public function autoload()
    {
        $autoload_classes = config('admin::classmap');
        if (!empty($autoload_classes)) {
            foreach ($autoload_classes as $key => $class) {
                RC_Hook::add_action('class_'.$key, function () use ($class) {
                    RC_Loader::load_theme($class);
                });
            }
        }
    }


    /**
     * 路由加载调度
     */
    public static function routeDispacth()
    {
        $routemap = config('admin::route');
        if (!empty($routemap)) {
            foreach ($routemap as $key => $value) {
                list($class, $action) = explode('@', $value);
                if (empty($action)) {
                    $action = 'init';
                }
                RC_Hook::add_action($key, function () use ($action, $class) {
                    return (new $class)->$action();
                });
            }
        }
    }


    /**
     * 主题框架默认加载
     */
    public static function defaultLoading()
    {
        RC_Locale::loadThemeTextdomain('default');
    }

    /**
     * 加载jslang配置文件
     *
     * @return
     */
    public static function loadJSLang()
    {
        $jslang = config('admin::jslang');

        if (file_exists($jslang)) {
            return include_once $jslang;
        }

        return null;
    }

    /**
     * Load the alias = One less install step for the user
     */
    public static function registerFacades()
    {
        $facades = config('admin::facades');

        $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();

        collect($facades)->each(function ($item, $key) use ($loader) {
            $loader->alias($key, $item);
        });
    }



}