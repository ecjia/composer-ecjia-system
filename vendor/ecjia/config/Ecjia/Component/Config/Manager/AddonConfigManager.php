<?php


namespace Ecjia\Component\Config\Manager;


class AddonConfigManager extends AbstractManager
{

    /**
     * 获取插件的配置项
     * addon_app_actives
     * addon_plugin_actives
     * addon_widget_actives
     * @param string $type
     * @param string $code
     * @param string|array $value
     */
    public function get($code, $unserialize = false, $use_platform = false)
    {
        return $this->getRepository()->getAddonConfig($code, $unserialize, $use_platform);
    }

    /**
     * 更新插件的配置项
     * addon_app_actives
     * addon_plugin_actives
     * addon_widget_actives
     * @param string $type
     * @param string $code
     * @param string|array $value
     */
    public function write($code, $value, $serialize = false, $use_platform = false)
    {
        return $this->getRepository()->writeAddonConfig($code, $value, $serialize, $use_platform);
    }

}