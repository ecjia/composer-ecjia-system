<?php


namespace Ecjia\Component\CleanCache;

use Royalcms\Component\Support\ServiceProvider;

class CleanCacheServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCleanCache();

    }

    /**
     * Register the Cache service
     */
    public function registerCleanCache()
    {
        $this->royalcms->bindShared('ecjia.clean-cache', function($royalcms)
        {
            return new CacheManger();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'ecjia.clean-cache',
        );
    }


    /**
     * Get a list of files that should be compiled for the package.
     *
     * @return array
     */
    public static function compiles()
    {
        $dir = __DIR__ . '';
        return [
            $dir . "/Facades/CleanCache.php",
            $dir . "/CacheComponentAbstract.php",
            $dir . "/CacheFactory.php",
            $dir . "/CacheManager.php",
        ];
    }

}