<?php


namespace Ecjia\System\Admins\Sessions;


use Royalcms\Component\NativeSession\Serialize;

class SessionManager
{
    protected $prefix;

    protected $redis;

    /**
     * @var \Predis\Client
     */
    protected $connection;

    public function __construct($connection)
    {
        $this->prefix = $this->markPrefix();
//        $this->redis = new SessionManager;
        $this->connection = $connection;
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

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * 获取所有的Session Keys
     * @return mixed
     */
    public function getKeys()
    {
        return $this->connection->keys('*');
    }

    /**
     * 获取所有的Session Keys，并携带Values
     * @return array|mixed
     */
    public function getKeysWithValue()
    {
        $keys = $this->getKeys();

        return collect($keys)->mapWithKeys(function ($item) {
            return [$item => $this->connection->get($item)];
        })->all();
    }

    /**
     * 获取所有的Session Keys，并携带PHPSession反序列化的值
     * @return array|mixed
     */
    public function getKeysWithValueUnSerialize()
    {
        $keys = $this->getKeys();

        return collect($keys)->mapWithKeys(function ($item) {
            $data = $this->connection->get($item);
            $sessionData = Serialize::unserialize($data);
            $sessionData['ttl'] = $this->connection->ttl($item);
            $sessionData['ttl_formatted'] = \RC_Format::seconds2days($sessionData['ttl']);
            $sessionKey = $this->sessionKeyExtract($item);
            return [$sessionKey => $sessionData];
        })->all();
    }

    /**
     * 提取真正的Session Key
     * ecjia-b2b2c:session:c3cc14a29cd87ac4f1e7ec741f3957ebad9f0462
     * @param $key
     * @return string|string
     */
    protected function sessionKeyExtract($key)
    {
        $prefix = $this->prefix . 'session:';
        return str_replace($prefix, '', $key);
    }

    /**
     * 删除某个Session Key
     * @param $key
     */
    public function deleteWithSessionKey($key)
    {
        $prefix = $this->prefix . 'session:';

        $this->connection->del($key);
    }

}