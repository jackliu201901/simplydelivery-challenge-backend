<?php

namespace App\Http\Middleware;

use Closure;

class SimpleAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->header('X-API-KEY') !== $_ENV['API_KEY']) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
