<?php

defined('IN_ECJIA') or exit('No permission resources.');

return array(

	'Ecjia\Component\Framework\EcjiaServiceProvider',
	'Ecjia\Component\Config\ConfigServiceProvider',
	'Ecjia\Component\Password\PasswordServiceProvider',
	'Ecjia\Component\AdminLog\AdminLogServiceProvider',
	'Ecjia\Component\CleanCache\CleanCacheServiceProvider',


    'Ecjia\System\Providers\RouteServiceProvider',
    'Ecjia\System\Providers\EventServiceProvider',
    'Ecjia\System\Providers\HookerServiceProvider',

    'Ecjia\System\AdminPanel\EcjiaAdminPanelServiceProvider',

);

//end