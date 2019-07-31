<?php
namespace Ecjia\Component\Config\Contracts;

interface ConfigModelInterface
{


    /**
     * 写入数据库数据
     * @param string $key
     * @param string $value
     */
    public function writeItem($key, $value);




}