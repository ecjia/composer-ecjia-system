<?php

return array(

    'Ecjia_PluginManager'  => 'Ecjia\System\Facades\PluginManager',
    'Ecjia_ThemeManager'   => 'Ecjia\System\Facades\ThemeManager',
    'Ecjia_SiteManager'    => 'Ecjia\System\Facades\SiteManager',
    'Ecjia_VersionManager' => 'Ecjia\System\Facades\VersionManager',

    'ecjia_config'       => 'Ecjia\System\Facades\Config',
    'ecjia_update_cache' => 'Ecjia\System\Facades\Cache',
    'ecjia_admin_log'    => 'Ecjia\System\Facades\AdminLog',

    //compatible
    'ecjia_base'         => 'Ecjia\System\BaseController\EcjiaController',
    'ecjia_admin'        => 'Ecjia\System\BaseController\EcjiaAdminController',
    'ecjia_front'        => 'Ecjia\System\BaseController\EcjiaFrontController',
    'ecjia_error'        => 'Royalcms\Component\Error\Error',

);