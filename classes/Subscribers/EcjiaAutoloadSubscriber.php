<?php


namespace Ecjia\System\Subscribers;


use RC_Hook;
use RC_Loader;
use RC_Package;
use Royalcms\Component\Hook\Dispatcher;

class EcjiaAutoloadSubscriber
{

    /**
     * auto laod classes
     */
    public function auto_load_classes($classname)
    {
        if (RC_Hook::has_action('class_' . $classname)) {
            return RC_Hook::do_action('class_' . $classname);
        }
        elseif (strpos($classname, 'ecjia') === 0) {
            return RC_Loader::load_sys_class($classname, false);
        }

        return false;
    }

    /**
     * 加载手动加载的类
     */
    public function onManualLoadClassesAction()
    {
        $autoload_classes = RC_Package::package('system')->loadConfig('autoload_class');
        if (!empty($autoload_classes)) {
            foreach ($autoload_classes as $key => $class) {
                RC_Hook::add_action($key, function () use ($class) {
                    RC_Package::package('system')->loadClass($class, false);
                });
            }
        }

        RC_Hook::do_action('ecjia_manual_load_classes');
    }

    /**
     * 注册自动加载方法
     */
    public function onAutoloadRegisterAction()
    {
        spl_autoload_register(array($this, 'auto_load_classes'), true);
    }

    /**
     * 注销自动加载方法
     */
    public function onAutoloadUnregisterAction()
    {
        spl_autoload_unregister(array($this, 'auto_load_classes'));
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->addAction(
            'init',
            'Ecjia\System\Subscribers\EcjiaAutoloadSubscriber@onAutoloadRegisterAction',
            1
        );

        $events->addAction(
            'init',
            'Ecjia\System\Subscribers\EcjiaAutoloadSubscriber@onManualLoadClassesAction',
            1
        );
    }
}