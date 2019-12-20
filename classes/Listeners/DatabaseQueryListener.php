<?php

namespace Ecjia\System\Listeners;


use Illuminate\Database\Events\QueryExecuted;
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
    public function handle(QueryExecuted $event)
    {
        if (config('system.debug')) {

            $query = $event->sql;
            $bindings = $event->bindings;
            $time = $event->time;

            $query = str_replace('?', '"'.'%s'.'"', $query);
            $sql = vsprintf($query, $bindings);
            RC_Logger::getLogger('sql')->info('sql:'.$sql);
        }

    }

}