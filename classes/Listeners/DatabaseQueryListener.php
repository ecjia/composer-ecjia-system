<?php


namespace Ecjia\System\Listeners;


use RC_Logger;

class DatabaseQueryListener
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
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle($query, $bindings, $time)
    {
        if (config('system.debug')) {
            $query = str_replace('?', '"'.'%s'.'"', $query);
            $sql = vsprintf($query, $bindings);
            RC_Logger::getLogger(RC_Logger::LOG_SQL)->info('sql:'.$sql);
        }

    }

}