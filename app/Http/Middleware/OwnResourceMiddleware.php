<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnResourceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('user')->id === $request->user()->id) :
            return $next($request);
        else :
            return abort(403, 'Forbidden');
        endif;
    }
}
