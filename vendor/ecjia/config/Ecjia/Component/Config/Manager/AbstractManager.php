<?php


namespace Ecjia\Component\Config;


abstract class AbstractManager
{

    /**
     * The config repository implementation.
     *
     * @var \Ecjia\System\Config\ConfigRepositoryInterface
     */
    protected $repository;

    /**
     * Create a new config instance.
     *
     * @param  \Ecjia\System\Config\ConfigRepositoryInterface  $repository
     * @return void
     */
    public function __construct(ConfigRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the config repository instance.
     *
     * @return \Ecjia\System\Config\ConfigRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get the all codes.
     *
     * @return array
     */
    public function allKeys()
    {
        return $this->getRepository()->allKeys();
    }

    public function clearCache()
    {
        return $this->getRepository()->clearCache();
    }

}