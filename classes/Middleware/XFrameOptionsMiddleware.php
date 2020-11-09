<?php


namespace Ecjia\System\Middleware;


use Closure;

class XFrameOptionsMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('X-Frame-Options', 'SAMEORIGIN');

        return $response;
    }

}