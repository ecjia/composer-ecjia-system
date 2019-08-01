<?php 
//  
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//   
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
namespace Ecjia\System\Providers;

use ReflectionClass;
use Royalcms\Component\App\AppParentServiceProvider;
use Ecjia\System\Plugin\PluginManager;
use Ecjia\System\Theme\ThemeManager;
use Ecjia\System\Frameworks\Site\SiteManager;
use Ecjia\System\Version\VersionManager;
use Ecjia\System\Config\Config;
use Ecjia\System\Config\ConfigModel;
use Ecjia\System\Config\DatabaseConfigRepository;
use Ecjia\System\Frameworks\CleanCache\CacheManger;

class SystemServiceProvider extends AppParentServiceProvider 
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

    }
    
    /**
     * Guess the package path for the provider.
     *
     * @return string
     */
    public function guessPackagePath($namespace = null)
    {
        $path = with(new ReflectionClass($this))->getFileName();
        
        return realpath(dirname($path).'/../../');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->package('ecjia/system');

	    $this->registerPluginManager();
	    
	    $this->registerThemeManager();
	    
	    $this->registerSiteManager();
	    
	    $this->registerVersionManager();
	    
	    $this->registerConfigRepository();
	    
	    $this->registerConfig();

	    $this->registerCache();

        $this->registerNamespaces();

        $this->registerProviders();

        $this->registerLocalProviders();

        $this->registerFacades();
	}

    /**
     * Register the Namespaces
     */
	public function registerNamespaces()
    {
        \Royalcms\Component\ClassLoader\ClassManager::addNamespaces(config('system::namespaces'));
    }

    /**
     * Register the Providers
     */
    public function registerProviders()
    {
        collect(config('system::provider'))->each(function($item) {
            $this->royalcms->register($item);
        });
    }

    /**
     * Register the Local Providers
     */
	public function registerLocalProviders() {
        if ($this->royalcms->environment() == 'local') {
            collect(config('system::provider_local'))->each(function($item) {
                $this->royalcms->register($item);
            });
        }
    }

    /**
     * Load the alias = One less install step for the user
     */
    protected function registerFacades()
    {
        $this->royalcms->booting(function()
        {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();

            collect(config('system::facade'))->each(function ($item, $key) use ($loader) {
                $loader->alias($key, $item);
            });

        });
    }
	
	/**
	 * Register the Plugin manager
	 * @return \Ecjia\System\Plugin\PluginManager
	 */
	public function registerPluginManager() 
	{
	    $this->royalcms->bindShared('ecjia.plugin.manager', function($royalcms){
	    	return new PluginManager($royalcms);
	    });
	}
	
	/**
	 * Register the Theme manager
	 * @return \Ecjia\System\Theme\ThemeManager
	 */
	public function registerThemeManager()
	{
	    $this->royalcms->bindShared('ecjia.theme.manager', function($royalcms){
	        return new ThemeManager($royalcms);
	    });
	}
	
	/**
	 * Register the Site manager
	 * @return \Ecjia\System\Site\SiteManager
	 */
	public function registerSiteManager()
	{
	    $this->royalcms->bindShared('ecjia.site.manager', function($royalcms){
	        return new SiteManager($royalcms);
	    });
	}
	
	/**
	 * Register the Site manager
	 * @return \Ecjia\System\Version\VersionManager
	 */
	public function registerVersionManager()
	{
	    $this->royalcms->bindShared('ecjia.version.manager', function($royalcms){
	        return new VersionManager($royalcms);
	    });
	}

    /**
     * Register the Cache service
     * @return \Ecjia\System\Admins\CleanCache\CacheManger
     */
    public function registerCache()
    {
        $this->royalcms->bindShared('ecjia.cache', function($royalcms)
        {
            return new CacheManger();
        });
    }
	
	
	/**
	 * Register the Config service
	 * @return \Ecjia\System\Config\Config
	 */
	public function registerConfig()
	{
	    $this->royalcms->bindShared('ecjia.config', function($royalcms)
	    {
	        $repository = $royalcms['ecjia.config.repository'];
	        
	        return new Config($repository);
	    });
	}
	
	/**
	 * Register the Config repository service.
	 *
	 * @return void
	 */
	protected function registerConfigRepository()
	{
	    $this->royalcms->bindShared('ecjia.config.repository', function($royalcms)
	    {
	        return new DatabaseConfigRepository(new ConfigModel());
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
	        'ecjia.config',
	        'ecjia.cache',
	    );
	}

    /**
     * Get a list of files that should be compiled for the package.
     *
     * @return array
     */
    public static function compiles()
    {
        $dir = __DIR__ . '/..';
        return [
            $dir . "/Http/Kernel.php",
            $dir . "/Exceptions/Handler.php",

            $dir . "/Facades/Config.php",
            $dir . "/Facades/ThemeManager.php",
            $dir . "/Facades/PluginManager.php",
            $dir . "/Facades/SiteManager.php",
            $dir . "/Facades/VersionManager.php",
            $dir . "/Facades/AdminLog.php",
            $dir . "/Admins/AdminLog/AdminLog.php",
            $dir . "/Admins/AdminLog/CompatibleTrait.php",
            $dir . "/Admins/AdminLog/AdminLogAction.php",
            $dir . "/Admins/AdminLog/AdminLogObject.php",

            $dir . "/Frameworks/Contracts/EcjiaSessionInterface.php",
            $dir . "/Frameworks/Contracts/EcjiaTemplateFileLoader.php",
            $dir . "/Frameworks/Contracts/PaidOrderProcessInterface.php",
            $dir . "/Frameworks/Contracts/ScriptLoaderInterface.php",
            $dir . "/Frameworks/Contracts/StyleLoaderInterface.php",
            $dir . "/Frameworks/Contracts/UserAllotPurview.php",
            $dir . "/Frameworks/Contracts/UserInterface.php",
            $dir . "/Frameworks/Contracts/ShopInterface.php",
            $dir . "/Frameworks/ScriptLoader/ScriptLoader.php",
            $dir . "/Frameworks/ScriptLoader/StyleLoader.php",
            $dir . "/Frameworks/Screens/NotInstallScreen.php",
            $dir . "/Frameworks/Screens/AllScreen.php",

            $dir . "/Frameworks/Model/Model.php",
            $dir . "/Frameworks/Model/InsertOnDuplicateKey.php",

            $dir . "/Frameworks/Sessions/Traits/EcjiaSessionSpecTrait.php",
            $dir . "/Frameworks/Sessions/Handler/MysqlSessionHandler.php",
            $dir . "/Frameworks/Sessions/Handler/RedisSessionHandler.php",

            $dir . "/Config/DatabaseConfigRepository.php",
            $dir . "/Config/ConfigRepositoryInterface.php",
            $dir . "/Config/ConfigModel.php",
            $dir . "/Config/Config.php",
            $dir . "/Config/CompatibleTrait.php",

            $dir . "/Theme/ThemeManager.php",
            $dir . "/Theme/Theme.php",
            $dir . "/Theme/ParseThemeStyle.php",

            $dir . "/BaseController/BasicController.php",
            $dir . "/BaseController/EcjiaController.php",
            $dir . "/BaseController/SimpleController.php",
            $dir . "/BaseController/SmartyController.php",
            $dir . "/BaseController/EcjiaAdminController.php",
            $dir . "/BaseController/EcjiaFrontController.php",

            $dir . "/ecjia_view.class.php",
            $dir . "/ecjia_notification.class.php",
            $dir . "/ecjia_loader.class.php",
            $dir . "/ecjia_screen.class.php",
        ];
    }

}
