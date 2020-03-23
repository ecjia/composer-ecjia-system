<?php

namespace Ecjia\System\Services;

use Ecjia\System\Admins\Users\AdminUserModel;
use ecjia_admin;
use RC_Cache;
use RC_Uri;

/**
 * 后台菜单API
 * @author royalwang
 */
class ShortcutMenuService
{
    /**
     * @param $options
     * @return array|\admin_menu
     */
	public function handle(& $options)
    {
        $admin_id = $_SESSION['admin_id'];

        $cache_key = 'admin_navlist' . $admin_id;

        $shortcut = RC_Cache::remember($cache_key, 3600, function () use ($admin_id) {
            $admin_navlist = $this->getUserNavList($admin_id);

            $shortcut = ecjia_admin::make_admin_menu('shortcut', __('我的快捷菜单'), '', 0);

            $submenus = array();
            $i = 0;
            foreach ($admin_navlist as $url => $name) {
                $submenus[] = ecjia_admin::make_admin_menu('shortcut_' . $i, $name, $url, $i);
                $i++;
            }

            if (!empty($admin_navlist)) {
                $submenus[] = ecjia_admin::make_admin_menu('divider', '', '', 99);
            }

            $submenus[] = ecjia_admin::make_admin_menu('shortcut_100', __('设置快捷菜单'), RC_Uri::url('@privilege/quick_nav'), 100);

            $shortcut->add_submenu($submenus);

            return $shortcut;
        });

        return $shortcut;
    }

    protected function getUserNavList($user_id)
    {
        $nav_list = AdminUserModel::where('user_id', $user_id)->pluck('nav_list');
        if (!empty($nav_list)) {
            $navs = explode(',', $nav_list);
            $nav_list = collect($navs)->mapWithKeys(function ($item) {
                $tmp = explode('|', $item);
                return [$tmp[1] => $tmp[0]];
            })->all();
        }

        return $nav_list;
    }
}

// end