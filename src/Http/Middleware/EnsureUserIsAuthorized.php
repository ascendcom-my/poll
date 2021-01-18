<?php

namespace Bigmom\Poll\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnsureUserIsAuthorized
{
    /**
     * Ensures the user is authorized to visit poll CMS.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (config('poll.restrict-usage') && Gate::forUser(Auth::guard('poll')->user())->has('managePoll')) {
            $allowed = app()->environment('local')
                || Gate::forUser(Auth::guard('poll')->user())->allows('managePoll');

            abort_unless($allowed, 403);
        }

        return $next($request);
    }
}
