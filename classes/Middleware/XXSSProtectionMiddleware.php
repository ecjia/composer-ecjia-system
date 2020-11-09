<?php


namespace Ecjia\System\Middleware;


use Closure;

class XXSSProtectionMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('X-XSS-Protection', '1; mode=block');

        return $response;
    }

}