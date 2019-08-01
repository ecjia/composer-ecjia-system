<?php


namespace Ecjia\Component\Config\Manager;


use Ecjia\Component\Config\Contracts\ConfigRepositoryInterface;

abstract class AbstractManager
{

    /**
     * The config repository implementation.
     *
     * @var \Ecjia\Component\Config\Contracts\ConfigRepositoryInterface
     */
    protected $repository;

    /**
     * Create a new config instance.
     *
     * @param  \Ecjia\Component\Config\Contracts\ConfigRepositoryInterface  $repository
     * @return void
     */
    public function __construct(ConfigRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the config repository instance.
     *
     * @return \Ecjia\Component\Config\Contracts\ConfigRepositoryInterface
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

    /**
     * Clean the caches.
     */
    public function clearCache()
    {
        $this->getRepository()->clearCache();
    }

}