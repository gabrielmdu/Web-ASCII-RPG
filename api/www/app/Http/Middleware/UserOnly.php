<?php

namespace App\Http\Middleware;

use App\Exceptions\WarpgException;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class UserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->isGuest()) {
            throw new WarpgException('Action forbidden for guests', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
