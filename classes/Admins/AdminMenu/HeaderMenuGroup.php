<?php


namespace Ecjia\System\Admins\AdminMenu;


use Ecjia\Component\Menu\Menu;
use ecjia_app;
use RC_Cache;

class HeaderMenuGroup
{
    /**
     * 缓存Key
     * @var string
     */
    protected $cache_key;

    protected $cache_seconds = 3600;

    protected $groups = [
        \Ecjia\Component\Menu\MenuGroup\SystemMenuGroup::class,
        \Ecjia\Component\Menu\MenuGroup\AppsMenuGroup::class,
        \Ecjia\Component\Menu\MenuGroup\ToolsMenuGroup::class,
        \Ecjia\Component\Menu\MenuGroup\SettingMenuGroup::class,
        \Ecjia\Component\Menu\MenuGroup\ServiceMenuGroup::class,
    ];

    public function __construct()
    {
        if (defined('RC_SITE')) {
            $this->cache_key = 'admin_menus' . constant('RC_SITE');
        } else {
            $this->cache_key = 'admin_menus';
        }
    }

    /**
     * 判断是否启用主题目录
     */
    protected function isExistsThemes()
    {
        //判断是否启用主题目录，启用才开放此功能
        if (file_exists(SITE_THEME_PATH) || file_exists(RC_THEME_PATH)) {
            $this->groups[] = \Ecjia\Component\Menu\MenuGroup\SkinMenuGroup::class;
        }
    }

    /**
     * 获取菜单组
     */
    public function getGroups($apps)
    {
        $this->isExistsThemes();

        return collect($this->groups)->mapWithKeys(function ($group) use ($apps) {
            /**
             * @var \Ecjia\Component\Menu\MenuGroup\AbstractMenuGroup $menuGroup
             */
            $menuGroup = new $group;
            $data = [
                'group' => $menuGroup->getGroup(),
                'label' => $menuGroup->getLabel(),
                'menus' => $menuGroup->loadAppMenus($apps),
            ];
            return [$menuGroup->getGroup() => $data];
        })->all();
    }

    /**
     * 获取菜单数据，支持缓存
     * @return mixed
     */
    public function getMenuData()
    {
        $menus = RC_Cache::remember($this->cache_key, $this->cache_seconds, function () {
            $apps = ecjia_app::installed_app_floders();
            return $this->getGroups($apps);
        });
        return $menus;
    }

    /**
     * 获取菜单
     */
    public function getMenus()
    {
        $menus = $this->getMenuData();

        $menus = $this->filterMenu($menus);

        return $menus;
    }

    /**
     * 过滤菜单
     * @param $menus
     * @return array
     */
    protected function filterMenu($menus)
    {
        //判断权限
        return collect($menus)->map(function ($item, $key) {
            $menus = collect($item['menus'])->map(function (Menu $menu, $key) {
                if ($this->checkMenuPermissions($menu)) {
                    if ($menu->hasSubmenus()) {
                        $submenus = collect($menu->getSubmenus())->map(function (Menu $menu, $key) {
                            if ($this->checkMenuPermissions($menu)) {
                                return $menu;
                            }
                            else {
                                return null;
                            }
                        })->filter()->all();

                        //子菜单为空时返回null，移除该一级菜单
                        if (empty($submenus)) {
                            return null;
                        }

                        $menu->setSubmenus($submenus);
                        return $menu;
                    }

                    return null;
                }
                else {
                    return null;
                }
            })->filter()->all();

            // 如果菜单元素长度为0则删除该组
            if (empty($menus)) {
                return null;
            }

            $item['menus'] = $menus;

            return $item;
        })->filter()->all();
    }

    /**
     * 检查菜单权限
     * @param Menu $menu
     * @return bool
     */
    protected function checkMenuPermissions(Menu $menu)
    {
        if ($menu->hasPurviews()) {
            $boole = false;
            foreach ($menu->getPurviews() as $action) {
                $boole = $boole || $this->verifyAssignedPermissions($action);
            }
            return $boole;
        }
        return true;
    }

    /**
     * 判断管理员对某一个操作是否有权限。
     *
     * 根据当前对应的action_code，然后再和用户session里面的action_list做匹配，以此来决定是否可以继续执行。
     * @param string    $action   操作对应的$action
     * @return bool
     */
    public function verifyAssignedPermissions($action, $action_list = null)
    {
        if (empty($action_list)) {
            $action_list = $_SESSION['action_list'];
        }

        if ($action_list == 'all') {
            return true;
        }

        if (strpos(',' . $action_list . ',', ',' . $action . ',') === false) {
            return false;
        } else {
            return true;
        }
    }

}