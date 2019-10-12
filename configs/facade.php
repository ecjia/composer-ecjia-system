<?php

return array(


    'ecjia_config'       => 'Ecjia\Component\Config\Facades\Config',
    'ecjia_admin_log'    => 'Ecjia\Component\AdminLog\Facades\AdminLog',
    'ecjia_update_cache' => 'Ecjia\Component\CleanCache\Facades\CleanCache',


    'Ecjia_AppManager'    => 'Ecjia\Component\App\Facades\AppManager',
    'Ecjia_PluginManager' => 'Ecjia\Component\Plugin\Facades\PluginManager',

    'Ecjia_ThemeManager'   => 'Ecjia\System\Facades\ThemeManager',
    'Ecjia_SiteManager'    => 'Ecjia\System\Facades\SiteManager',
    'Ecjia_VersionManager' => 'Ecjia\System\Facades\VersionManager',


    //compatible
    'ecjia'                => 'Ecjia\Component\Framework\Ecjia',
    'ecjia_base'           => 'Ecjia\System\BaseController\EcjiaController',
    'ecjia_admin'          => 'Ecjia\System\BaseController\EcjiaAdminController',
    'ecjia_front'          => 'Ecjia\System\BaseController\EcjiaFrontController',
    'ecjia_cloud'          => 'Ecjia\Component\EcjiaCloud\EcjiaCloud',
    'ecjia_license'        => 'Ecjia\Component\EcjiaLicense\EcjiaLicense',
    'ecjia_error'          => 'Royalcms\Component\Error\Error',
    'ecjia_app'            => 'Ecjia\Component\App\Facades\AppManager',
    'ecjia_plugin'         => 'Ecjia\Component\Plugin\Facades\PluginManager',
    'ecjia_view'           => 'Ecjia\Component\View\Facades\View',
    'ecjia_widget'         => 'Ecjia\Component\Widget\Facades\Widget',
    'ecjia_option'         => 'Ecjia\Component\Option\Facades\Option',
    'ecjia_metadata'       => 'Ecjia\Component\Metadata\Facades\Metadata',
    'ecjia_notification'   => 'Ecjia\Component\Notification\Facades\Notification',
    'ecjia_open'           => 'Ecjia\Component\EcjiaOpen\EcjiaOpen',
    'ecjia_editor'         => 'Ecjia\Component\Editor\Facades\Editor',
    'ecjia_screen'         => 'Ecjia\Component\Screen\EcjiaScreen',
    'ecjia_purview'        => 'Ecjia\Component\Purview\EcjiaPurview',
    'ecjia_utility'        => 'Ecjia\Component\Support\EcjiaUtility',
    'ecjia_sort'           => 'Ecjia\Component\Support\EcjiaSort',


);