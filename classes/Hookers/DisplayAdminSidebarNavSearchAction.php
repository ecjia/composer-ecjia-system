<?php


namespace Ecjia\System\Hookers;


use ecjia_admin_menu;

class DisplayAdminSidebarNavSearchAction
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {

        $menus = ecjia_admin_menu::singleton()->admin_menu();

        if (!empty($menus['apps'])) {
            foreach ($menus['apps'] as $k => $menu) {
                if ($menu->has_submenus) {
                    if ($menu->submenus) {
                        foreach ($menu->submenus as $child) {
                            if ($child->action == 'divider') {
                                echo '<li class="divider"></li>';
                            } elseif ($child->action == 'nav-header') {
                                echo '<li class="nav-header">' . $child->name . '</li>';
                            } else {
                                echo '<li><a href="' . $child->link . '">' . $child->name . '</a></li>';
                            }
                        }
                    }
                }
            }
        }

    }

}