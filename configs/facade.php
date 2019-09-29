<?php

return array(


    'ecjia_config'       => 'Ecjia\Component\Config\Facades\Config',
    'ecjia_admin_log'    => 'Ecjia\Component\AdminLog\Facades\AdminLog',
    'ecjia_update_cache' => 'Ecjia\Component\CleanCache\Facades\CleanCache',


    'Ecjia_AppManager'     => 'Ecjia\Component\App\Facades\AppManager',
    'Ecjia_PluginManager'  => 'Ecjia\System\Facades\PluginManager',
    'Ecjia_ThemeManager'   => 'Ecjia\System\Facades\ThemeManager',
    'Ecjia_SiteManager'    => 'Ecjia\System\Facades\SiteManager',
    'Ecjia_VersionManager' => 'Ecjia\System\Facades\VersionManager',


    //compatible
    'ecjia'                => 'Ecjia\Component\Framework\Ecjia',
    'ecjia_base'           => 'Ecjia\System\BaseController\EcjiaController',
    'ecjia_admin'          => 'Ecjia\System\BaseController\EcjiaAdminController',
    'ecjia_front'          => 'Ecjia\System\BaseController\EcjiaFrontController',
    'ecjia_cloud'          => 'Ecjia\Component\EcjiaCloud\EcjiaCloud',
    'ecjia_error'          => 'Royalcms\Component\Error\Error',
    'ecjia_app'            => 'Ecjia\Component\App\Facades\AppManager',


);