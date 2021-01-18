<?php

namespace Bigmom\Poll\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('poll')->user()) {
            return redirect()->route('poll.getLogin');
        }

        return $next($request);
    }
}