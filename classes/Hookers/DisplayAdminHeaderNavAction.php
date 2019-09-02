<?php


namespace Ecjia\System\Hookers;


use ecjia_admin_menu;

class DisplayAdminHeaderNavAction
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $menus = ecjia_admin_menu::singleton()->admin_menu();
        $menus_label = ecjia_admin_menu::singleton()->get_menu_label();

        echo '<ul class="nav" id="mobile-nav">' . PHP_EOL;

        foreach ($menus as $key => $group) {
            if ($group) {
                echo '<li class="dropdown">' . PHP_EOL;
                echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-list-alt icon-white"></i> ' . $menus_label[$key] . ' <b class="caret"></b></a>' . PHP_EOL;
                echo '<ul class="dropdown-menu">' . PHP_EOL;

                foreach ($group as $k => $menu) {
                    if ($menu->has_submenus) {
                        echo '<li class="dropdown">' . PHP_EOL;
                        if ($menu->link) {
                            echo '<a href="' . $menu->link . '" target="' . $menu->target . '">' . $menu->name . ' <b class="caret-right"></b></a>' . PHP_EOL;
                        } else {
                            echo '<a href="javascript:;" target="' . $menu->target . '">' . $menu->name . ' <b class="caret-right"></b></a>' . PHP_EOL;
                        }
                        echo '<ul class="dropdown-menu">' . PHP_EOL;
                        if ($menu->submenus) {
                            foreach ($menu->submenus as $child) {
                                if ($child->action == 'divider') {
                                    echo '<li class="divider"></li>' . PHP_EOL;
                                } elseif ($child->action == 'nav-header') {
                                    echo '<li class="nav-header">' . $child->name . '</li>' . PHP_EOL;
                                } else {
                                    echo '<li><a href="' . $child->link . '" target="' . $menu->target . '">' . $child->name . '</a></li>' . PHP_EOL;
                                }
                            }
                        }
                        echo '</ul>' . PHP_EOL;
                        echo '</li>' . PHP_EOL;
                    } else {
                        if ($menu->action == 'divider') {
                            echo '<li class="divider"></li>' . PHP_EOL;
                        } elseif ($menu->action == 'nav-header') {
                            echo '<li class="nav-header">' . $menu->name . '</li>' . PHP_EOL;
                        } else {
                            echo '<li><a href="' . $menu->link . '" target="' . $menu->target . '">' . $menu->name . '</a></li>' . PHP_EOL;
                        }
                    }
                }
            }

            echo '</ul>' . PHP_EOL;
            echo '</li>' . PHP_EOL;
        }
        echo '</ul>' . PHP_EOL;
    }

}