<?php


namespace Ecjia\Component\Config\Seeder;

/**
 * Class ConfigStruct
 * @package Ecjia\Component\Config
 */
class ConfigStruct
{

    protected $code;

    protected $name;

    protected $range = [];

    protected $value;

    protected $type;

    protected $store_range;

    protected $store_dir;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return ConfigStruct
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return ConfigStruct
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param array $range
     * @return ConfigStruct
     */
    public function setRange($range)
    {
        $this->range = $range;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return ConfigStruct
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return ConfigStruct
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoreRange()
    {
        return $this->store_range;
    }

    /**
     * @param mixed $store_range
     * @return ConfigStruct
     */
    public function setStoreRange($store_range)
    {
        $this->store_range = $store_range;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoreDir()
    {
        return $this->store_dir;
    }

    /**
     * @param mixed $store_dir
     * @return ConfigStruct
     */
    public function setStoreDir($store_dir)
    {
        $this->store_dir = $store_dir;
        return $this;
    }



}