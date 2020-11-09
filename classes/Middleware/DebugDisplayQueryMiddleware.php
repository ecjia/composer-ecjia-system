<?php


namespace Ecjia\System\Middleware;


use Closure;
use ecjia;
use RC_DB;

class DebugDisplayQueryMiddleware
{

    public function handle($request, Closure $next)
    {
        if (ecjia::is_debug_display() && config('system.debug_display_query') === true) {
            RC_DB::enableQueryLog();
        }

        $response = $next($request);

        return $response;
    }

}