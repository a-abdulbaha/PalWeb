<?php

namespace App\Http\Middleware;

use App\Events\UserViewed;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Used to track the individual page views of each user
 */
class TrackPageViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.track_page_views')) {
            event(new UserViewed);
        }

        return $next($request);
    }
}
