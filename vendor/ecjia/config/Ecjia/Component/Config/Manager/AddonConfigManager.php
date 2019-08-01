<?php


namespace Ecjia\Component\Config\Manager;


use ecjia;

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
        if ($use_platform)
        {
            $code = 'addon_' . ecjia::current_platform() . '_' . $code;
        }
        else
        {
            $code = 'addon_' . $code;
        }

        if ($this->getRepository()->has($code))
        {
            $value = $this->get($code);
        }
        else
        {
            $this->getRepository()->add('addon', $code, null, ['type' => 'hidden']);
            $value = null;
        }

        if ($unserialize)
        {
            $value or $value = serialize(array());
            $value = unserialize($value);
        }

        return $value;
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

        if ($use_platform)
        {
            $code = 'addon_' . ecjia::current_platform() . '_' . $code;
        }
        else
        {
            $code = 'addon_' . $code;
        }

        if ($serialize)
        {
            $value or $value = array();
            $value = serialize($value);
        }

        if ($this->getRepository()->has($code))
        {
            $result = $this->write($code, $value);
        }
        else
        {
            $result = $this->getRepository()->add('addon', $code, $value, ['type' => 'hidden']);
        }

        return $result;
    }

}