<?php


namespace Ecjia\System\Admins\Sessions;


class SessionManager
{
    protected $prefix;

    /**
     * @var
     */
    protected $connection;

    public function __construct()
    {
        $this->prefix = $this->markPrefix();
        $this->connection = $this->markConnection();
    }

    protected function markPrefix()
    {
        $defaultconnection = config('database.default');
        $connection = array_get(config('database.connections'), $defaultconnection);
        if (array_get($connection, 'database')) {
            $prefix = $connection['database'] . ':';
        }
        else {
            $prefix = 'ecjia_session:';
        }

        return $prefix;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    protected function markConnection()
    {
        return royalcms('redis')->connection('session');
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getKeys()
    {
        return $this->connection->keys('*');
    }

}