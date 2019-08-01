<?php


namespace Ecjia\Component\Config\Manager;


class ItemManager extends AbstractManager
{

    public function all()
    {
        return $this->getRepository()->all();
    }


    /**
     * Get the specified configuration value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->getRepository()->get($key, $default);
    }


    /**
     * Set a given configuration value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value)
    {
        return $this->getRepository()->set($key, $value);
    }


    /**
     * Write a given configuration value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function write($key, $value)
    {
        return $this->getRepository()->write($key, $value);
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return $this->getRepository()->has($key);
    }


    /**
     * 添加配置项
     *
     * @param string $group
     * @param string $key
     * @param string $value
     * @param array $options
     * @return bool
     */
    public function add($group, $key, $value, $options = [])
    {
        return $this->getRepository()->add($group, $key, $value, $options);
    }

    /**
     * 修改配置项
     *
     * @param string $group
     * @param string $key
     * @param string $value
     * @param array $options
     * @return bool
     */
    public function change($group, $key, $value, $options = [])
    {
        return $this->getRepository()->change($group, $key, $value, $options);
    }

    /**
     * 删除某个配置项
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        return $this->getRepository()->delete($key);
    }


}