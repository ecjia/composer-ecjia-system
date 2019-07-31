<?php


namespace Ecjia\Component\Config;


class GroupManager extends AbstractManager
{

    /**
     * Get all groups.
     */
    public function all()
    {
        return $this->getRepository()->getGroups();
    }


    /**
     * Determine if a configuration group exists.
     *
     * @param  string  $group
     * @return bool
     */
    public function has($group)
    {
        return $this->getRepository()->hasGroup($group);
    }


    /**
     * Get the specified configuration value.
     *
     * @param  string  $group
     * @param  mixed   $default
     * @return mixed
     */
    public function get($group, $default = null)
    {
        return $this->getRepository()->getGroup($group, $default);
    }


    public function delete($group)
    {
        return $this->getRepository()->deleteGroup($group);
    }


    public function add($group, $id = null)
    {
        return $this->getRepository()->addGroup($group, $id);
    }






}