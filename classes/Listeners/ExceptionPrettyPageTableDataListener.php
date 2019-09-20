<?php


namespace Ecjia\System\Listeners;


class ExceptionPrettyPageTableDataListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 移除$_ENV中的敏感信息
     *
     * @param $tables
     * @return void
     */
    public function handle(& $tables)
    {
        $env = collect($tables['Environment Variables']);
        $server = collect($tables['Server/Request Data']);

        $col = collect([
            'AUTH_KEY',
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
            'DB_PREFIX'
        ]);
        $col->map(function ($item) use ($env, $server) {
            $env->pull($item);
            $server->pull($item);
        });

        $tables['Environment Variables'] = $env->all();
        $tables['Server/Request Data'] = $server->all();
    }

}